<?php

namespace Crm\WhatsApp\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class WhatsAppClientService
{
    protected Client $client;

    protected string $baseUrl;

    protected ?string $gatewayKey;

    public function __construct(?string $baseUrl = null, ?string $gatewayKey = null)
    {
        $this->baseUrl = rtrim($baseUrl ?: (string) config('whatsapp.gateway_url', 'http://127.0.0.1:3001'), '/');
        $this->gatewayKey = $gatewayKey ?: config('whatsapp.gateway_key');

        $headers = [
            'Accept'       => 'application/json',
            'Content-Type' => 'application/json',
        ];

        if (! empty($this->gatewayKey)) {
            $headers['X-Gateway-Key'] = $this->gatewayKey;
        }

        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'headers'  => $headers,
            'timeout'  => 45,
        ]);
    }

    /**
     * Get connection status from the Node.js Gateway.
     */
    public function getStatus(): array
    {
        try {
            $response = $this->client->get('/status');
            $data = json_decode($response->getBody()->getContents(), true);

            return $data ?: ['connected' => false, 'error' => 'Invalid JSON from gateway'];
        } catch (\Throwable $e) {
            return [
                'connected' => false,
                'state'     => 'offline',
                'error'     => 'Gateway offline or unreachable (' . $e->getMessage() . ')',
            ];
        }
    }

    /**
     * Get the latest QR code for linking WhatsApp.
     */
    public function getQrCode(): array
    {
        try {
            $response = $this->client->get('/qr');
            $data = json_decode($response->getBody()->getContents(), true);

            return $data ?: ['connected' => false, 'qr' => null];
        } catch (\Throwable $e) {
            return [
                'connected' => false,
                'qr'        => null,
                'error'     => 'Gateway unreachable (' . $e->getMessage() . ')',
            ];
        }
    }

    /**
     * Send a message or media file to a single recipient.
     */
    public function sendMessage(string $to, ?string $mediaPath = null, ?string $caption = null, ?string $filename = null): array
    {
        try {
            $payload = [
                'to'        => $to,
                'caption'   => $caption,
                'mediaPath' => $mediaPath,
                'filename'  => $filename,
            ];

            $response = $this->client->post('/send', [
                'json' => $payload,
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            return $data ?: ['success' => false, 'error' => 'Invalid response from gateway'];
        } catch (GuzzleException $e) {
            $responseBody = $e->hasResponse() ? $e->getResponse()->getBody()->getContents() : null;
            $errorMsg = $e->getMessage();

            if ($responseBody) {
                $decoded = json_decode($responseBody, true);
                if (isset($decoded['error'])) {
                    $errorMsg = $decoded['error'];
                }
            }

            Log::error("[WhatsApp Gateway Send Error] to: {$to}, error: {$errorMsg}");

            return [
                'success' => false,
                'error'   => $errorMsg,
            ];
        } catch (\Throwable $e) {
            Log::error("[WhatsApp Client Error] to: {$to}, error: " . $e->getMessage());

            return [
                'success' => false,
                'error'   => $e->getMessage(),
            ];
        }
    }

    /**
     * Disconnect / logout session on Gateway.
     */
    public function logout(): array
    {
        try {
            $response = $this->client->post('/logout');
            $data = json_decode($response->getBody()->getContents(), true);

            return $data ?: ['success' => true];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'error'   => $e->getMessage(),
            ];
        }
    }
}
