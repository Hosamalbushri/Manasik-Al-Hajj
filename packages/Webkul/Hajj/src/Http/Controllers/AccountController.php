<?php

namespace Webkul\Hajj\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function index(): View
    {
        return view('hajj::account.index', [
            'hajjUser' => auth()->guard('hajj')->user(),
        ]);
    }
}
