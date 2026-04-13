<?php

namespace Webkul\Admin\DataGrids\AdhkarDuas;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;
use Webkul\Manasik\Repositories\DuaRepository;

class WebDuaSectionDataGrid extends DataGrid
{
    public function prepareQueryBuilder(): Builder
    {
        $queryBuilder = DB::table('manasik_dua_sections')
            ->addSelect(
                'manasik_dua_sections.id',
                'manasik_dua_sections.slug',
                'manasik_dua_sections.sort_order',
                'manasik_dua_sections.status',
                'manasik_dua_sections.updated_at',
                'manasik_dua_sections.content',
            )
            ->orderBy('manasik_dua_sections.sort_order')
            ->orderBy('manasik_dua_sections.id');

        $this->addFilter('id', 'manasik_dua_sections.id');

        return $queryBuilder;
    }

    public function prepareColumns(): void
    {
        $this->addColumn([
            'index' => 'id',
            'label' => trans('admin::app.settings.dua-sections.datagrid.id'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index' => 'section_title',
            'label' => trans('admin::app.settings.dua-sections.datagrid.title'),
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

                $title = app(DuaRepository::class)
                    ->resolveSectionTitle($content, strtolower(app()->getLocale()));

                return e($title !== '' ? $title : (string) $row->slug);
            },
        ]);

        $this->addColumn([
            'index' => 'slug',
            'label' => trans('admin::app.settings.dua-sections.datagrid.slug'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index' => 'sort_order',
            'label' => trans('admin::app.settings.dua-sections.datagrid.sort-order'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index' => 'status',
            'label' => trans('admin::app.settings.dua-sections.datagrid.status'),
            'type' => 'boolean',
            'searchable' => true,
            'filterable' => true,
            'filterable_options' => [
                ['label' => trans('admin::app.settings.dua-sections.datagrid.active'), 'value' => 1],
                ['label' => trans('admin::app.settings.dua-sections.datagrid.inactive'), 'value' => 0],
            ],
            'sortable' => true,
            'closure' => function ($row) {
                if ($row->status) {
                    return '<p class="label-active">'.e(trans('admin::app.settings.dua-sections.datagrid.active')).'</p>';
                }

                return '<p class="label-pending">'.e(trans('admin::app.settings.dua-sections.datagrid.inactive')).'</p>';
            },
        ]);

        $this->addColumn([
            'index' => 'updated_at',
            'label' => trans('admin::app.settings.dua-sections.datagrid.updated-at'),
            'type' => 'datetime',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true,
            'closure' => fn ($row) => core()->formatDate($row->updated_at),
        ]);
    }

    public function prepareActions(): void
    {
        if (bouncer()->hasPermission('adhkar_duas.dua_sections.edit')) {
            $this->addAction([
                'index' => 'edit',
                'icon' => 'icon-edit',
                'title' => trans('admin::app.settings.dua-sections.datagrid.edit'),
                'method' => 'GET',
                'url' => fn ($row) => route('admin.adhkar-duas.dua-sections.edit', $row->id),
            ]);
        }

        if (bouncer()->hasPermission('adhkar_duas.dua_sections.delete')) {
            $this->addAction([
                'index' => 'delete',
                'icon' => 'icon-delete',
                'title' => trans('admin::app.settings.dua-sections.datagrid.delete'),
                'method' => 'DELETE',
                'url' => fn ($row) => route('admin.adhkar-duas.dua-sections.destroy', $row->id),
            ]);
        }
    }
}
