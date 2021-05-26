<?php

namespace App\Http\Controllers;

use App\Services\Intelligence;
use Illuminate\Http\Request;

class IntelligenceController extends Controller
{
    public function detectLanguage (Request $request)
    {
        $request->validate([
            'text' => 'required|string|min:5'
        ]);

        return response()->json([
            'language' => (new Intelligence())->detectLanguage($request->get('text')),
        ]);
    }
}
