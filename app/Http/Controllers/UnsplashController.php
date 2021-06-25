<?php

namespace App\Http\Controllers;

use App\Services\Intelligence;
use App\Services\Unsplash;
use Illuminate\Http\Request;

class UnsplashController extends Controller
{
    public function generate (Request $request)
    {
        $request->validate([
            'text' => 'required|string',
        ]);
        $language = (new Intelligence)->detectLanguage($request->get('text'));
        $images = (new Unsplash)->search($request->get('text'), $language);
        $imageUrl = $images ? $images[0]['urls']['full'] : null;
        return response()->json([
            'image' => $imageUrl,
        ]);
    }
}
