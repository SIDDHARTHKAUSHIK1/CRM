<?php

return [
    'leads' => [
        'name' => 'Leads',
        'repository' => 'Crm\Lead\Repositories\LeadRepository',
        'label_column' => 'title',
    ],

    'lead_sources' => [
        'name' => 'Lead Sources',
        'repository' => 'Crm\Lead\Repositories\SourceRepository',
    ],

    'lead_types' => [
        'name' => 'Lead Types',
        'repository' => 'Crm\Lead\Repositories\TypeRepository',
    ],

    'lead_pipelines' => [
        'name' => 'Lead Pipelines',
        'repository' => 'Crm\Lead\Repositories\PipelineRepository',
    ],

    'lead_pipeline_stages' => [
        'name' => 'Lead Pipeline Stages',
        'repository' => 'Crm\Lead\Repositories\StageRepository',
    ],

    'users' => [
        'name' => 'Sales Owners',
        'repository' => 'Crm\User\Repositories\UserRepository',
    ],

    'organizations' => [
        'name' => 'Organizations',
        'repository' => 'Crm\Contact\Repositories\OrganizationRepository',
    ],

    'persons' => [
        'name' => 'Persons',
        'repository' => 'Crm\Contact\Repositories\PersonRepository',
    ],

    'warehouses' => [
        'name' => 'Warehouses',
        'repository' => 'Crm\Warehouse\Repositories\WarehouseRepository',
    ],

    'locations' => [
        'name' => 'Locations',
        'repository' => 'Crm\Warehouse\Repositories\LocationRepository',
    ],
];
