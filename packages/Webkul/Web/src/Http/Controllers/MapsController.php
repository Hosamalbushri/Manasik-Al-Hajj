<?php

namespace Webkul\Web\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Schema;
use Webkul\Web\Repositories\WebMapLocationRepository;

class MapsController extends Controller
{
    public function index(): View
    {
        $cards = [];

        if (Schema::hasTable('web_map_locations')) {
            $fromDb = app(WebMapLocationRepository::class)->getActiveCardsForLocale();
            if ($fromDb->isNotEmpty()) {
                $cards = $fromDb->all();
            }
        }

        $cardDetails = [];
        foreach ($cards as $row) {
            if (! is_array($row) || empty($row['slug'])) {
                continue;
            }
            $slug = (string) $row['slug'];
            $cardDetails[$slug] = (string) ($row['detail_alert'] ?? '');
        }

        return view('web::maps.index', [
            'cards'       => $cards,
            'cardDetails' => $cardDetails,
            'pageTitle'   => __('web::app.maps.meta_title'),
        ]);
    }
}
