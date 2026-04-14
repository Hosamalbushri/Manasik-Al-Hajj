<?php

namespace Webkul\Admin\DataGrids\HajjPilgrims;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Webkul\DataGrid\DataGrid;

class HajjUserDataGrid extends DataGrid
{
    public function prepareQueryBuilder(): Builder
    {
        if (! Schema::hasTable('manasik_hajj_users')) {
            return DB::table('manasik_hajj_users')->whereRaw('0 = 1');
        }

        $queryBuilder = DB::table('manasik_hajj_users')
            ->select('manasik_hajj_users.*')
            ->orderByDesc('manasik_hajj_users.created_at')
            ->orderByDesc('manasik_hajj_users.id');

        $this->addFilter('id', 'manasik_hajj_users.id');

        return $queryBuilder;
    }

    public function prepareColumns(): void
    {
        $this->addColumn([
            'index'      => 'id',
            'label'      => trans('admin::app.settings.hajj-pilgrims.datagrid.id'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'name',
            'label'      => trans('admin::app.settings.hajj-pilgrims.datagrid.name'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'email',
            'label'      => trans('admin::app.settings.hajj-pilgrims.datagrid.email'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'phone',
            'label'      => trans('admin::app.settings.hajj-pilgrims.datagrid.phone'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'locale',
            'label'      => trans('admin::app.settings.hajj-pilgrims.datagrid.locale'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'manasik_guide_completions_count',
            'label'      => trans('admin::app.settings.hajj-pilgrims.datagrid.completions'),
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => false,
            'closure'    => function ($row) {
                $n = isset($row->manasik_guide_completions_count) ? (int) $row->manasik_guide_completions_count : 0;

                return (string) $n;
            },
        ]);

        $this->addColumn([
            'index'      => 'status',
            'label'      => trans('admin::app.settings.hajj-pilgrims.datagrid.status'),
            'type'       => 'boolean',
            'searchable' => true,
            'filterable' => true,
            'filterable_options' => [
                ['label' => trans('admin::app.settings.hajj-pilgrims.datagrid.active'), 'value' => 1],
                ['label' => trans('admin::app.settings.hajj-pilgrims.datagrid.inactive'), 'value' => 0],
            ],
            'sortable' => true,
            'closure'  => function ($row) {
                if ($row->status) {
                    return '<p class="label-active">'.e(trans('admin::app.settings.hajj-pilgrims.datagrid.active')).'</p>';
                }

                return '<p class="label-pending">'.e(trans('admin::app.settings.hajj-pilgrims.datagrid.inactive')).'</p>';
            },
        ]);

        $this->addColumn([
            'index'      => 'email_verified_at',
            'label'      => trans('admin::app.settings.hajj-pilgrims.datagrid.email-verified'),
            'type'       => 'datetime',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
            'closure'    => fn ($row) => $row->email_verified_at ? core()->formatDate($row->email_verified_at) : '—',
        ]);

        $this->addColumn([
            'index'      => 'created_at',
            'label'      => trans('admin::app.settings.hajj-pilgrims.datagrid.created-at'),
            'type'       => 'datetime',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
            'closure'    => fn ($row) => core()->formatDate($row->created_at),
        ]);
    }

    public function prepareActions(): void
    {
        if (bouncer()->hasPermission('hajj_pilgrims')) {
            $this->addAction([
                'index'  => 'view',
                'icon'   => 'icon-eye',
                'title'  => trans('admin::app.settings.hajj-pilgrims.datagrid.view'),
                'method' => 'GET',
                'url'    => fn ($row) => route('admin.manasik-hajj-users.show', $row->id),
            ]);
        }
    }
}
