# WhatsApp Gateway Microservice

A standalone Node.js microservice powered by `@whiskeysockets/baileys` that acts as a local WhatsApp Web bridge for Laravel CRM.

---

## ⚠️ Important Compliance & Account Safety Warnings

1. **Unofficial Web Automation**: This service automates a WhatsApp Web multi-device session. WhatsApp actively detects and bans numbers that send unsolicited, automated bulk messages.
2. **User Consent Required**: Never send marketing messages to contacts who have not provided express consent.
3. **Pacing & Throttling**: Never bypass the CRM's safety throttle (minimum 15–30 seconds recommended per message).
4. **Security**: This service binds exclusively to `127.0.0.1:3001` and requires the `X-Gateway-Key` shared secret header for all API requests.

---

## 🚀 Setup & Running

### 1. Install Dependencies
```bash
cd whatsapp-gateway
npm install
```

### 2. Configure Environment
Create a `.env` file in `whatsapp-gateway/` (optional if defaults are used):
```env
PORT=3001
GATEWAY_KEY=your_secret_gateway_key_here
LOG_LEVEL=warn
```

### 3. Start the Gateway
```bash
npm start
```

### 4. Background Process Management (PM2)
To keep the service running continuously in production:
```bash
npm install -g pm2
pm2 start index.js --name "whatsapp-gateway"
pm2 save
```

---

## 🔌 API Reference (Localhost Only)

All requests must include the header `X-Gateway-Key: <GATEWAY_KEY>`.

- `GET /status` — Returns current connection state (`connected`, `number`, `pushName`).
- `GET /qr` — Returns base64 PNG data URL of the pairing QR code.
- `POST /send` — Send message/media:
  ```json
  {
    "to": "919876543210",
    "mediaPath": "/path/to/storage/brochure.pdf",
    "caption": "Check out our latest product catalog!",
    "filename": "Catalog.pdf"
  }
  ```
- `POST /logout` — Unlinks the current session and clears credentials.
