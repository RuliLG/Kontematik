<nav>
    <input type="search" wire:model="query" class="block border-gray-300 rounded mb-8 w-full" placeholder="Search tool">
    <div wire:loading.delay="100" class="text-gray-700 font-bold">
        Loading...
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
            Oops! We didn't find any tool for your search
        </div>
        @endif
    </div>
</nav>
