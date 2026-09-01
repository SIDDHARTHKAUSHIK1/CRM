<?php

namespace Crm\Quote\Models;

use Illuminate\Database\Eloquent\Model;
use Crm\Attribute\Traits\CustomAttribute;
use Crm\Contact\Models\PersonProxy;
use Crm\Lead\Models\LeadProxy;
use Crm\Quote\Contracts\Quote as QuoteContract;
use Crm\User\Models\UserProxy;

class Quote extends Model implements QuoteContract
{
    use CustomAttribute;

    protected $table = 'quotes';

    protected $casts = [
        'billing_address' => 'array',
        'shipping_address' => 'array',
        'expired_at' => 'datetime',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'subject',
        'description',
        'billing_address',
        'shipping_address',
        'discount_percent',
        'discount_amount',
        'tax_amount',
        'adjustment_amount',
        'sub_total',
        'grand_total',
        'expired_at',
        'user_id',
        'person_id',
    ];

    /**
     * Get the quote items record associated with the quote.
     */
    public function items()
    {
        return $this->hasMany(QuoteItemProxy::modelClass());
    }

    /**
     * Get the user that owns the quote.
     */
    public function user()
    {
        return $this->belongsTo(UserProxy::modelClass());
    }

    /**
     * Get the person that owns the quote.
     */
    public function person()
    {
        return $this->belongsTo(PersonProxy::modelClass());
    }

    /**
     * The leads that belong to the quote.
     */
    public function leads()
    {
        return $this->belongsToMany(LeadProxy::modelClass(), 'lead_quotes');
    }
}
