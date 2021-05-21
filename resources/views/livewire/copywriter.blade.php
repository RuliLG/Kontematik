<div>
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
    <div class="space-y-4 mt-8">
        @foreach ($responses as $response)
        <div class="flex">
            <div class="bg-purple-50 border border-purple-100 rounded-lg text-purple-800 p-4 w-full">
                {{ $response }}
            </div>
            <div class="flex-shrink-0">
                <button type="button" wire:click="saveGeneratedText('{{$response}}')" class="p-4 {{ isset($saved[$response]) ? 'text-yellow-600' : 'text-gray-400' }}">
                    @svg('eos-grade', 'h-6 w-6')
                </button>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
