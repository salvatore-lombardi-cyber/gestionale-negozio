<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AnagraficheController extends Controller
{
    public function index()
    {
        return view('anagrafiche.index');
    }
}
