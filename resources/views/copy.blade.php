<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $service->name ?? __('Kontematik') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto grid grid-cols-1 gap-8 sm:px-6 md:grid-cols-3 lg:grid-cols-4 lg:px-8">
            <aside class="bg-white overflow-hidden shadow-sm p-8 sm:rounded-lg">
                <nav class="space-y-8">
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
                </nav>
            </aside>
            <div class="bg-white overflow-hidden shadow-sm p-8 sm:rounded-lg md:col-span-2 lg:col-span-3">
                <livewire:copywriter :service="$service" />
            </div>
        </div>
    </div>
</x-app-layout>
