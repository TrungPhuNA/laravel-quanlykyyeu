<?php

namespace App\Http\Controllers\Language;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function renderLanguage($language = 'en')
    {
        if (! in_array($language, ['en', 'vi'])) {
            abort(404);
        }

        Session::put('language', $language);
        return redirect()->back();
    }
}
