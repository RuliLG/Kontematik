<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;

class CopyController extends Controller
{
    public function render()
    {
        $categories = ServiceCategory::with('services')
            ->whereHas('services')
            ->get();
        return view('copy', [
            'categories' => $categories,
        ]);
    }

    public function renderTool(Service $service)
    {
        $categories = ServiceCategory::with('services')
            ->whereHas('services')
            ->get();

        return view('copy', [
            'categories' => $categories,
            'service' => $service,
        ]);
    }
}
