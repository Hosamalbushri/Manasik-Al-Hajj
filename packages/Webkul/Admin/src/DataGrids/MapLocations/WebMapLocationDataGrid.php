<?php

namespace Webkul\Admin\DataGrids\MapLocations;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;
use Webkul\Manasik\Repositories\MapLocationRepository;

class WebMapLocationDataGrid extends DataGrid
{
    public function prepareQueryBuilder(): Builder
    {
        $queryBuilder = DB::table('manasik_map_locations')
            ->addSelect(
                'manasik_map_locations.id',
                'manasik_map_locations.slug',
                'manasik_map_locations.map_id',
                'manasik_map_locations.sort_order',
                'manasik_map_locations.status',
                'manasik_map_locations.updated_at',
                'manasik_map_locations.content',
            )
            ->orderBy('manasik_map_locations.sort_order')
            ->orderBy('manasik_map_locations.id');

        $this->addFilter('id', 'manasik_map_locations.id');

        return $queryBuilder;
    }

    public function prepareColumns(): void
    {
        $this->addColumn([
            'index' => 'id',
            'label' => trans('admin::app.settings.map-locations.datagrid.id'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index' => 'location_name',
            'label' => trans('admin::app.settings.map-locations.datagrid.location-name'),
            'type' => 'string',
            'searchable' => false,
            'sortable' => false,
            'filterable' => false,
            'closure' => function ($row) {
                $raw = $row->content ?? null;
                $content = is_string($raw) ? json_decode($raw, true) : (is_array($raw) ? $raw : []);
                if (! is_array($content)) {
                    $content = [];
                }

                $title = app(MapLocationRepository::class)
                    ->resolveContentForLocale($content, strtolower(app()->getLocale()))['title'];

                if ($title !== '') {
                    return e($title);
                }

                return e((string) $row->slug);
            },
        ]);

        $this->addColumn([
            'index' => 'map_id',
            'label' => trans('admin::app.settings.map-locations.datagrid.map-id'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index' => 'sort_order',
            'label' => trans('admin::app.settings.map-locations.datagrid.sort-order'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index' => 'status',
            'label' => trans('admin::app.settings.map-locations.datagrid.status'),
            'type' => 'boolean',
            'searchable' => true,
            'filterable' => true,
            'filterable_options' => [
                ['label' => trans('admin::app.settings.map-locations.datagrid.active'), 'value' => 1],
                ['label' => trans('admin::app.settings.map-locations.datagrid.inactive'), 'value' => 0],
            ],
            'sortable' => true,
            'closure' => function ($row) {
                if ($row->status) {
                    return '<p class="label-active">'.trans('admin::app.settings.map-locations.datagrid.active').'</p>';
                }

                return '<p class="label-pending">'.trans('admin::app.settings.map-locations.datagrid.inactive').'</p>';
            },
        ]);

        $this->addColumn([
            'index' => 'updated_at',
            'label' => trans('admin::app.settings.map-locations.datagrid.updated-at'),
            'type' => 'datetime',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true,
            'closure' => fn ($row) => core()->formatDate($row->updated_at),
        ]);
    }

    public function prepareActions(): void
    {
        if (bouncer()->hasPermission('map_locations.edit')) {
            $this->addAction([
                'index' => 'edit',
                'icon' => 'icon-edit',
                'title' => trans('admin::app.settings.map-locations.datagrid.edit'),
                'method' => 'GET',
                'url' => fn ($row) => route('admin.map-locations.edit', $row->id),
            ]);
        }

        if (bouncer()->hasPermission('map_locations.delete')) {
            $this->addAction([
                'index' => 'delete',
                'icon' => 'icon-delete',
                'title' => trans('admin::app.settings.map-locations.datagrid.delete'),
                'method' => 'DELETE',
                'url' => fn ($row) => route('admin.map-locations.destroy', $row->id),
            ]);
        }
    }
}
