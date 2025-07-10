<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function changeLanguage($locale)
    {
        // Salva la lingua nella sessione
        Session::put('locale', $locale);
        
        // Torna alla pagina precedente
        return redirect()->back();
    }
}