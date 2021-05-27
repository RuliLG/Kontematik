<x-app-layout bg="bg-gray-100">
    <x-slot name="header">
        <div class="text-xl text-lightBlue-100 leading-tight">
            @if (isset($service))
            <div class="space-x-4 flex justify-start items-center">
                <a href="{{ route('dashboard') }}" class="font-semibold flex justify-start items-center hover:text-white">
                    <svg class="w-6 h-6 mr-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path></svg>
                    {{ __('Kontematik') }}
                </a>
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                <h2 class="font-semibold">
                    {{ $service->name }}
                </h2>
            </div>
            @else
            <h2 class="font-semibold">
                {{ __('Kontematik') }}
            </h2>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto grid grid-cols-1 gap-8 sm:px-6 md:grid-cols-3 lg:grid-cols-4 lg:px-8">
            @if (isset($service))
            <aside class="bg-white overflow-hidden shadow-sm p-8 sm:rounded-lg">
                <livewire:service-list />
            </aside>
            <div class="bg-white overflow-hidden shadow-sm p-8 sm:rounded-lg md:col-span-2 lg:col-span-3">
                <livewire:copywriter :service="$service" />
            </div>
            @else
            <div class="md:col-span-3 lg:col-span-4">
                <livewire:service-list layout="grid" />
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
