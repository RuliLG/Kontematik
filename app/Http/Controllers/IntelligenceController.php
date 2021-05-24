<?php

namespace App\Http\Controllers;

use App\Services\Intelligence;
use Illuminate\Http\Request;

class IntelligenceController extends Controller
{
    public function detectLanguage (Request $request)
    {
        $request->validate([
            'text' => 'required|string'
        ]);
        $text = $request->get('text', '');
        if (strlen($text) < 30) {
            return;
        }

        return response()->json([
            'language' => (new Intelligence())->detectLanguage($text),
        ]);
    }
}
