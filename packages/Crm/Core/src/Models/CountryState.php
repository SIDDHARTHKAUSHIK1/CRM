<?php

namespace Crm\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Crm\Core\Contracts\CountryState as CountryStateContract;

class CountryState extends Model implements CountryStateContract
{
    public $timestamps = false;
}
