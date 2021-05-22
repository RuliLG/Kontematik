<div class="tooltip-parent">
    <input type="search" wire:model="query" class="block bg-transparent py-6 px-0 text-lg text-gray-700 border-t-0 border-l-0 border-r-0 border-b-2 border-dashed border-gray-300 rounded-none w-full focus:outline-none focus:ring-0" placeholder="{{ __('Search') }}" autofocus>
    <div wire:loading.delay="100" class="text-gray-400 font-thin text-left text-4xl mt-24">
        {{ __('Loading...') }}
    </div>

    <div wire:loading.remove wire:target="query" class="mt-8">
        @if (!$results->isEmpty())
        <ul class="flex flex-wrap -mx-4">
            @foreach ($results as $i => $result)
            <li class="p-4 w-full">
                <div class="bg-white shadow-lg border border-gray-300 p-4 rounded-lg flex justify-between items-center lg:p-8">
                    <div class="flex items-start justify-start">
                        <span class="h-8 w-8 flex-shrink-0 mr-4 bg-lightBlue-800 flex items-center justify-center rounded-full text-white font-bold text-sm">{{ $i + 1 }}</span>
                        <p class="font-bold text-gray-900" id="result_{{ $i }}">{{ $result->output }}</p>
                    </div>
                    <div class="flex-shrink-0 ml-4">
                        <button type="button" wire:click="removeResult({{ $result->id }})" onclick="updateTooltip(this)" class="text-lightBlue-800 p-4 focus:outline-none" data-tooltip="{{ __('Remove from your library') }}" data-action-tooltip="{{ __('Removed!') }}">
                            @svg('eos-bookmark', 'w-6 h-6')
                        </button>
                        <button type="button" class="text-lightBlue-800 p-4 focus:outline-none" data-tooltip="{{ __('Copy to clipboard') }}" data-action-tooltip="{{ __('Copied!') }}" onclick="copy('result_{{ $i }}', this)">
                            @svg('eos-content-copy', 'w-6 h-6')
                        </button>
                    </div>
                </div>
            </li>
            @endforeach
        </ul>
        @else
        <div class="text-gray-400 font-thin text-left text-4xl mt-24">
            {{ __('Oops! We didn\'t find any saved result for your search') }}
        </div>
        @endif
    </div>
</div>
