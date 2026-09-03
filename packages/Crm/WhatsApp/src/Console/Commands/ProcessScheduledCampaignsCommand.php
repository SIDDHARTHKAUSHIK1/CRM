<?php

namespace Crm\WhatsApp\Console\Commands;

use Illuminate\Console\Command;
use Crm\WhatsApp\Models\WhatsappCampaign;
use Crm\WhatsApp\Jobs\SendWhatsappCampaignMessageJob;

class ProcessScheduledCampaignsCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'whatsapp:process-campaigns';

    /**
     * The console command description.
     */
    protected $description = 'Process and dispatch pending WhatsApp campaign messages for active running campaigns';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $runningCampaigns = WhatsappCampaign::where('status', 'running')->get();

        if ($runningCampaigns->isEmpty()) {
            $this->info('No running WhatsApp campaigns found.');
            return 0;
        }

        foreach ($runningCampaigns as $campaign) {
            $this->info("Processing Campaign #{$campaign->id}: {$campaign->name}");

            $limit = $campaign->daily_limit ?: 500;
            $pendingRecipients = $campaign->recipients()
                ->where('status', 'pending')
                ->limit($limit)
                ->get();

            $delaySeconds = 0;
            foreach ($pendingRecipients as $recipient) {
                SendWhatsappCampaignMessageJob::dispatch($campaign->id, $recipient->id)
                    ->delay(now()->addSeconds($delaySeconds));

                $delaySeconds += max(5, (int) $campaign->throttle_seconds);
            }

            $this->info("Dispatched {$pendingRecipients->count()} messages for Campaign #{$campaign->id}.");
        }

        return 0;
    }
}
