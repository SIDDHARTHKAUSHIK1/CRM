<?php

namespace Crm\Admin\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'contacts.person.create.after' => [
            'Crm\Admin\Listeners\Person@linkToEmail',
        ],

        'lead.create.after' => [
            'Crm\Admin\Listeners\Lead@linkToEmail',
        ],

        'activity.create.after' => [
            'Crm\Admin\Listeners\Activity@afterUpdateOrCreate',
        ],

        'activity.update.after' => [
            'Crm\Admin\Listeners\Activity@afterUpdateOrCreate',
        ],
    ];
}
