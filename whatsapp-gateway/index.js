import express from 'express';
import cors from 'cors';
import dotenv from 'dotenv';
import pino from 'pino';
import QRCode from 'qrcode';
import fs from 'fs';
import path from 'path';
import crypto from 'crypto';
import mime from 'mime-types';
import { fileURLToPath } from 'url';
import {
  makeWASocket,
  DisconnectReason,
  useMultiFileAuthState,
  fetchLatestBaileysVersion
} from '@whiskeysockets/baileys';

dotenv.config();

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const PORT = parseInt(process.env.PORT || '3001', 10);
const HOST = '127.0.0.1';
const SESSION_DIR = path.join(__dirname, '.session');

if (!fs.existsSync(SESSION_DIR)) {
  fs.mkdirSync(SESSION_DIR, { recursive: true });
}

const logger = pino({ level: process.env.LOG_LEVEL || 'warn' });

let sock = null;
let currentQR = null;
let currentQRBase64 = null;
let connectionStatus = {
  connected: false,
  number: null,
  pushName: null,
  state: 'connecting',
  error: null
};

// Security middleware to validate X-Gateway-Key with constant-time comparison
function validateGatewayKey(req, res, next) {
  const configuredKey = (process.env.GATEWAY_KEY || '').trim();
  if (!configuredKey) {
    // If no key configured in dev environment, allow request
    return next();
  }

  const providedKey = (req.headers['x-gateway-key'] || '').trim();
  const bufA = Buffer.from(providedKey);
  const bufB = Buffer.from(configuredKey);

  if (bufA.length !== bufB.length || !crypto.timingSafeEqual(bufA, bufB)) {
    return res.status(401).json({
      success: false,
      error: 'Unauthorized: Invalid X-Gateway-Key header'
    });
  }

  next();
}

async function startWhatsAppSocket() {
  const { state, saveCreds } = await useMultiFileAuthState(SESSION_DIR);
  const { version, isLatest } = await fetchLatestBaileysVersion();

  sock = makeWASocket({
    version,
    logger,
    printQRInTerminal: false,
    auth: state,
    browser: ['Laravel CRM', 'Chrome', '120.0.0'],
    generateHighQualityLinkPreview: false,
    connectTimeoutMs: 60000,
    keepAliveIntervalMs: 30000
  });

  sock.ev.on('creds.update', saveCreds);

  sock.ev.on('connection.update', async (update) => {
    const { connection, lastDisconnect, qr } = update;

    if (qr) {
      currentQR = qr;
      try {
        currentQRBase64 = await QRCode.toDataURL(qr, { margin: 2, scale: 6 });
      } catch (err) {
        console.error('Failed to generate QR base64:', err);
      }
      connectionStatus = {
        connected: false,
        number: null,
        pushName: null,
        state: 'qr_ready',
        error: null
      };
    }

    if (connection === 'close') {
      const statusCode = lastDisconnect?.error?.output?.statusCode;
      const shouldReconnect = statusCode !== DisconnectReason.loggedOut;

      currentQR = null;
      currentQRBase64 = null;
      connectionStatus = {
        connected: false,
        number: null,
        pushName: null,
        state: 'disconnected',
        error: lastDisconnect?.error?.message || 'Connection closed'
      };

      if (shouldReconnect) {
        setTimeout(startWhatsAppSocket, 3000);
      } else {
        // Logged out - clean session
        try {
          fs.rmSync(SESSION_DIR, { recursive: true, force: true });
          fs.mkdirSync(SESSION_DIR, { recursive: true });
        } catch (e) {}
        setTimeout(startWhatsAppSocket, 2000);
      }
    } else if (connection === 'open') {
      currentQR = null;
      currentQRBase64 = null;
      const jid = sock.user?.id || '';
      const cleanNumber = jid.split(':')[0] || jid.split('@')[0];

      connectionStatus = {
        connected: true,
        number: cleanNumber,
        pushName: sock.user?.name || null,
        state: 'connected',
        error: null
      };
      console.log(`[WhatsApp Gateway] Connected as: ${cleanNumber} (${sock.user?.name || 'Unknown'})`);
    }
  });
}

// Initialize Express App
const app = express();
app.use(cors());
app.use(express.json({ limit: '50mb' }));
app.use(express.urlencoded({ extended: true, limit: '50mb' }));

