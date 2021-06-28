<div>
    <div class="py-6 px-4 sm:p-6 lg:pb-8">
        <div>
            <h2 class="text-lg leading-6 font-medium text-gray-900">Your integrations</h2>
            <p class="mt-1 text-sm text-gray-500">
                Below you can see a list of every app connected to your Kontematik account.
            </p>
        </div>


        @if (session('oauth_error'))
        <div class="rounded-md bg-red-50 p-4 mt-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">We couldn't execute the selected integration</h3>
                    <div class="mt-2 text-sm text-red-700">{{ session('oauth_error') }}</div>
                </div>
            </div>
        </div>
        @endif

        <div class="mt-6 flex flex-col lg:flex-row">
            @if ($activeIntegrations->isEmpty())
            <div class="bg-gray-100 rounded p-8 text-center text-gray-700 w-full">
                Choose one integration from below
            </div>
            @else
            <ul class="grid grid-cols-1 gap-8 lg:grid-cols-2">
                @foreach ($activeIntegrations as $integration)
                <li class="">
                    <div class="h-full relative flex flex-col justify-center items-start w-full bg-white border border-gray-200 rounded-lg p-4 overflow-hidden lg:p-8">
                        <div class="w-full">
                            <img src="{{ $integration->image() }}" alt="{{ $integration->name() }} Logo" class="h-8 w-auto">
                        </div>
                        <div class="flex-1 text-left mt-4 lg:mt-6">
                            <span class="block text-base text-gray-700 font-bold">{{ $integration->details()->getSiteName() }}</span>
                            <span class="block text-sm text-gray-500">User: {{ $integration->details()->getUser() }}</span>
                        </div>
                        <button type="button" wire:click="destroy({{ $integration->getToken()->id }})" class="block px-4 py-2 mt-4 w-full text-center text-gray-600 bg-white border border-gray-300 rounded hover:bg-gray-100 hover:text-gray-700">Remove integration</button>
                    </div>
                </li>
                @endforeach
            </ul>
            @endif
        </div>
    </div>
    <div class="py-6 px-4 sm:p-6 lg:pb-8">
        <div>
            <h2 class="text-lg leading-6 font-medium text-gray-900">Available integrations</h2>
            <p class="mt-1 text-sm text-gray-500">
                Integrate Kontematik with existing tools to help you write faster.
            </p>
        </div>

        <div class="mt-6 flex flex-col lg:flex-row">
            <ul class="grid grid-cols-1 gap-8 lg:grid-cols-2">
                @foreach ($integrations as $integration)
                <li class="">
                    @if ($integration->isAvailable())
                    <a href="{{ $integration->link() }}" class="h-full relative flex flex-col justify-center items-start w-full bg-white border border-gray-200 rounded-lg p-4 hover:bg-gray-100 overflow-hidden lg:p-8">
                    @else
                    <div class="h-full relative flex flex-col justify-center items-start w-full bg-gray-50 border border-gray-200 rounded-lg p-4 cursor-not-allowed opacity-75 overflow-hidden lg:p-8">
                    @endif
                        <div class="w-full">
                            <img src="{{ $integration->image() }}" alt="{{ $integration->name() }} Logo" class="h-12 w-auto block mx-auto">
                        </div>
                        <div class="flex-1 text-center mt-4 lg:mt-6">
                            <span class="block text-sm text-gray-500">{{ $integration->description() }}</span>
                        </div>
                    @if ($integration->isAvailable())
                    </a>
                    @else
                        <span class="absolute top-0 right-0 bg-gray-700 px-2 py-1 text-sm text-white uppercase rounded-bl">Soon</span>
                    </div>
                    @endif
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
