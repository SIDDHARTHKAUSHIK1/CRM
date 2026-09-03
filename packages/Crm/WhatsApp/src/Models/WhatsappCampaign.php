<?php

namespace Crm\WhatsApp\Models;

use Illuminate\Database\Eloquent\Model;
use Crm\User\Models\UserProxy;
use Crm\WhatsApp\Contracts\WhatsappCampaign as WhatsappCampaignContract;

class WhatsappCampaign extends Model implements WhatsappCampaignContract
{
    protected $table = 'whatsapp_campaigns';

    protected $fillable = [
        'name',
        'brochure_path',
        'brochure_name',
        'caption',
        'status',
        'throttle_seconds',
        'daily_limit',
        'consecutive_failure_limit',
        'consecutive_failures',
        'pause_reason',
        'total_recipients',
        'sent_count',
        'failed_count',
        'created_by',
    ];

    protected $casts = [
        'throttle_seconds' => 'integer',
        'daily_limit' => 'integer',
        'consecutive_failure_limit' => 'integer',
        'consecutive_failures' => 'integer',
        'total_recipients' => 'integer',
        'sent_count' => 'integer',
        'failed_count' => 'integer',
    ];

    protected $appends = [
        'progress_percent',
        'pending_count',
        'brochure_url',
        'media_type',
    ];

    /**
     * Get the campaign recipients.
     */
    public function recipients()
    {
        return $this->hasMany(WhatsappCampaignRecipientProxy::modelClass(), 'whatsapp_campaign_id');
    }

    /**
     * Creator user.
     */
    public function user()
    {
        return $this->belongsTo(UserProxy::modelClass(), 'created_by');
    }

    /**
     * Calculate progress percentage.
     */
    public function getProgressPercentAttribute(): int
    {
        if ($this->total_recipients <= 0) {
            return 0;
        }

        $processed = $this->sent_count + $this->failed_count;
        return min(100, (int) round(($processed / $this->total_recipients) * 100));
    }

    /**
     * Pending count.
     */
    public function getPendingCountAttribute(): int
    {
        return max(0, $this->total_recipients - ($this->sent_count + $this->failed_count));
    }

    /**
     * Public Brochure URL.
     */
    public function getBrochureUrlAttribute(): string
    {
        if (! $this->brochure_path) {
            return '';
        }

        return '/storage/' . ltrim($this->brochure_path, '/');
    }

    /**
     * Inferred Media Type (image, video, document).
     */
    public function getMediaTypeAttribute(): string
    {
        if (!$this->brochure_path) return 'document';
        $ext = strtolower(pathinfo($this->brochure_path, PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) return 'image';
        if (in_array($ext, ['mp4', 'webm', 'ogg', 'mov'])) return 'video';
        return 'document';
    }
}
