<?php

namespace Webkul\Admin\DataGrids\AdhkarDuas;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;
use Webkul\Manasik\Repositories\DuaRepository;

class WebDuaDataGrid extends DataGrid
{
    public function prepareQueryBuilder(): Builder
    {
        $queryBuilder = DB::table('manasik_duas')
            ->join('manasik_dua_sections', 'manasik_duas.manasik_dua_section_id', '=', 'manasik_dua_sections.id')
            ->addSelect(
                'manasik_duas.id',
                'manasik_duas.manasik_dua_section_id',
                'manasik_duas.sort_order',
                'manasik_duas.status',
                'manasik_duas.updated_at',
                'manasik_duas.content',
                'manasik_dua_sections.content as section_content',
                'manasik_dua_sections.slug as section_slug',
            )
            ->orderBy('manasik_duas.sort_order')
            ->orderBy('manasik_duas.id');

        $this->addFilter('id', 'manasik_duas.id');
        $this->addFilter('manasik_dua_section_id', 'manasik_duas.manasik_dua_section_id');

        return $queryBuilder;
    }

    public function prepareColumns(): void
    {
        $repo = app(DuaRepository::class);
        $loc = strtolower(app()->getLocale());

        $this->addColumn([
            'index' => 'id',
            'label' => trans('admin::app.settings.duas.datagrid.id'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index' => 'section_name',
            'label' => trans('admin::app.settings.duas.datagrid.section'),
            'type' => 'string',
            'searchable' => false,
            'sortable' => false,
            'filterable' => false,
            'closure' => function ($row) use ($repo, $loc) {
                $raw = $row->section_content ?? null;
                $content = is_string($raw) ? json_decode($raw, true) : (is_array($raw) ? $raw : []);
                if (! is_array($content)) {
                    $content = [];
                }
                $title = $repo->resolveSectionTitle($content, $loc);

                return e($title !== '' ? $title : (string) $row->section_slug);
            },
        ]);

        $this->addColumn([
            'index' => 'dua_title',
            'label' => trans('admin::app.settings.duas.datagrid.title'),
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
                $d = $repo->resolveDuaFields($content, $loc);
                $t = $d['title'];
                if ($t === '' && $d['text'] !== '') {
                    $t = mb_substr($d['text'], 0, 80).(mb_strlen($d['text']) > 80 ? '…' : '');
                }

                return e($t);
            },
        ]);

        $this->addColumn([
            'index' => 'sort_order',
            'label' => trans('admin::app.settings.duas.datagrid.sort-order'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index' => 'status',
            'label' => trans('admin::app.settings.duas.datagrid.status'),
            'type' => 'boolean',
            'searchable' => true,
            'filterable' => true,
            'filterable_options' => [
                ['label' => trans('admin::app.settings.duas.datagrid.active'), 'value' => 1],
                ['label' => trans('admin::app.settings.duas.datagrid.inactive'), 'value' => 0],
            ],
            'sortable' => true,
            'closure' => function ($row) {
                if ($row->status) {
                    return '<p class="label-active">'.e(trans('admin::app.settings.duas.datagrid.active')).'</p>';
                }

                return '<p class="label-pending">'.e(trans('admin::app.settings.duas.datagrid.inactive')).'</p>';
            },
        ]);

        $this->addColumn([
            'index' => 'updated_at',
            'label' => trans('admin::app.settings.duas.datagrid.updated-at'),
            'type' => 'datetime',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true,
            'closure' => fn ($row) => core()->formatDate($row->updated_at),
        ]);
    }

    public function prepareActions(): void
    {
        if (bouncer()->hasPermission('adhkar_duas.duas.edit')) {
            $this->addAction([
                'index' => 'edit',
                'icon' => 'icon-edit',
                'title' => trans('admin::app.settings.duas.datagrid.edit'),
                'method' => 'GET',
                'url' => fn ($row) => route('admin.adhkar-duas.duas.edit', $row->id),
            ]);
        }

        if (bouncer()->hasPermission('adhkar_duas.duas.delete')) {
            $this->addAction([
                'index' => 'delete',
                'icon' => 'icon-delete',
                'title' => trans('admin::app.settings.duas.datagrid.delete'),
                'method' => 'DELETE',
                'url' => fn ($row) => route('admin.adhkar-duas.duas.destroy', $row->id),
            ]);
        }
    }
}
