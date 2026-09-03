<?php

namespace Crm\WhatsApp\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Crm\WhatsApp\Models\WhatsappCampaign;
use Crm\WhatsApp\Models\WhatsappCampaignRecipient;
use Crm\WhatsApp\Models\WhatsappDoNotContact;
use Crm\WhatsApp\Services\WhatsAppClientService;

class SendWhatsappCampaignMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Number of times the job may be attempted.
     */
    public $tries = 1;

    public function __construct(
        public int $campaignId,
        public int $recipientId
    ) {
        $this->onConnection('database');
    }

    /**
     * Execute the job.
     */
    public function handle(WhatsAppClientService $client): void
    {
        $campaign = WhatsappCampaign::find($this->campaignId);
        $recipient = WhatsappCampaignRecipient::find($this->recipientId);

        if (! $campaign || ! $recipient) {
            return;
        }

        // Circuit breaker / Pause check: Do not send if campaign was paused or cancelled
        if ($campaign->status !== 'running') {
            Log::info("[WhatsApp Job] Skipping recipient {$recipient->phone_e164} because campaign {$campaign->id} is {$campaign->status}");
            return;
        }

        // Idempotency: Skip if already sent
        if ($recipient->status === 'sent') {
            return;
        }

        // Check Do Not Contact (DNC) list
        $isDnc = WhatsappDoNotContact::where('phone_e164', $recipient->phone_e164)->exists();
        if ($isDnc) {
            $recipient->update([
                'status'        => 'skipped_dnc',
                'error_message' => 'Skipped: Phone number is on Do Not Contact (DNC) list',
            ]);

            $this->checkCampaignCompletion($campaign);
            return;
        }

        // Mark as sending
        $recipient->increment('attempts');
        $recipient->update(['status' => 'sending']);

        // Resolve absolute media file path
        $absoluteMediaPath = null;
        if (! empty($campaign->brochure_path)) {
            if (Storage::disk('public')->exists($campaign->brochure_path)) {
                $absoluteMediaPath = Storage::disk('public')->path($campaign->brochure_path);
            } elseif (file_exists($campaign->brochure_path)) {
                $absoluteMediaPath = $campaign->brochure_path;
            } elseif (Storage::exists($campaign->brochure_path)) {
                $absoluteMediaPath = Storage::path($campaign->brochure_path);
            }
        }

        // Send through Gateway
        $result = $client->sendMessage(
            to: $recipient->phone_e164,
            mediaPath: $absoluteMediaPath,
            caption: $campaign->caption,
            filename: $campaign->brochure_name
        );

        if (! empty($result['success'])) {
            $recipient->update([
                'status'        => 'sent',
                'sent_at'       => now(),
                'error_message' => null,
            ]);

            $campaign->increment('sent_count');
            $campaign->update(['consecutive_failures' => 0]);
        } else {
            $errorMessage = $result['error'] ?? 'Unknown send error';

            $recipient->update([
                'status'        => 'failed',
                'error_message' => $errorMessage,
            ]);

            $campaign->increment('failed_count');
            $campaign->increment('consecutive_failures');

            // Automatic Circuit Breaker Check
            $campaign->refresh();
            if ($campaign->consecutive_failures >= $campaign->consecutive_failure_limit) {
                $campaign->update([
                    'status'       => 'paused',
                    'pause_reason' => "Auto-paused: {$campaign->consecutive_failures} consecutive failures encountered. Check your WhatsApp Gateway connection.",
                ]);

                Log::warning("[WhatsApp Circuit Breaker] Auto-paused Campaign {$campaign->id} after {$campaign->consecutive_failures} consecutive failures.");
            }
        }

        $this->checkCampaignCompletion($campaign);
    }

    /**
     * Check if all recipients have been processed.
     */
    protected function checkCampaignCompletion(WhatsappCampaign $campaign): void
    {
        $campaign->refresh();

        if ($campaign->status !== 'running') {
            return;
        }

        $remainingCount = WhatsappCampaignRecipient::where('whatsapp_campaign_id', $campaign->id)
            ->whereIn('status', ['pending', 'sending'])
            ->count();

        if ($remainingCount === 0) {
            $campaign->update([
                'status' => 'completed',
            ]);

            Log::info("[WhatsApp Campaign] Campaign {$campaign->id} completed successfully.");
        }
    }
}
