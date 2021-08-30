<div>
    <div class="py-6 px-4 sm:p-6 lg:pb-8">
        <div>
            <h2 class="text-lg leading-6 font-medium text-gray-900">@lang('common.password')</h2>
            <p class="mt-1 text-sm text-gray-500">
                @lang('app.password_subtitle')
            </p>
        </div>
        <div class="mt-6 grid grid-cols-12 gap-6">
            <div class="col-span-12 sm:col-span-6">
                <label for="password" class="block text-sm font-medium text-gray-700">@lang('common.password')</label>
                <input type="password" wire:model="password" name="password" id="password" autocomplete="new-password" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-lightBlue-500 focus:border-lightBlue-500 sm:text-sm">
            </div>

            <div class="col-span-12 sm:col-span-6">
                <label for="confirm_password" class="block text-sm font-medium text-gray-700">@lang('common.confirm_password')</label>
                <input type="password" wire:model="confirmPassword" name="confirm_password" id="confirm_password" autocomplete="confirm-password" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-lightBlue-500 focus:border-lightBlue-500 sm:text-sm">
            </div>
        </div>
    </div>

    <!-- Privacy section -->
    <div class="pt-6 divide-y divide-gray-200">
        @if ($state === 'saved')
        <div class="p-4 pt-0">
            <div class="rounded-md bg-green-50 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">
                            @lang('app.password_updated')
                        </p>
                    </div>
                    <div class="ml-auto pl-3">
                        <div class="-mx-1.5 -my-1.5">
                            <button wire:click="resetState" type="button" class="inline-flex bg-green-50 rounded-md p-1.5 text-green-500 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-green-50 focus:ring-green-600">
                                <span class="sr-only">@lang('common.dismiss')</span>
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        <div class="mt-4 py-4 px-4 flex justify-end sm:px-6">
            <a href="{{ route('profile') }}" class="bg-white border border-gray-300 rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-lightBlue-500">
                @lang('common.cancel')
            </a>
            @if (strlen($password) >= 6 && $password === $confirmPassword)
            <button type="button" wire:click="save" class="ml-5 bg-lightBlue-700 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-lightBlue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-lightBlue-500">
                @lang('app.update_password')
            </button>
            @else
            <span class="ml-5 bg-gray-700 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white cursor-not-allowed">
                @lang('app.update_password')
            </span>
            @endif
        </div>
    </div>
</div>
