<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\LanguageHelper;

class LanguageController extends Controller
{
    public function switchLang($lang)
    {
        // Validate the language
        if (!in_array($lang, ['en', 'fr', 'ar'])) {
            return redirect()->back();
        }

        // Set the new language
        LanguageHelper::setLocale($lang);
        
        // Redirect back to the previous page
        return redirect()->back();
    }
} 