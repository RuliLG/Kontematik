<?php

namespace App\Http\Livewire;

use App\Models\Service;
use App\Models\ServiceCategory;
use Livewire\Component;

class ServiceList extends Component
{
    public $query = '';

    public function render()
    {
        if (empty($this->query)) {
            $serviceIds = Service::select('id')->get()->pluck('id');
        } else {
            $serviceIds = Service::search($this->query)->get()->pluck('id');
        }

        $categories = ServiceCategory::with([
                'services' => function ($query) use ($serviceIds) {
                    $query->whereIn('id', empty($serviceIds) ? [-1] : $serviceIds);
                }
            ])
            ->get()
            ->filter(function ($category) {
                return !$category->services->isEmpty();
            });

        return view('livewire.service-list', [
            'categories' => $categories,
        ]);
    }
}
