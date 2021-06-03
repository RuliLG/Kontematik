<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;

class ServiceItem extends Component
{
    public $service;

    public function render()
    {
        return view('livewire.admin.service-item');
    }

    public function toggleEnabled()
    {
        $this->service->is_enabled = !$this->service->is_enabled;
        $this->service->save();
    }

    public function togglePopular()
    {
        $this->service->is_popular = !$this->service->is_popular;
        $this->service->save();
    }
}
