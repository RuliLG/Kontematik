<div class="flex items-center justify-between space-x-4">
    <!-- Repo name and link -->
    <div class="min-w-0 space-y-3">
        <div class="flex items-center space-x-3">
            <button type="button" class="h-4 w-4 {{ $service->is_enabled ? 'bg-green-100' : 'bg-gray-100' }} rounded-full flex items-center justify-center" aria-hidden="true" wire:click="toggleEnabled">
                <span class="h-2 w-2 {{ $service->is_enabled ? 'bg-green-400' : 'bg-gray-400' }} rounded-full"></span>
            </button>

            <span class="block">
                <h2 class="text-sm font-medium">
                    <a href="{{ route('admin.service', ['service' => $service->slug]) }}">
                        {{ $service->name }}
                    </a>
                </h2>
            </span>
        </div>
        <a href="{{ route('admin.service', ['service' => $service->slug]) }}" class="relative group flex items-center space-x-2.5">
            <span class="pl-7 text-sm text-gray-500 group-hover:text-gray-900 font-medium truncate">
                {{ $service->category->name }}
            </span>
        </a>
    </div>
    <div class="sm:hidden">
        <!-- Heroicon name: solid/chevron-right -->
        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
        </svg>
    </div>
    <!-- Repo meta info -->
    <div class="hidden sm:flex flex-col flex-shrink-0 items-end space-y-3">
        <p class="flex items-center space-x-4">
            <span class="relative text-sm text-gray-500 hover:text-gray-900 font-medium">
                {{ $service->is_popular ? 'Featured' : '' }}
            </span>
            <button class="relative bg-white rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-lightBlue-500" type="button" wire:click="togglePopular">
                <svg class="{{ $service->is_popular ? 'text-yellow-300 hover:text-yellow-400' : 'text-gray-400 hover:text-gray-300' }} h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
            </button>
        </p>
    </div>
</div>
