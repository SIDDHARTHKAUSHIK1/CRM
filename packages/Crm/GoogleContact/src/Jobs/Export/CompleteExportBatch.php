<?php

namespace Crm\GoogleContact\Jobs\Export;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Crm\GoogleContact\Models\ContactExportBatch;
use Crm\GoogleContact\Repositories\ContactExportBatchRepository;

class CompleteExportBatch implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected int $exportBatchId,
    ) {}

    public function handle(ContactExportBatchRepository $batchRepository): void
    {
        $batch = ContactExportBatch::withoutGlobalScope(\Crm\Core\Scopes\TenantScope::class)->find($this->exportBatchId);

        if (! $batch) {
            return;
        }

        if ($batch->tenant_id) {
            \Crm\Core\TenantContext::setTenantId($batch->tenant_id);
        }

        try {
            if ($batch->isFinished()) {
                return;
            }

            $batchRepository->update([
                'state' => $batch->failed_count > 0
                    ? ContactExportBatch::STATE_COMPLETED_WITH_ERRORS
                    : ContactExportBatch::STATE_COMPLETED,
                'completed_at' => now(),
            ], $batch->id);
        } finally {
            \Crm\Core\TenantContext::reset();
        }
    }
}
