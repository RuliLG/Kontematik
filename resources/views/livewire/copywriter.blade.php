<div class="tooltip-parent">
    <form wire:submit.prevent="generate" onsubmit="trackGoal('DIIAOO6H')" id="copy-form">
        <div class="space-y-4">
            @foreach ($this->default_fields as $i => $field)
            <div x-data="alpineFieldLength('{{ $field->name }}', {{ $field->max_length }})">
                <label class="block font-bold text-gray-500">
                    {{ $field->label }}
                    @if ($field->is_required)
                    <small class="text-sm text-gray-400 inline pl-2 font-normal">&mdash; Required</small>
                    @endif
                </label>
                <div class="relative">
                    @if ($field->type === 'textarea')
                    <textarea
                        id="{{ $field->name }}"
                        type="text"
                        rows="5"
                        class="block w-full p-4 rounded-lg border border-gray-300"
                        placeholder="{{ $field->placeholder }}"
                        required="{{ $field->is_required ? 'true' : 'false' }}"
                        wire:loading.attr="disabled"
                        wire:target="generate"
                        wire:model.debounce.250="data.{{ $field->name }}"
                        {{ $i === 0 ? 'autofocus' : ''}}
                        data-initial-value="{{ $data[$field->name] }}"
                        x-model="text"
                    ></textarea>
                    @else
                    <input
                        id="{{ $field->name }}"
                        type="text"
                        wire:loading.attr="disabled"
                        wire:target="generate"
                        wire:model.debounce.250="data.{{ $field->name }}"
                        class="block w-full p-4 pr-16 rounded-lg border border-gray-300"
                        required="{{ $field->is_required ? 'true' : 'false' }}"
                        placeholder="{{ $field->placeholder }}"
                        {{ $i === 0 ? 'autofocus' : ''}}
                        data-initial-value="{{ $data[$field->name] }}"
                        x-model="text"
                    >
                    @endif
                    @if ($field->max_length > 0)
                    <span
                        x-cloak
                        class="absolute right-0 bottom-0 mr-2 my-1 text-sm font-semibold bg-white px-1 pb-3 pt-4 rounded text-red-700"
                        :class="{
                            'text-gray-600': text.length < max * 0.8,
                            'text-yellow-700': text.length >= max * 0.8 && text.length <= max,
                            'text-red-700': text.length > max
                        }"
                    >
                        <span x-text="text.length"></span> / {{ $field->max_length }}
                    </span>
                    @endif
                </div>
                @error('data.' . $field->name)
                <span class="mt-1 block text-red-600 text-sm">{{ str_replace('data.', '', $message) }}</span>
                @enderror
            </div>
            @endforeach
        </div>

        @error('limit_reached')
        <x-warning>
            You have reached your usage limit. Please,
            <a href="/billing" class="font-medium underline text-yellow-700 hover:text-yellow-600">
                upgrade your account
            </a>.
        </x-warning>
        @enderror

        @error('unsafe_prompt')
        <x-warning>
            Oops! The entered text seems to use profane, prejudiced or hateful language, something that could be NSFW, or text that portrays certain groups/people in a harmful manner. Continous of this kind of language may lead to the cancellation of your account. Please, change your input and try again.
        </x-warning>
        @enderror

        @error('already_generating')
        <x-warning>
            You are already generating with another tool. Please, kindly wait until it finishes.
        </x-warning>
        @enderror

        <button type="submit" wire:loading.class="hidden" wire:target="generate" class="block w-full bg-purple-600 p-4 rounded-lg text-white mt-8 hover:bg-purple-500">{{ __('Generate text') }}</button>
        <div class="flex justify-center">
            <label class="inline-flex items-center justify-start font-bold text-gray-500 mt-4">
                Writing in
                <select wire:model="language" class="ml-4 block rounded-lg border-none" wire:loading.attr="disabled" wire:target="generate" id="language-select">
                    <option value="auto">🪄 Autodetect</option>
                    @foreach ($languages as $lang)
                    <option value="{{ $lang['code'] }}">{{ $lang['name'] }}</option>
                    @endforeach
                </select>
            </label>
        </div>
    </form>

    <div wire:loading wire:target="generate">
        <x-loading-text></x-loading-text>
    </div>

    @if (!empty($responses))
        <ul class="space-y-4 mt-8 -mx-4">
            @foreach ($responses as $i => $response)
            <li class="p-4">
                <livewire:result
                    :response="$response"
                    :service="$service"
                    :result="$result"
                    :i="$i"
                    :integrationToken="$integrationToken"
                    :key="'result_' . $i"
                ></livewire:result>
            </li>
            @endforeach
        </ul>

        <div class="flex flex-col justify-center items-center mt-16">
            <span class="block text-center text-xl font-bold text-gray-900">{{ __('Rate this result') }}</span>
            <div class="rating flex justify-center items-center mt-4">
                @for ($i = 1; $i <= 5; $i++)
                <button type="button" class="{{ $result->rating >= $i ? 'selected' : 'text-gray-500' }} focus:outline-none" wire:click="rate({{ $i }})">
                    @svg('eos-star', 'h-8 w-8')
                </button>
                @endfor
            </div>
        </div>

        @if (!$result->webflow_share_uuid)
        <div class="text-center mt-8">
            <button wire:loading.remove wire:target="share" type="button" wire:click="share" onclick="trackGoal('GXJK4T5S')" class="inline-flex justify-center items-center text-center w-full py-4 px-12 bg-lightBlue-700 text-white font-bold rounded-lg hover:bg-lightBlue-600 md:w-auto focus:outline-none">
                @svg('eos-ios-share', 'w-6 h-6 mr-4')
                Share
            </button>
            <div wire:loading wire:target="share" class="inline-flex justify-center items-center text-center w-full py-4 px-12 bg-lightBlue-700 text-white font-bold rounded-lg md:w-auto">
                @svg('eos-ios-share', 'w-6 h-6 mr-4')<span>Generating sharing link...</span>
            </div>
        </div>
        @else
        <div class="mt-8">
            <div class="border-dashed border-gray-300 border-2 rounded-lg p-8 text-lightBlue-700 text-base font-bold text-center" data-tooltip="{{ __('Click to copy') }}" data-action-tooltip="{{ __('Copied!') }}" onclick="copy('share-url', this)">
                <span class="text-xl text-gray-900">{{ __('Share this URL with your team') }}</span>
                <span id="share-url" class="mt-4">{{ $result->webflow_url }}</span>
            </div>
            <label wire:click="toggleIndexation" class="block mt-4 text-center">
                <input type="checkbox" class="h-6 w-6 text-lightBlue-700" {{ $indexable ? '' : 'checked' }}> {{ __('I do not want this URL to be indexed on Google') }}
            </label>
        </div>
        @endif
    @endif
</div>
