<?php

namespace Webkul\Web\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Routing\Controller;
use Webkul\Web\Repositories\WebDuaRepository;

class AdhkarController extends Controller
{
    public function index(WebDuaRepository $duaRepository): View
    {
        $rows = $duaRepository->getActiveSectionsWithDuasForLocale();

        $duaTabs = [];
        foreach ($rows as $row) {
            $cards = [];
            foreach ($row['duas'] as $d) {
                $cards[] = [
                    'title'     => $d['title'],
                    'badge'     => $row['badge'],
                    'text'      => $d['text'],
                    'reference' => $d['reference'],
                ];
            }
            $duaTabs[] = [
                'tab_id' => $row['tab_id'],
                'label'  => $row['label'],
                'cards'  => $cards,
            ];
        }

        return view('web::adhkar.index', [
            'duaTabs'   => $duaTabs,
            'pageTitle' => __('web::app.adhkar.meta_title'),
        ]);
    }
}
