<?php

namespace App\Http\Livewire\Admin;

use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\ServiceField;
use App\Models\ServicePrompt;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
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
        'service.gpt3_engine' => 'required|in:davinci,curie,ada,babbage',
        'service.tw_color' => 'required|in:blueGray,coolGray,gray,trueGray,warmGray,red,orange,amber,yellow,lime,green,emerald,teal,cyan,lightBlue,blue,indigo,violet,purple,fuchsia,pink,rose',
        'service.icon_name' => 'required|string',
        'fields.*.label' => 'required|string',
        'fields.*.placeholder' => 'sometimes|nullable|string',
        'fields.*.order' => 'required|integer|min:0|max:20',
        'fields.*.type' => 'required|in:text,textarea',
        'fields.*.max_length' => 'required|integer|min:0|max:512',
        'fields.*.is_required' => 'required|boolean',
        'tags' => 'sometimes|string',
        'prompts.es' => 'required|string',
        'prompts.en' => 'sometimes|string',
        'prompts.de' => 'sometimes|string',
        'prompts.fr' => 'sometimes|string',
        'prompts.it' => 'sometimes|string',
    ];

    public $categories = [];
    public $icons = [];

    public $fields = [];
    public $deletedFieldIds = [];

    public $prompts = [
        'es' => '',
        'en' => '',
        'de' => '',
        'fr' => '',
        'it' => '',
        'pt' => '',
    ];
    public $promptErrors = [
        'es' => null,
        'en' => null,
        'de' => null,
        'fr' => null,
        'it' => null,
        'pt' => null,
    ];

    public $iconQuery = '';
    public $tags = '';

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
            $this->service->gpt3_engine = 'davinci';
            $this->service->icon_name = 'eos-nfc';
            $this->service->tags = '[]';
        } else {
            $this->fields = $this->service->fields->toArray();
            $this->tags = join(',', $this->service->tags);

            $prompts = $this->service->prompts;
            foreach ($prompts as $prompt) {
                $this->prompts[$prompt->language_code] = $prompt->raw_prompt;
            }
        }

        $this->checkPromptsForErrors();

        $this->categories = ServiceCategory::orderBy('order', 'ASC')->get();
    }

    public function render()
    {
        return view('livewire.admin.edit-service');
    }

    public function updatedServiceName($newVal)
    {
        $this->service->slug = Str::slug($newVal);
    }

    public function updatedIconQuery($newVal)
    {
        $this->searchIcons();
    }

    public function updatedPrompts($newVal, $key)
    {
        $this->checkPromptsForErrors();
    }

    public function setColor($color)
    {
        $this->service->tw_color = $color;
    }

    public function save()
    {
        $this->validate();

        DB::beginTransaction();
        $this->service->icon_name = $this->service->icon_name;
        $this->service->slug = Str::slug($this->service->slug);
        $this->service->tags = json_encode(
            array_values(
                array_filter(
                    array_map('trim', $this->tags ? explode(',', $this->tags) : [])
                )
            )
        );
        $this->service->save();


        if (!empty($this->deletedFieldIds)) {
            ServiceField::destroy($this->deletedFieldIds);
        }

        foreach ($this->fields as $field) {
            if (isset($field['id'])) {
                $serviceField = ServiceField::findOrFail($field['id']);
            } else if (isset($field['_id'])) {
                $serviceField = new ServiceField;
            }

            foreach ($field as $key => $value) {
                if (Str::startsWith($key, '_')) {
                    continue;
                }

                $serviceField->{$key} = $value;
            }

            $serviceField->service_id = $this->service->id;

            $serviceField->save();
        }

        foreach ($this->prompts as $lang => $text) {

            $prompt = ServicePrompt::where([
                'service_id' => $this->service->id,
                'language_code' => $lang,
            ])->first();
            $this->promptErrors[$lang] = null;

            if (!$prompt) {
                if (empty($text)) {
                    continue;
                }

                $prompt = new ServicePrompt;
                $prompt->service_id = $this->service->id;
                $prompt->language_code = $lang;
            } else if (empty($text)) {
                $prompt->delete();
                continue;
            }

            $prompt->raw_prompt = $text;
            $prompt->save();
        }

        DB::commit();
        return redirect(route('admin'));
    }

    public function addNewField()
    {
        $field = [
            '_id' => Str::uuid(),
            'service_id' => $this->service->id,
            'order' => count($this->fields),
            'is_required' => true,
            'field_location' => 'default',
            'type' => 'text',
            'max_length' => 60,
            'name' => 'field_' . Str::random(5),
        ];
        $this->fields[] = $field;
    }

    public function deleteField($index)
    {
        $field = $this->fields[$index];
        if (isset($field['id'])) {
            $this->deletedFieldIds[] = $field['id'];
        }

        unset($this->fields[$index]);
        $this->fields = array_values($this->fields);
    }

    private function searchIcons()
    {
        if (empty($this->iconQuery)) {
            $this->icons = [];
            return;
        }

        $slug = Str::slug($this->iconQuery);

        $icons = collect(
            array_slice(scandir(realpath(public_path() . '/../vendor/codeat3/blade-eos-icons/resources/svg')), 2)
        )
            ->map(function ($name) {
                return 'eos-' . str_replace('.svg', '', $name);
            })
            ->filter(function ($name) use ($slug) {
                return strpos($name, $slug) !== false;
            })
            ->take(20);

        $this->icons = $icons;
    }

    private function checkPromptsForErrors()
    {
        $fields = array_column($this->fields, 'name');
        foreach ($this->prompts as $lang => $prompt) {
            $this->promptErrors[$lang] = null;
            $matches = [];
            preg_match_all('/\{([\w_-]+)\}/', $prompt, $matches);
            foreach ($matches[1] as $possibleField) {
                if (!in_array($possibleField, $fields)) {
                    $this->promptErrors[$lang] = $possibleField . ' does not exist.';
                    break;
                }
            }
        }
    }
}
