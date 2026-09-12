<?php

namespace Crm\Admin\DataGrids\Quote;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Crm\Contact\Repositories\PersonRepository;
use Crm\DataGrid\DataGrid;
use Crm\User\Repositories\UserRepository;

class QuoteDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     */
    public function prepareQueryBuilder(): Builder
    {
        $tablePrefix = DB::getTablePrefix();

        $queryBuilder = DB::table('quotes')
            ->addSelect(
                'quotes.id',
                'quotes.subject',
                'quotes.expired_at',
                'quotes.sub_total',
                'quotes.discount_amount',
                'quotes.tax_amount',
                'quotes.adjustment_amount',
                'quotes.grand_total',
                'quotes.created_at',
                'users.id as user_id',
                'users.name as sales_person',
                'persons.id as person_id',
                'persons.name as person_name',
                'quotes.expired_at as expired_quotes'
            )
            ->leftJoin('users', 'quotes.user_id', '=', 'users.id')
            ->leftJoin('persons', 'quotes.person_id', '=', 'persons.id');

        if ($userIds = bouncer()->getAuthorizedUserIds()) {
            $queryBuilder->whereIn('quotes.user_id', $userIds);
        }

        $this->addFilter('id', 'quotes.id');
        $this->addFilter('user', 'quotes.user_id');
        $this->addFilter('sales_person', 'users.name');
        $this->addFilter('person_name', 'persons.name');
        $this->addFilter('expired_at', 'quotes.expired_at');
        $this->addFilter('created_at', 'quotes.created_at');

        if (request()->input('expired_quotes.in') == 1) {
            $this->addFilter('expired_quotes', DB::raw('DATEDIFF(NOW(), '.$tablePrefix.'quotes.expired_at) >= '.$tablePrefix.'NOW()'));
        } else {
            $this->addFilter('expired_quotes', DB::raw('DATEDIFF(NOW(), '.$tablePrefix.'quotes.expired_at) < '.$tablePrefix.'NOW()'));
        }

        $this->scopeToCurrentTenant($queryBuilder, 'quotes');

        return $queryBuilder;
    }

    /**
     * Prepare columns.
     */
    public function prepareColumns(): void
    {
        $this->addColumn([
            'index' => 'subject',
            'label' => trans('admin::app.quotes.index.datagrid.subject'),
            'type' => 'string',
            'filterable' => true,
            'searchable' => true,
            'sortable' => true,
            'closure' => function ($row) {
                $editUrl = route('admin.quotes.edit', $row->id);
                return '<div class="flex flex-col pr-1">'
                    . '<a href="' . $editUrl . '" class="font-semibold text-sm text-gray-600hover:text-purple-600 transition dark:text-white dark:hover:text-purple-400 leading-snug line-clamp-2" title="' . e($row->subject) . '">'
                    . e($row->subject)
                    . '</a>'
                    . '<span class="text-xs text-slate-400 dark:text-slate-500 mt-1 whitespace-nowrap">'
                    . 'Quote #' . $row->id . ' · Created: ' . core()->formatDate($row->created_at, 'd M Y')
                    . '</span>'
                    . '</div>';
            },
        ]);

        $this->addColumn([
            'index' => 'sales_person',
            'label' => trans('admin::app.quotes.index.datagrid.sales-person'),
            'type' => 'string',
            'sortable' => true,
            'searchable' => true,
            'filterable' => true,
            'filterable_type' => 'searchable_dropdown',
            'filterable_options' => [
                'repository' => UserRepository::class,
                'column' => [
                    'label' => 'name',
                    'value' => 'name',
                ],
            ],
            'closure' => function ($row) {
                $hasUser = ! empty($row->sales_person);
                $dotColor = $hasUser ? 'bg-emerald-500' : 'bg-slate-400';
                return '<span class="inline-flex items-center gap-1.5 text-xs font-medium text-slate-700 dark:text-slate-300 whitespace-nowrap">'
                    . '<span class="w-1.5 h-1.5 rounded-full ' . $dotColor . ' shrink-0"></span>'
                    . e($row->sales_person ?? 'Unassigned')
                    . '</span>';
            },
        ]);

        $this->addColumn([
            'index' => 'person_name',
            'label' => trans('admin::app.quotes.index.datagrid.person'),
            'type' => 'string',
            'sortable' => true,
            'searchable' => true,
            'filterable' => true,
            'filterable_type' => 'searchable_dropdown',
            'filterable_options' => [
                'repository' => PersonRepository::class,
                'column' => [
                    'label' => 'name',
                    'value' => 'name',
                ],
            ],
            'closure' => function ($row) {
                if (! $row->person_name) {
                    return '<span class="text-slate-400 text-xs">-</span>';
                }
                $initials = strtoupper(substr($row->person_name, 0, 2));
                $route = route('admin.contacts.persons.view', $row->person_id);

                return '<div class="flex items-center gap-2 min-w-0">'
                    . '<div class="w-6 h-6 rounded-full bg-purple-100 text-purple-700 dark:bg-purple-950/70 dark:text-purple-300 font-bold text-[10px] flex items-center justify-center shrink-0">' . $initials . '</div>'
                    . '<a class="text-xs font-semibold text-slate-800 hover:text-purple-600 transition hover:underline dark:text-slate-200 truncate" href="' . $route . '">' . e($row->person_name) . '</a>'
                    . '</div>';
            },
        ]);

        $this->addColumn([
            'index' => 'sub_total',
            'label' => trans('admin::app.quotes.index.datagrid.subtotal'),
            'type' => 'string',
            'sortable' => true,
            'filterable' => true,
            'closure' => function ($row) {
                $subtotal = core()->formatBasePrice($row->sub_total, 2);
                $discount = $row->discount_amount > 0 ? '<div class="text-[11px] font-medium text-emerald-600 dark:text-emerald-400 whitespace-nowrap">Disc: -'.core()->formatBasePrice($row->discount_amount, 2).'</div>' : '';
                return '<div class="flex flex-col items-start">'
                    . '<span class="text-xs font-semibold text-slate-800 dark:text-slate-200 whitespace-nowrap tracking-tight tabular-nums">' . $subtotal . '</span>'
                    . $discount
                    . '</div>';
            },
        ]);

        $this->addColumn([
            'index' => 'tax_amount',
            'label' => trans('admin::app.quotes.index.datagrid.tax'),
            'type' => 'string',
            'filterable' => true,
            'sortable' => true,
            'closure' => function ($row) {
                return '<div class="flex flex-col items-start">'
                    . '<span class="text-xs font-semibold text-slate-700 dark:text-slate-300 whitespace-nowrap tracking-tight tabular-nums">' . core()->formatBasePrice($row->tax_amount, 2) . '</span>'
                    . '<span class="text-[11px] text-slate-400 font-medium whitespace-nowrap">GST / Tax</span>'
                    . '</div>';
            },
        ]);

        $this->addColumn([
            'index' => 'grand_total',
            'label' => trans('admin::app.quotes.index.datagrid.grand-total'),
            'type' => 'string',
            'sortable' => true,
            'filterable' => true,
            'closure' => function ($row) {
                $total = core()->formatBasePrice($row->grand_total, 2);
                return '<div class="flex items-center">'
                    . '<span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold text-gray-600bg-slate-100 border border-slate-200/80 dark:bg-gray-800 dark:border-gray-700 dark:text-white whitespace-nowrap tracking-tight tabular-nums shadow-2xs">'
                    . $total
                    . '</span>'
                    . '</div>';
            },
        ]);

        $this->addColumn([
            'index' => 'expired_at',
            'label' => trans('admin::app.quotes.index.datagrid.expired-at'),
            'type' => 'date',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true,
            'closure' => function ($row) {
                $isExpired = $row->expired_at && strtotime($row->expired_at) < time();
                if ($isExpired) {
                    return '<div class="flex flex-col items-start gap-1">'
                        . '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200 dark:bg-rose-950/60 dark:text-rose-300 dark:border-rose-900 whitespace-nowrap">Expired</span>'
                        . '<span class="text-[11px] text-rose-600 dark:text-rose-400 font-medium whitespace-nowrap">' . core()->formatDate($row->expired_at, 'd M Y') . '</span>'
                        . '</div>';
                }
                return '<div class="flex flex-col items-start gap-1">'
                    . '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-950/60 dark:text-emerald-300 dark:border-emerald-900 whitespace-nowrap">Valid / Active</span>'
                    . '<span class="text-[11px] text-slate-500 dark:text-slate-400 font-medium whitespace-nowrap">Expires ' . core()->formatDate($row->expired_at, 'd M Y') . '</span>'
                    . '</div>';
            },
        ]);

        $this->addColumn([
            'index' => 'created_at',
            'label' => trans('admin::app.quotes.index.datagrid.created-at'),
            'type' => 'date',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true,
            'closure' => function ($row) {
                return '<div class="flex flex-col items-start">'
                    . '<span class="text-xs font-medium text-slate-700 dark:text-slate-300 whitespace-nowrap">' . core()->formatDate($row->created_at, 'd M Y') . '</span>'
                    . '<span class="text-[11px] text-slate-400 whitespace-nowrap">' . core()->formatDate($row->created_at, 'h:i A') . '</span>'
                    . '</div>';
            },
        ]);
    }

    /**
     * Prepare actions.
     */
    public function prepareActions(): void
    {
        if (bouncer()->hasPermission('quotes.edit')) {
            $this->addAction([
                'index' => 'edit',
                'icon' => 'icon-edit',
                'title' => trans('admin::app.quotes.index.datagrid.edit'),
                'method' => 'GET',
                'url' => fn ($row) => route('admin.quotes.edit', $row->id),
            ]);
        }

        if (bouncer()->hasPermission('quotes.print')) {
            $this->addAction([
                'index' => 'print',
                'icon' => 'icon-print',
                'title' => trans('admin::app.quotes.index.datagrid.print'),
                'method' => 'GET',
                'url' => fn ($row) => route('admin.quotes.print', $row->id),
            ]);
        }

        if (bouncer()->hasPermission('quotes.mail')) {
            $this->addAction([
                'index' => 'mail',
                'icon' => 'icon-mail',
                'title' => trans('admin::app.quotes.index.datagrid.mail'),
                'method' => 'POST',
                'url' => fn ($row) => route('admin.leads.quotes.mail', ['quote_id' => $row->id]),
            ]);
        }

        if (bouncer()->hasPermission('quotes.delete')) {
            $this->addAction([
                'index' => 'delete',
                'icon' => 'icon-delete',
                'title' => trans('admin::app.quotes.index.datagrid.delete'),
                'method' => 'DELETE',
                'url' => fn ($row) => route('admin.quotes.delete', $row->id),
            ]);
        }
    }

    /**
     * Prepare mass actions.
     */
    public function prepareMassActions(): void
    {
        $this->addMassAction([
            'icon' => 'icon-delete',
            'title' => trans('admin::app.quotes.index.datagrid.delete'),
            'method' => 'POST',
            'url' => route('admin.quotes.mass_delete'),
        ]);

        $this->addMassAction([
            'icon' => 'icon-delete',
            'title' => trans('admin::app.quotes.index.datagrid.delete'),
            'method' => 'POST',
            'url' => route('admin.quotes.mass_delete'),
        ]);
    }
}
