<?php

return [
    'leads' => [
        'name' => 'admin::app.leads.index.title',
        'repository' => 'Crm\Lead\Repositories\LeadRepository',
    ],

    'persons' => [
        'name' => 'admin::app.contacts.persons.index.title',
        'repository' => 'Crm\Contact\Repositories\PersonRepository',
    ],

    'organizations' => [
        'name' => 'admin::app.contacts.organizations.index.title',
        'repository' => 'Crm\Contact\Repositories\OrganizationRepository',
    ],

    'products' => [
        'name' => 'admin::app.products.index.title',
        'repository' => 'Crm\Product\Repositories\ProductRepository',
    ],

    'quotes' => [
        'name' => 'admin::app.quotes.index.title',
        'repository' => 'Crm\Quote\Repositories\QuoteRepository',
    ],

    'warehouses' => [
        'name' => 'admin::app.settings.warehouses.index.title',
        'repository' => 'Crm\Warehouse\Repositories\WarehouseRepository',
    ],
];
