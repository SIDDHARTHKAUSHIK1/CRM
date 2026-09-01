<?php

namespace Crm\Warehouse\Models;

use Illuminate\Database\Eloquent\Model;
use Crm\Warehouse\Contracts\Location as LocationContract;

class Location extends Model implements LocationContract
{
    /**
     * The table associated with the model.
     */
    protected $table = 'warehouse_locations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'warehouse_id',
    ];

    /**
     * Get the warehouse that owns the location.
     */
    public function warehouse()
    {
        return $this->belongsTo(WarehouseProxy::modelClass());
    }
}
