<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MagazzinoOverviewController extends Controller
{
    public function index()
    {
        return view('magazzino-overview.index');
    }
}
