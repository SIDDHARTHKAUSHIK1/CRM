<?php

namespace Crm\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Crm\Core\Contracts\Country as CountryContract;

class Country extends Model implements CountryContract
{
    public $timestamps = false;
}
