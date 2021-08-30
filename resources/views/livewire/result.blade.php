<div class="bg-white shadow-lg border border-gray-300 p-4 rounded-lg lg:p-8">
    <div class="flex justify-between items-center">
        <div class="flex items-start justify-start">
            <span class="h-8 w-8 flex-shrink-0 mr-4 bg-lightBlue-800 flex items-center justify-center rounded-full text-white font-bold text-sm">{{ $i + 1 }}</span>
            <p class="font-bold text-gray-900" id="{{ $id_ }}">
                {!! nl2br(str_replace('<', '&lt;', str_replace('>', '&gt;', $response))) !!}
            </p>
        </div>
        <div class="flex-shrink-0 ml-4">
            <button type="button" wire:click="save()" onclick="updateTooltip(this)" class="p-4 focus:outline-none {{ $isSaved ? 'text-lightBlue-700' : 'text-gray-400' }}" data-tooltip="{{ $isSaved ? __('app.remove_from_library') : __('app.save_to_library') }}" data-action-tooltip="{{ $isSaved ? __('app.removed') : __('app.saved') }}">
                @svg('eos-bookmark', 'h-6 w-6')
            </button>
            <button type="button" class="text-lightBlue-800 p-4 focus:outline-none" data-tooltip="{{ __('app.click_copy') }}" data-action-tooltip="{{ __('app.copied') }}" onclick="copy('{{ $id_ }}', this)">
                @svg('eos-content-copy', 'w-6 h-6')
            </button>
        </div>
    </div>

    @if (!empty($actions))
    <div class="mt-4 relative">
        <div wire:loading wire:target="perform" class="text-center text-gray-700 text-lg w-full">
            @lang('app.performing_action')
        </div>
        <ul class="flex flex-wrap justify-center" wire:loading.remove wire:target="perform">
            @foreach ($actions as $action => $label)
            <li class="flex-shrink-0 p-2">
                <button
                    type="button"
                    class="flex items-center text-center justify-center w-full rounded bg-white border border-gray-300 px-4 py-2 text-gray-700 hover:bg-gray-50"
                    wire:click="perform('{{ $action }}')"
                >
                    <img class="h-4 w-4 object-fit mr-3" src="{{ $icon }}" alt="">
                    {{ $label }}
                </button>
            </li>
            @endforeach
        </ul>
    </div>
    @endif
</div>
