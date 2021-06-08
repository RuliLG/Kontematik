<div>
    <div class="py-6 px-4 sm:p-6 lg:pb-8">
        <div>
            <h2 class="text-lg leading-6 font-medium text-gray-900">Preferences</h2>
            <p class="mt-1 text-sm text-gray-500">
                Introducing your preferences will help us recommend you the best tools for your use-cases.
            </p>
        </div>

        <div class="mt-6 flex flex-col lg:flex-row">
            <div class="flex-grow space-y-6">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:gap-8 md:grid-cols-3 lg:grid-cols-4">
                    @foreach ($niches as $niche)
                    <label class="w-full flex items-center justify-start">
                        <input type="checkbox" class="h-6 w-6 text-purple-600 rounded border border-gray-300" wire:model="selectedNiches.{{ $niche->id }}">
                        <span class="ml-4 flex-1">{{ $niche->name }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
