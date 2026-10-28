<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function switchLang($locale)
    {
        // Only allow supported languages
        if (! in_array($locale, ['en', 'zh_CN', 'fr'])) {
            abort(400);
        }

        // Save the choice in session
        session()->put('locale', $locale);

        return redirect()->back();
    }
}