<nav>
    @if ($layout === 'list')
    <input type="search" wire:model="query" class="block border-gray-300 rounded mb-8 w-full" placeholder="Search tool" autofocus>
    <div wire:loading.delay="100" class="text-gray-700 font-bold">
        {{ __('Loading...') }}
    </div>
    <div wire:loading.remove class="space-y-8">
        @foreach ($categories as $category)
        <div>
            <h2 class="font-bold">{{ $category->name }}</h2>
            <ul>
                @foreach ($category->services as $s)
                <li>
                    <a href="{{ route('tool', ['service' => $s->slug]) }}" class="block px-4 py-2 hover:text-purple-600">{{ $s->name }}</a>
                </li>
                @endforeach
            </ul>
        </div>
        @endforeach
        @if ($categories->isEmpty())
        <div class="text-gray-700 font-bold">
            {{ __('Oops! We didn\'t find any tool for your search') }}
        </div>
        @endif
    </div>
    @elseif ($layout === 'grid')
    <input type="search" wire:model="query" class="block bg-transparent py-6 px-0 text-lg text-gray-700 border-t-0 border-l-0 border-r-0 border-b-2 border-dashed border-gray-300 rounded-none w-full focus:outline-none focus:ring-0" placeholder="{{ __('Write here to find a tool (e.g. ecommerce)') }}" autofocus>
    <div wire:loading.delay="100" class="text-gray-400 font-thin text-left text-4xl mt-24">
        {{ __('Loading...') }}
    </div>
        @if ($query)
        <div wire:loading.remove class="mt-8">
            @if (!$categories->isEmpty())
            <ul class="flex flex-wrap -mx-4">
                @foreach ($categories as $category)
                    @foreach ($category->services as $s)
                    <li class="p-4 w-full sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/5">
                        <a
                            href="{{ route('tool', ['service' => $s->slug]) }}"
                            class="text-center block px-4 py-8 rounded-xl bg-{{ $s->tw_color }}-100 text-{{ $s->tw_color }}-800 hover:bg-{{ $s->tw_color }}-50 transition duration-150"
                        >
                            @svg($s->icon_name, 'h-12 mx-auto mb-4')
                            <span class="font-bold text-sm">{{ $s->name }}</span>
                        </a>
                    </li>
                    @endforeach
                @endforeach
            </ul>
            @else
            <div class="text-gray-400 font-thin text-left text-4xl mt-24">
                {{ __('Oops! We didn\'t find any tool for your search') }}
            </div>
            @endif
        </div>
        @else
        <div wire:loading.remove class="text-gray-400 font-thin text-left text-4xl mt-24">
            Write to filter through our {{ $this->count }} tools
        </div>
        @endif
    @endif
</nav>
