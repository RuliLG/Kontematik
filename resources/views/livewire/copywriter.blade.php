<div>
    <form wire:submit.prevent="generate">
        <div class="space-y-4">
            @foreach ($this->default_fields as $field)
            <div>
                <label>{{ $field->label }}</label>
                <input type="text" wire:model="data.{{$field->name}}" class="block w-full p-4 rounded-lg border border-gray-300" required="{{ $field->is_required ? 'true' : 'false' }}">
            </div>
            @endforeach
        </div>
        <button type="submit" class="block w-full bg-purple-600 p-4 rounded-lg text-white mt-8 hover:bg-purple-500">Generate text</button>
    </form>

    @if (!empty($responses))
    <div class="space-y-4 mt-8">
        @foreach ($responses as $response)
        <div class="bg-purple-50 border border-purple-100 rounded-lg text-purple-800 p-4">
            {{ $response }}
        </div>
        @endforeach
    </div>
    @endif
</div>
