<?php

namespace Webkul\Admin\DataGrids\HajjRites;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;
use Webkul\Manasik\Repositories\HajjRiteRepository;

class HajjRiteDataGrid extends DataGrid
{
    public function prepareQueryBuilder(): Builder
    {
        $queryBuilder = DB::table('manasik_hajj_rites')
            ->select('manasik_hajj_rites.*')
            ->selectRaw('(SELECT COUNT(*) FROM manasik_hajj_rite_dua WHERE manasik_hajj_rite_dua.manasik_hajj_rite_id = manasik_hajj_rites.id) as dua_count')
            ->orderBy('manasik_hajj_rites.sort_order')
            ->orderBy('manasik_hajj_rites.id');

        $this->addFilter('id', 'manasik_hajj_rites.id');

        return $queryBuilder;
    }

    public function prepareColumns(): void
    {
        $repo = app(HajjRiteRepository::class);
        $loc = strtolower(app()->getLocale());

        $this->addColumn([
            'index' => 'id',
            'label' => trans('admin::app.settings.hajj-rites.datagrid.id'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index' => 'slug',
            'label' => trans('admin::app.settings.hajj-rites.datagrid.slug'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index' => 'rite_title',
            'label' => trans('admin::app.settings.hajj-rites.datagrid.title'),
            'type' => 'string',
            'searchable' => false,
            'sortable' => false,
            'filterable' => false,
            'closure' => function ($row) use ($repo, $loc) {
                $raw = $row->content ?? null;
                $content = is_string($raw) ? json_decode($raw, true) : (is_array($raw) ? $raw : []);
                if (! is_array($content)) {
                    $content = [];
                }
                $slice = $repo->resolveRiteContent($content, $loc);
                $t = $slice['title'];
                if ($t === '' && $slice['tab_label'] !== '') {
                    $t = $slice['tab_label'];
                }

                return e($t !== '' ? $t : (string) $row->slug);
            },
        ]);

        $this->addColumn([
            'index' => 'dua_count',
            'label' => trans('admin::app.settings.hajj-rites.datagrid.dua-count'),
            'type' => 'string',
            'searchable' => false,
            'sortable' => true,
            'filterable' => false,
        ]);

        $this->addColumn([
            'index' => 'sort_order',
            'label' => trans('admin::app.settings.hajj-rites.datagrid.sort-order'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index' => 'status',
            'label' => trans('admin::app.settings.hajj-rites.datagrid.status'),
            'type' => 'boolean',
            'searchable' => true,
            'filterable' => true,
            'filterable_options' => [
                ['label' => trans('admin::app.settings.hajj-rites.datagrid.active'), 'value' => 1],
                ['label' => trans('admin::app.settings.hajj-rites.datagrid.inactive'), 'value' => 0],
            ],
            'sortable' => true,
            'closure' => function ($row) {
                if ($row->status) {
                    return '<p class="label-active">'.e(trans('admin::app.settings.hajj-rites.datagrid.active')).'</p>';
                }

                return '<p class="label-pending">'.e(trans('admin::app.settings.hajj-rites.datagrid.inactive')).'</p>';
            },
        ]);

        $this->addColumn([
            'index' => 'updated_at',
            'label' => trans('admin::app.settings.hajj-rites.datagrid.updated-at'),
            'type' => 'datetime',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true,
            'closure' => fn ($row) => core()->formatDate($row->updated_at),
        ]);
    }

    public function prepareActions(): void
    {
        if (bouncer()->hasPermission('hajj_rites.edit')) {
            $this->addAction([
                'index' => 'edit',
                'icon' => 'icon-edit',
                'title' => trans('admin::app.settings.hajj-rites.datagrid.edit'),
                'method' => 'GET',
                'url' => fn ($row) => route('admin.manasik-hajj-rites.edit', $row->id),
            ]);
        }

        if (bouncer()->hasPermission('hajj_rites.delete')) {
            $this->addAction([
                'index' => 'delete',
                'icon' => 'icon-delete',
                'title' => trans('admin::app.settings.hajj-rites.datagrid.delete'),
                'method' => 'DELETE',
                'url' => fn ($row) => route('admin.manasik-hajj-rites.destroy', $row->id),
            ]);
        }
    }
}
