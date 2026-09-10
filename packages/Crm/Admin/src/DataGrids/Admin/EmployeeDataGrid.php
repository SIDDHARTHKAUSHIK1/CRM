<?php

namespace Crm\Admin\DataGrids\Admin;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Crm\DataGrid\DataGrid;

class EmployeeDataGrid extends DataGrid
{
    /**
     * Primary column.
     *
     * @var string
     */
    protected $primaryColumn = 'id';

    /**
     * Prepare query builder.
     */
    public function prepareQueryBuilder(): Builder
    {
        $currentUserId = auth()->guard('user')->id();

        $queryBuilder = DB::table('users')
            ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
            ->select(
                'users.id',
                'users.name',
                'users.email',
                'users.image',
                'users.status',
                'users.role_id',
                'users.created_at',
                'users.last_login_at',
                'roles.name as role_name',
                'roles.permission_type as role_permission_type',
                DB::raw('(SELECT COUNT(*) FROM leads WHERE leads.user_id = users.id) as leads_count'),
                DB::raw('(SELECT COUNT(*) FROM quotes WHERE quotes.user_id = users.id) as quotes_count'),
                DB::raw('(SELECT COUNT(*) FROM activities WHERE activities.user_id = users.id) as activities_count')
            );

        if ($currentUserId) {
            $queryBuilder->where('users.id', '!=', $currentUserId);
        }

        return $queryBuilder;
    }

    /**
     * Add columns.
     */
    public function prepareColumns(): void
    {
        $this->addColumn([
            'index'      => 'id',
            'label'      => trans('admin::app.admin-panel.employees.datagrid.id'),
            'type'       => 'string',
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'name',
            'label'      => trans('admin::app.admin-panel.employees.datagrid.name'),
            'type'       => 'string',
            'sortable'   => true,
            'searchable' => true,
            'filterable' => true,
            'closure'    => function ($row) {
                return [
                    'image' => $row->image ? Storage::url($row->image) : null,
                    'name'  => $row->name,
                ];
            },
        ]);

        $this->addColumn([
            'index'      => 'email',
            'label'      => trans('admin::app.admin-panel.employees.datagrid.email'),
            'type'       => 'string',
            'sortable'   => true,
            'searchable' => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'role_name',
            'label'      => trans('admin::app.admin-panel.employees.datagrid.role'),
            'type'       => 'string',
            'sortable'   => true,
            'searchable' => true,
            'filterable' => false,
            'closure'    => function ($row) {
                return [
                    'role_name'            => $row->role_name ?? '—',
                    'role_permission_type' => $row->role_permission_type ?? 'custom',
                ];
            },
        ]);

        $this->addColumn([
            'index'      => 'status',
            'label'      => trans('admin::app.admin-panel.employees.datagrid.status'),
            'type'       => 'boolean',
            'sortable'   => true,
            'filterable' => true,
            'searchable' => false,
        ]);

        $this->addColumn([
            'index'      => 'created_at',
            'label'      => trans('admin::app.admin-panel.employees.datagrid.created-at'),
            'type'       => 'date',
            'sortable'   => true,
            'searchable' => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'last_login_at',
            'label'      => trans('admin::app.admin-panel.employees.datagrid.last-login'),
            'type'       => 'date',
            'sortable'   => true,
            'searchable' => false,
            'filterable' => false,
            'closure'    => function ($row) {
                return $row->last_login_at
                    ? core()->formatDate($row->last_login_at, 'd M Y, h:i A')
                    : trans('admin::app.admin-panel.employees.datagrid.never');
            },
        ]);

        $this->addColumn([
            'index'      => 'work_summary',
            'label'      => trans('admin::app.admin-panel.employees.datagrid.work-summary'),
            'type'       => 'string',
            'sortable'   => false,
            'searchable' => false,
            'filterable' => false,
            'closure'    => function ($row) {
                return [
                    'leads'      => (int) ($row->leads_count ?? 0),
                    'quotes'     => (int) ($row->quotes_count ?? 0),
                    'activities' => (int) ($row->activities_count ?? 0),
                ];
            },
        ]);
    }

    /**
     * Prepare actions.
     */
    public function prepareActions(): void
    {
        $this->addAction([
            'index'  => 'view',
            'icon'   => 'icon-eye',
            'title'  => trans('admin::app.admin-panel.employees.datagrid.view'),
            'method' => 'GET',
            'url'    => fn ($row) => route('admin.panel.employees.view', $row->id),
        ]);

        $this->addAction([
            'index'  => 'edit',
            'icon'   => 'icon-edit',
            'title'  => trans('admin::app.admin-panel.employees.datagrid.edit'),
            'method' => 'GET',
            'url'    => fn ($row) => route('admin.panel.employees.edit', $row->id),
        ]);

        $this->addAction([
            'index'  => 'password',
            'icon'   => 'icon-role',
            'title'  => trans('admin::app.admin-panel.employees.datagrid.password'),
            'method' => 'GET',
            'url'    => fn ($row) => route('admin.panel.employees.edit', $row->id),
        ]);

        $this->addAction([
            'index'  => 'impersonate',
            'icon'   => 'icon-enter',
            'title'  => trans('admin::app.admin-panel.employees.datagrid.impersonate'),
            'method' => 'POST',
            'url'    => fn ($row) => route('admin.panel.employees.impersonate', $row->id),
        ]);

        $this->addAction([
            'index'  => 'delete',
            'icon'   => 'icon-delete',
            'title'  => trans('admin::app.admin-panel.employees.datagrid.delete'),
            'method' => 'DELETE',
            'url'    => fn ($row) => route('admin.panel.employees.delete', $row->id),
        ]);
    }
}