// Public root & health endpoints
app.get('/', (req, res) => {
  res.json({
    status: 'online',
    service: 'WhatsApp Gateway Microservice',
    connected: connectionStatus.connected,
    number: connectionStatus.number || null,
    pushName: connectionStatus.pushName || null,
    crm_url: 'http://127.0.0.1:8000/admin/whatsapp'
  });
});

app.get('/health', (req, res) => {
  res.json({ status: 'ok', time: new Date().toISOString() });
});

app.use(validateGatewayKey);

// GET /status
app.get('/status', (req, res) => {
  res.json({
    success: true,
    ...connectionStatus,
    qrAvailable: !!currentQRBase64
  });
});

// GET /qr
app.get('/qr', (req, res) => {
  res.json({
    success: true,
    connected: connectionStatus.connected,
    state: connectionStatus.state,
    qr: currentQRBase64
  });
});

// POST /send
app.post('/send', async (req, res) => {
  try {
    const { to, mediaPath, mediaUrl, caption, filename } = req.body;

    if (!to) {
      return res.status(400).json({
        success: false,
        error: 'Missing required parameter: "to"'
      });
    }

    if (!sock || !connectionStatus.connected) {
      return res.status(503).json({
        success: false,
        error: 'WhatsApp Gateway is not connected. Please scan QR code in CRM settings.'
      });
    }

    const digitsOnly = to.toString().replace(/\D/g, '');
    if (digitsOnly.length < 8) {
      return res.status(400).json({
        success: false,
        error: `Invalid phone number format: "${to}"`
      });
    }

    const jid = `${digitsOnly}@s.whatsapp.net`;
    let messagePayload = {};

    if (mediaPath) {
      if (!fs.existsSync(mediaPath)) {
        return res.status(404).json({
          success: false,
          error: `Media file not found at path: "${mediaPath}"`
        });
      }

      const buffer = fs.readFileSync(mediaPath);
      const detectedMime = mime.lookup(mediaPath) || 'application/octet-stream';
      const cleanFilename = filename || path.basename(mediaPath);

      const INLINE_MEDIA_LIMIT_BYTES = 16 * 1024 * 1024; // WhatsApp's own cap for inline image/video playback
      const isOversizedMedia = buffer.length > INLINE_MEDIA_LIMIT_BYTES;

      if (detectedMime.startsWith('image/') && !isOversizedMedia) {
        messagePayload = {
          image: buffer,
          caption: caption || '',
          mimetype: detectedMime
        };
      } else if (detectedMime.startsWith('video/') && !isOversizedMedia) {
        messagePayload = {
          video: buffer,
          caption: caption || '',
          mimetype: detectedMime
        };
      } else {
        // Document / PDF / Sheet, OR an image/video too large to send inline —
        // WhatsApp allows documents up to 2GB, so this is how a large brochure/video
        // still gets delivered instead of failing WhatsApp's inline media size cap.
        messagePayload = {
          document: buffer,
          mimetype: detectedMime,
          fileName: cleanFilename,
          caption: caption || ''
        };
      }
    } else {
      // Text only
      messagePayload = {
        text: caption || ''
      };
    }

    const result = await sock.sendMessage(jid, messagePayload);

    return res.json({
      success: true,
      messageId: result?.key?.id || null,
      timestamp: result?.messageTimestamp || Date.now()
    });
  } catch (err) {
    console.error('[WhatsApp Send Error]:', err);
    return res.status(500).json({
      success: false,
      error: err.message || 'Failed to send WhatsApp message'
    });
  }
});

// POST /logout
app.post('/logout', async (req, res) => {
  try {
    if (sock) {
      try {
        await sock.logout();
      } catch (e) {}
    }

    try {
      fs.rmSync(SESSION_DIR, { recursive: true, force: true });
      fs.mkdirSync(SESSION_DIR, { recursive: true });
    } catch (e) {}

    connectionStatus = {
      connected: false,
      number: null,
      pushName: null,
      state: 'disconnected',
      error: null
    };
    currentQR = null;
    currentQRBase64 = null;

    setTimeout(startWhatsAppSocket, 1000);

    return res.json({
      success: true,
      message: 'Logged out successfully. New QR will be generated.'
    });
  } catch (err) {
    return res.status(500).json({
      success: false,
      error: err.message || 'Logout failed'
    });
  }
});

// Start Express and WhatsApp Baileys socket
app.listen(PORT, HOST, () => {
  console.log(`[WhatsApp Gateway] Server running at http://${HOST}:${PORT}`);
  startWhatsAppSocket().catch((err) => {
    console.error('[WhatsApp Gateway] Startup Error:', err);
  });
});
