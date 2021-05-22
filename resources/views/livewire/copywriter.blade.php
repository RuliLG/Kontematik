<div class="tooltip-parent">
    <form wire:submit.prevent="generate">
        <div class="space-y-4">
            @foreach ($this->default_fields as $field)
            <div>
                <label>{{ $field->label }}</label>
                <input type="text" wire:loading.attr="disabled" wire:target="generate" wire:model="data.{{$field->name}}" class="block w-full p-4 rounded-lg border border-gray-300" required="{{ $field->is_required ? 'true' : 'false' }}">
            </div>
            @endforeach
        </div>
        <button type="submit" wire:loading.class="hidden" wire:target="generate" class="block w-full bg-purple-600 p-4 rounded-lg text-white mt-8 hover:bg-purple-500">{{ __('Generate text') }}</button>
    </form>

    <div wire:loading wire:target="generate">
        <x-loading-text></x-loading-text>
    </div>

    @if (!empty($responses))
    <ul class="space-y-4 mt-8 -mx-4">
        @foreach ($responses as $i => $response)
        <li class="p-4">
            <div class="bg-white shadow-lg border border-gray-300 p-4 rounded-lg flex justify-between items-center lg:p-8">
                <div class="flex items-start justify-start">
                    <span class="h-8 w-8 flex-shrink-0 mr-4 bg-lightBlue-800 flex items-center justify-center rounded-full text-white font-bold text-sm">{{ $i + 1 }}</span>
                    <p class="font-bold text-gray-900" id="result_{{ $i }}">{{ $response }}</p>
                </div>
                <div class="flex-shrink-0 ml-4">
                    <button type="button" wire:click="saveGeneratedText('{{$response}}')" onclick="updateTooltip(this)" class="p-4 focus:outline-none {{ isset($saved[$response]) ? 'text-lightBlue-700' : 'text-gray-400' }}" data-tooltip="{{ isset($saved[$response]) ? __('Remove from your library') : __('Save to your library') }}" data-action-tooltip="{{ isset($saved[$response]) ? __('Removed!') : __('Saved!') }}">
                        @svg('eos-bookmark', 'h-6 w-6')
                    </button>
                    <button type="button" class="text-lightBlue-800 p-4 focus:outline-none" data-tooltip="{{ __('Copy to clipboard') }}" data-action-tooltip="{{ __('Copied!') }}" onclick="copy('result_{{ $i }}', this)">
                        @svg('eos-content-copy', 'w-6 h-6')
                    </button>
                </div>
            </div>
        </li>
        @endforeach
    </ul>
    @endif
</div>
