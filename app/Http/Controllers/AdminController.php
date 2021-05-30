<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function render()
    {
        $services = Service::with('category')->orderBy('order', 'ASC')->get();
        return view('admin.dashboard', [
            'services' => $services,
        ]);
    }

    public function renderNewService()
    {
        return view('admin.edit-service', [
            'service' => null,
        ]);
    }

    public function renderEditService(Service $service)
    {
        return view('admin.edit-service', [
            'service' => $service,
        ]);
    }
}
