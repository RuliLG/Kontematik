<div class="min-h-screen bg-gray-100">
    <div class="py-10">
        <!-- Page header -->
        <div class="max-w-3xl mx-auto px-4 sm:px-6 md:flex md:items-center md:justify-between md:space-x-5 lg:max-w-7xl lg:px-8">
            <div class="flex items-center space-x-5">
                <div class="flex-shrink-0">
                    <div class="relative bg-{{ $service->tw_color }}-100 rounded-full h-16 w-16 flex items-center justify-center text-{{ $service->tw_color }}-800">
                        @svg($service->icon_name, 'w-8 h-8')
                    </div>
                </div>
                <div>
                    <input class="text-2xl font-bold text-gray-900 bg-gray-200 rounded-lg p-3 focus:outline-none" wire:model="service.name" placeholder="Service name" {{ $service->id ? '' : 'autofocus' }} />
                </div>
            </div>
            <div class="mt-6 flex flex-col-reverse justify-stretch space-y-4 space-y-reverse sm:flex-row-reverse sm:justify-end sm:space-x-reverse sm:space-y-0 sm:space-x-3 md:mt-0 md:flex-row md:space-x-3">
                <a href="{{ route('admin') }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-blue-500">
                    Go back
                </a>
                @if ($service->id)
                <a href="{{ route('tool', ['service' => $service->slug]) }}" target="_blank" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-blue-500">
                    Preview
                </a>
                @endif
                <button wire:click="save" type="button" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-blue-500">
                    Save
                </button>
            </div>
        </div>

        <div class="mt-8 max-w-3xl mx-auto grid grid-cols-1 gap-6 sm:px-6 lg:max-w-7xl">
            <div class="space-y-6 lg:col-start-1 lg:col-span-2">
                <!-- Tool Information-->
                <section aria-labelledby="applicant-information-title">
                    <div class="bg-white shadow sm:rounded-lg">
                        <div class="px-4 py-5 sm:px-6">
                            <h2 id="applicant-information-title" class="text-lg leading-6 font-medium text-gray-900">
                                Tool Information
                            </h2>
                        </div>
                        <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
                            <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">
                                        Slug
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        <input type="text" class="bg-white border border-gray-200 rounded" wire:model="service.slug" placeholder="example-slug">
                                    </dd>
                                </div>
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">
                                        Order inside category
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        <input type="number" min="0" max="1000" class="bg-white border border-gray-200 rounded" wire:model="service.order">
                                    </dd>
                                </div>
                                <div class="sm:col-span-2">
                                    <dt class="text-sm font-medium text-gray-500">
                                        Tags
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        <input type="text" wire:model="tags" class="bg-white border border-gray-200 rounded w-full">
                                        <small class="block text-xs text-gray-500">Introduce multiple words separated by comma</small>
                                    </dd>
                                </div>
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">
                                        Color: {{ $service->tw_color }}
                                    </dt>
                                    <dd class="mt-1 flex flex-wrap">
                                        <div class="p-1"><div wire:click="setColor('blueGray')" class="block h-6 w-6 rounded bg-blueGray-600 {{ $service->tw_color === 'blueGray' ? '' : 'opacity-50 hover:opacity-100' }}"></div></div>
                                        <div class="p-1"><div wire:click="setColor('coolGray')" class="block h-6 w-6 rounded bg-coolGray-600 {{ $service->tw_color === 'coolGray' ? '' : 'opacity-50 hover:opacity-100' }}"></div></div>
                                        <div class="p-1"><div wire:click="setColor('gray')" class="block h-6 w-6 rounded bg-gray-600 {{ $service->tw_color === 'gray' ? '' : 'opacity-50 hover:opacity-100' }}"></div></div>
                                        <div class="p-1"><div wire:click="setColor('trueGray')" class="block h-6 w-6 rounded bg-trueGray-600 {{ $service->tw_color === 'trueGray' ? '' : 'opacity-50 hover:opacity-100' }}"></div></div>
                                        <div class="p-1"><div wire:click="setColor('warmGray')" class="block h-6 w-6 rounded bg-warmGray-600 {{ $service->tw_color === 'warmGray' ? '' : 'opacity-50 hover:opacity-100' }}"></div></div>
                                        <div class="p-1"><div wire:click="setColor('red')" class="block h-6 w-6 rounded bg-red-600 {{ $service->tw_color === 'red' ? '' : 'opacity-50 hover:opacity-100' }}"></div></div>
                                        <div class="p-1"><div wire:click="setColor('orange')" class="block h-6 w-6 rounded bg-orange-600 {{ $service->tw_color === 'orange' ? '' : 'opacity-50 hover:opacity-100' }}"></div></div>
                                        <div class="p-1"><div wire:click="setColor('amber')" class="block h-6 w-6 rounded bg-amber-600 {{ $service->tw_color === 'amber' ? '' : 'opacity-50 hover:opacity-100' }}"></div></div>
                                        <div class="p-1"><div wire:click="setColor('yellow')" class="block h-6 w-6 rounded bg-yellow-600 {{ $service->tw_color === 'yellow' ? '' : 'opacity-50 hover:opacity-100' }}"></div></div>
                                        <div class="p-1"><div wire:click="setColor('lime')" class="block h-6 w-6 rounded bg-lime-600 {{ $service->tw_color === 'lime' ? '' : 'opacity-50 hover:opacity-100' }}"></div></div>
                                        <div class="p-1"><div wire:click="setColor('green')" class="block h-6 w-6 rounded bg-green-600 {{ $service->tw_color === 'green' ? '' : 'opacity-50 hover:opacity-100' }}"></div></div>
                                        <div class="p-1"><div wire:click="setColor('emerald')" class="block h-6 w-6 rounded bg-emerald-600 {{ $service->tw_color === 'emerald' ? '' : 'opacity-50 hover:opacity-100' }}"></div></div>
                                        <div class="p-1"><div wire:click="setColor('teal')" class="block h-6 w-6 rounded bg-teal-600 {{ $service->tw_color === 'teal' ? '' : 'opacity-50 hover:opacity-100' }}"></div></div>
                                        <div class="p-1"><div wire:click="setColor('cyan')" class="block h-6 w-6 rounded bg-cyan-600 {{ $service->tw_color === 'cyan' ? '' : 'opacity-50 hover:opacity-100' }}"></div></div>
                                        <div class="p-1"><div wire:click="setColor('lightBlue')" class="block h-6 w-6 rounded bg-lightBlue-600 {{ $service->tw_color === 'lightBlue' ? '' : 'opacity-50 hover:opacity-100' }}"></div></div>
                                        <div class="p-1"><div wire:click="setColor('blue')" class="block h-6 w-6 rounded bg-blue-600 {{ $service->tw_color === 'blue' ? '' : 'opacity-50 hover:opacity-100' }}"></div></div>
                                        <div class="p-1"><div wire:click="setColor('indigo')" class="block h-6 w-6 rounded bg-indigo-600 {{ $service->tw_color === 'indigo' ? '' : 'opacity-50 hover:opacity-100' }}"></div></div>
                                        <div class="p-1"><div wire:click="setColor('violet')" class="block h-6 w-6 rounded bg-violet-600 {{ $service->tw_color === 'violet' ? '' : 'opacity-50 hover:opacity-100' }}"></div></div>
                                        <div class="p-1"><div wire:click="setColor('purple')" class="block h-6 w-6 rounded bg-purple-600 {{ $service->tw_color === 'purple' ? '' : 'opacity-50 hover:opacity-100' }}"></div></div>
                                        <div class="p-1"><div wire:click="setColor('fuchsia')" class="block h-6 w-6 rounded bg-fuchsia-600 {{ $service->tw_color === 'fuchsia' ? '' : 'opacity-50 hover:opacity-100' }}"></div></div>
                                        <div class="p-1"><div wire:click="setColor('pink')" class="block h-6 w-6 rounded bg-pink-600 {{ $service->tw_color === 'pink' ? '' : 'opacity-50 hover:opacity-100' }}"></div></div>
                                        <div class="p-1"><div wire:click="setColor('rose')" class="block h-6 w-6 rounded bg-rose-600 {{ $service->tw_color === 'rose' ? '' : 'opacity-50 hover:opacity-100' }}"></div></div>
                                    </dd>
                                </div>
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">
                                        Category
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        <select wire:model="service.service_category_id" class="block w-full border border-gray-300 rounded">
                                            <option value="">Select category</option>
                                            @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </dd>
                                </div>
                                <div class="sm:col-span-2">
                                    <dt class="text-sm font-medium text-gray-500">
                                        Icon: @svg($service->icon_name, 'w-6 h-6 inline-flex ml-4 text-gray-800')
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        <input type="search" class="block bg-transparent py-3 px-4 text-lg text-gray-700 border-gray-300 rounded-lg w-full focus:outline-none focus:ring-0" placeholder="Search icon" wire:model.debounce.1000ms="iconQuery">
                                        @if ($iconQuery === '')
                                        <span class="block mt-8 text-gray-600 text-2xl text-center">Write to find an icon</span>
                                        @elseif (empty($icons))
                                        <span class="block mt-8 text-gray-600 text-2xl text-center">No icons were found</span>
                                        @else
                                        <ul class="mt-8 flex flex-wrap justify-center">
                                            @foreach ($icons as $icon)
                                            <li class="w-48 p-4">
                                                <div wire:click="$set('service.icon_name', '{{ $icon }}')" class="flex flex-col items-center justify-center cursor-pointer group hover:text-{{$service->tw_color}}-600">
                                                    @svg($icon, 'w-8 h-8 text-gray-700 group-hover:text-' . $service->tw_color . '-600')
                                                    <span class="mt-4 w-full text-center">{{ $icon }}</span>
                                                </div>
                                            </li>
                                            @endforeach
                                        </ul>
                                        @endif
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </section>

                <!-- GPT3 -->
                <section aria-labelledby="notes-title">
                    <div class="bg-white shadow sm:rounded-lg sm:overflow-hidden">
                        <div class="divide-y divide-gray-200">
                            <div class="px-4 py-5 sm:px-6">
                                <h2 id="notes-title" class="text-lg font-medium text-gray-900">GPT3 Configuration</h2>
                            </div>
                            <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
                                <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500">
                                            Temperature
                                            <small class="block text-xs text-gray-500">How <em>creative</em> the model will try to be. The closer to 1, the more creative.</small>
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900">
                                            <input type="text" class="bg-white border border-gray-200 rounded" wire:model="service.gpt3_temperature" placeholder="0.75">
                                        </dd>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500">
                                            Tokens
                                            <small class="block text-xs text-gray-500">Maximum tokens between input prompt and each output. 1 token = 4 characters.</small>
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900">
                                            <input type="number" min="16" max="2048" class="bg-white border border-gray-200 rounded w-48" wire:model="service.gpt3_tokens">
                                            <small class="block text-xs text-gray-500">{{ $service->gpt3_tokens * 4 }} characters.</small>
                                        </dd>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500">
                                            Best of
                                            <small class="block text-xs text-gray-500">How many outputs will the model generate.</small>
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900">
                                            <input type="number" min="1" max="20" class="bg-white border border-gray-200 rounded w-48" wire:model="service.gpt3_best_of">
                                        </dd>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500">
                                            N
                                            <small class="block text-xs text-gray-500">How many outputs will the model select (it has to be, at most, the same number of best_of).</small>
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900">
                                            <input type="number" min="1" max="20" class="bg-white border border-gray-200 rounded w-48" wire:model="service.gpt3_n">
                                        </dd>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500">
                                            Engine
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900">
                                            <select class="bg-white border border-gray-200 rounded w-full" wire:model="service.gpt3_best_of">
                                                <option value="davinci">Davinci</option>
                                                <option value="curie">Curie</option>
                                                <option value="babbage">Babbage</option>
                                                <option value="ada">Ada</option>
                                            </select>
                                        </dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Fields -->
                <section aria-labelledby="fields-title">
                    <div class="bg-white shadow sm:rounded-lg sm:overflow-hidden">
                        <div class="divide-y divide-gray-200">
                            <div class="px-4 py-5 sm:px-6">
                                <h2 id="fields-title" class="text-lg font-medium text-gray-900">Fields</h2>
                            </div>
                            <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
                                <div class="grid grid-cols-1 gap-y-8">
                                    @foreach ($fields as $i => $field)
                                    <div class="relative border-2 border-dashed border-gray-300 rounded-lg p-4 grid grid-cols-1 gap-4 md:grid-cols-3" wire:key="{{ isset($field['id']) ? $field['id'] : $field['_id'] }}">
                                        <label class="text-sm text-gray-900">
                                            <span class="block">Field label</span>
                                            <input type="text" class="bg-white border border-gray-200 rounded block w-full" wire:model="fields.{{ $i }}.label">
                                            <small class="block text-xs text-gray-500">Field name to use in prompt: {{ $field['name'] }}</small>
                                        </label>
                                        <label class="text-sm text-gray-900">
                                            <span class="block">Field placeholder</span>
                                            <input type="text" class="bg-white border border-gray-200 rounded block w-full" wire:model="fields.{{ $i }}.placeholder">
                                            <small class="block text-xs text-gray-500">This would be the sample value displayed to the user.</small>
                                        </label>
                                        <label class="text-sm text-gray-900">
                                            <span class="block">Field type</span>
                                            <select wire:model="fields.{{ $i }}.type" class="border border-gray-200 rounded block w-full">
                                                <option value="text">Textfield</option>
                                                <option value="textarea">Textarea</option>
                                            </select>
                                        </label>
                                        <label class="text-sm text-gray-900">
                                            <span class="block">Order</span>
                                            <input type="number" min="0" max="8" class="bg-white border border-gray-200 rounded block w-full" wire:model="fields.{{ $i }}.order">
                                        </label>
                                        <label class="text-sm text-gray-900">
                                            <span class="block">Max length</span>
                                            <input type="number" min="0" max="512" class="bg-white border border-gray-200 rounded block w-full" wire:model="fields.{{ $i }}.max_length">
                                        </label>
                                        <label class="text-sm text-gray-900">
                                            <input type="checkbox" class="h-6 w-6 text-blue-600" wire:model="fields.{{ $i }}.is_required">
                                            <span>Required</span>
                                        </label>
                                        <button type="button" class="absolute right-0 top-0 p-2 inline-block text-gray-400 hover:text-red-600" wire:click="deleteField({{ $i }})">@svg('eos-delete-o', 'h-6 w-6')</button>
                                    </div>
                                    @endforeach
                                    <button type="button" wire:click="addNewField" class="inline-flex w-full items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-blue-500">Add new field</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Prompts -->
                <section aria-labelledby="prompts-title">
                    <div class="bg-white shadow sm:rounded-lg sm:overflow-hidden">
                        <div class="divide-y divide-gray-200">
                            <div class="px-4 py-5 sm:px-6">
                                <h2 id="prompts-title" class="text-lg font-medium text-gray-900">Prompts</h2>
                            </div>
                            <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
                                <div class="grid grid-cols-1 gap-y-8">
                                    @foreach ($prompts as $lang => $text)
                                    <label class="text-sm text-gray-900">
                                        <span class="block">{{ country_flag($lang) }} Prompt</span>
                                        <textarea wire:model="prompts.{{ $lang }}" rows="7" class="block w-full border-gray-300 rounded-lg p-4"></textarea>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>
