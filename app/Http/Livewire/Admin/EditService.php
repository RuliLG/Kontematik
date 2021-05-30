<?php

namespace App\Http\Livewire\Admin;

use App\Models\Service;
use Codeat3\BladeEosIcons\BladeEosIconsServiceProvider;
use Livewire\Component;
use Str;

class EditService extends Component
{
    public $service = null;
    protected $rules = [
        'service.name' => 'required|string|min:4|max:255',
        'service.service_category_id' => 'required|exists:service_categories,id',
        'service.slug' => 'required|string',
        'service.order' => 'required|integer|min:0',
        'service.gpt3_temperature' => 'required|numeric|min:0|max:1',
        'service.gpt3_tokens' => 'required|integer|min:16|max:2048',
        'service.gpt3_best_of' => 'required|integer|min:1|max:20',
        'service.gpt3_n' => 'required|integer|lte:service.gpt3_best_of',
        'service.tw_color' => 'required|in:blueGray,coolGray,gray,trueGray,warmGray,red,orange,amber,yellow,lime,green,emerald,teal,cyan,lightBlue,blue,indigo,violet,purple,fuchsia,pink,rose',
        'service.icon_name' => 'required|string',
    ];

    public $icons = [];

    public function mount()
    {
        if (!$this->service) {
            $this->service = new Service;
            $this->service->tw_color = 'red';
            $this->service->order = 1;
            $this->service->gpt3_temperature = 0.7;
            $this->service->gpt3_tokens = 64;
            $this->service->gpt3_best_of = 3;
            $this->service->gpt3_n = 3;
            $this->service->icon_name = 'eos-nfc';
        }

        $icons = collect(
            array_slice(scandir(realpath(public_path() . '/../vendor/codeat3/blade-eos-icons/resources/svg')), 2)
        )->map(function ($name) {
            return 'eos-' . str_replace('.svg', '', $name);
        });
        $this->icons = $icons;
    }

    public function render()
    {
        return view('livewire.admin.edit-service');
    }

    public function updatedServiceName($newVal)
    {
        $this->service->slug = Str::slug($newVal);
    }

    public function setColor($color)
    {
        $this->service->tw_color = $color;
    }

    public function save()
    {
        $this->validate();
        $this->service->icon_name = $this->service->icon_name;
        $this->service->slug = Str::slug($this->service->slug);
        $this->service->save();
        return redirect(route('admin'));
    }
}
