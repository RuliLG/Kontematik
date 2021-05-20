<nav>
    <input type="search" wire:model="query" class="block border-gray-300 rounded mb-8 w-full" placeholder="Search tool">
    <div wire:loading.delay="100" class="text-gray-700 font-bold">
        Loading...
    </div>
    @if ($layout === 'list')
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
            Oops! We didn't find any tool for your search
        </div>
        @endif
    </div>
    @elseif ($layout === 'grid')
    <div wire:loading.remove>
        @if (!$categories->isEmpty())
        <ul class="flex flex-wrap -mx-4">
            @foreach ($categories as $category)
                @foreach ($category->services as $s)
                <li class="p-4 w-full sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/5">
                    <a
                        href="{{ route('tool', ['service' => $s->slug]) }}"
                        class="text-center block px-4 py-2 rounded-xl bg-{{ $s->tw_color }}-100 text-{{ $s->tw_color }}-800 hover:bg-{{ $s->tw_color }}-50 transition duration-150"
                    >
                        <span class="font-bold text-sm">{{ $s->name }}</span>
                    </a>
                </li>
                @endforeach
            @endforeach
        </ul>
        @else
        <div class="text-gray-700 font-bold">
            Oops! We didn't find any tool for your search
        </div>
        @endif
    </div>
    @endif
</nav>
