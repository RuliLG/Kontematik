<div>
    <div class="py-6 px-4 sm:p-6 lg:pb-8">
        <div>
            <h2 class="text-lg leading-6 font-medium text-gray-900">@lang('app.profile')</h2>
            <p class="mt-1 text-sm text-gray-500">
                @lang('app.profile_subtitle')
            </p>
        </div>

        <div class="mt-6 flex flex-col lg:flex-row">
            <div class="flex-grow space-y-6">
                <div>
                    <span class="block text-sm font-medium text-gray-700">
                        @lang('common.email')
                    </span>
                    <div class="mt-1 rounded-md shadow-sm flex">
                        <span class="w-full bg-gray-50 border border-gray-300 rounded-md p-3 inline-flex items-center text-gray-500 sm:text-sm">
                            {{ $user->email }}
                        </span>
                    </div>
                </div>

                <div>
                    <label for="about" class="block text-sm font-medium text-gray-700">
                        @lang('app.about')
                    </label>
                    <div class="mt-1">
                        <textarea wire:model="user.about" id="about" name="about" rows="3" class="shadow-sm focus:ring-lightBlue-500 focus:border-lightBlue-500 mt-1 block w-full sm:text-sm border-gray-300 rounded-md"></textarea>
                    </div>
                    <p class="mt-2 text-sm text-gray-500">
                        @lang('app.about_help')
                    </p>
                </div>
            </div>

            <div class="mt-6 flex-grow lg:mt-0 lg:ml-6 lg:flex-grow-0 lg:flex-shrink-0">
                <p class="text-sm font-medium text-gray-700" aria-hidden="true">
                    @lang('app.photo')
                </p>
                <div class="mt-1 lg:hidden">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 inline-block rounded-full overflow-hidden h-12 w-12" aria-hidden="true">
                            <img class="object-cover object-center rounded-full h-full w-full" src="{{ $this->photo_url }}" alt="">
                        </div>
                        <div class="ml-5 rounded-md shadow-sm">
                            <div class="group relative border border-gray-300 rounded-md py-2 px-3 flex items-center justify-center hover:bg-gray-50 focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-lightBlue-500">
                                <label for="user_photo" class="relative text-sm leading-4 font-medium text-gray-700 pointer-events-none">
                                    <span>@lang('common.change')</span>
                                    <span class="sr-only">@lang('app.photo')</span>
                                </label>
                                <input id="user_photo" wire:model="photo" name="user_photo" type="file" accept="image/jpeg, image/png" class="absolute w-full h-full opacity-0 cursor-pointer border-gray-300 rounded-md">
                                @error('photo')<span class="text-red-600 text-sm font-medium">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="hidden relative rounded-full overflow-hidden lg:block">
                    <img class="object-cover object-center relative rounded-full w-40 h-40" src="{{ $this->photo_url }}" alt="">
                    <label for="user-photo" class="absolute inset-0 w-full h-full bg-black bg-opacity-75 flex items-center justify-center text-sm font-medium text-white opacity-0 hover:opacity-100 focus-within:opacity-100">
                        <span>@lang('common.change')</span>
                        <span class="sr-only">@lang('app.photo')</span>
                        <input type="file" id="user-photo" wire:model="photo" accept="image/jpeg, image/png" name="user-photo" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer border-gray-300 rounded-md">
                        @error('photo')<span class="text-red-600 text-sm font-medium">{{ $message }}</span>@enderror
                    </label>
                </div>
            </div>
        </div>

        <div class="mt-6 grid grid-cols-12 gap-6">
            <div class="col-span-12 sm:col-span-4">
                <label for="first_name" class="block text-sm font-medium text-gray-700">@lang('app.full_name')</label>
                <input type="text" wire:model="user.name" name="first_name" id="first_name" autocomplete="given-name" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-lightBlue-500 focus:border-lightBlue-500 sm:text-sm">
            </div>

            <div class="col-span-12 sm:col-span-4">
                <label for="preferred_language" class="block text-sm font-medium text-gray-700">@lang('app.language')</label>
                <select wire:model="user.preferred_language" name="preferred_language" id="preferred_language" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-lightBlue-500 focus:border-lightBlue-500 sm:text-sm">
                    <option value="es">Español</option>
                    <option value="en">English</option>
                </select>
            </div>

            <div class="col-span-12 sm:col-span-4">
                <label for="company" class="block text-sm font-medium text-gray-700">@lang('app.company')</label>
                <input type="text" wire:model="user.company" name="company" id="company" autocomplete="organization" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-lightBlue-500 focus:border-lightBlue-500 sm:text-sm">
            </div>
        </div>
    </div>

    <!-- Privacy section -->
    <div class="pt-6 divide-y divide-gray-200">
        <div class="px-4 sm:px-6">
            <div>
                <h2 class="text-lg leading-6 font-medium text-gray-900">@lang('app.notifications')</h2>
                <p class="mt-1 text-sm text-gray-500">
                    @lang('app.notifications_subtitle')
                </p>
            </div>
            <ul class="mt-2 divide-y divide-gray-200">
                <li class="py-4 flex items-center justify-between">
                    <div class="flex flex-col">
                        <p class="text-sm font-medium text-gray-900" id="privacy-option-1-label">
                            @lang('app.notify_about_new_tools')
                        </p>
                        <p class="text-sm text-gray-500" id="privacy-option-1-description">
                            @lang('app.notify_about_new_tools_subtitle')
                        </p>
                    </div>

                    <button type="button" wire:click="toggle('notify_new_tools')" class="{{ $notifyNewTools ? 'bg-lightBlue-500' : 'bg-gray-200' }} ml-4 relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-lightBlue-500" role="switch" aria-checked="true" aria-labelledby="privacy-option-1-label" aria-describedby="privacy-option-1-description">
                        <span class="sr-only">Notify me</span>
                        <span aria-hidden="true" class="{{ $notifyNewTools ? 'translate-x-5' : 'translate-x-0' }} inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200"></span>
                    </button>
                </li>
                <li class="py-4 flex items-center justify-between">
                    <div class="flex flex-col">
                        <p class="text-sm font-medium text-gray-900" id="privacy-option-2-label">
                            @lang('app.notify_about_new_products')
                        </p>
                        <p class="text-sm text-gray-500" id="privacy-option-2-description">
                            @lang('app.notify_about_new_products_subtitle')
                        </p>
                    </div>

                    <button type="button" wire:click="toggle('notify_new_products')" class="{{ $notifyNewProducts ? 'bg-lightBlue-500' : 'bg-gray-200' }} ml-4 relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-lightBlue-500" role="switch" aria-checked="false" aria-labelledby="privacy-option-2-label" aria-describedby="privacy-option-2-description">
                        <span class="sr-only">Notify me</span>
                        <span aria-hidden="true" class="{{ $notifyNewProducts ? 'translate-x-5' : 'translate-x-0' }} inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200"></span>
                    </button>
                </li>
            </ul>
        </div>
        @if ($state === 'saved')
        <div class="mt-8 p-4">
            <div class="rounded-md bg-green-50 p-4 mt-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">
                            @lang('app.profile_updated')
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
            <button type="button" wire:click="save" class="ml-5 bg-lightBlue-700 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-lightBlue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-lightBlue-500">
                @lang('common.save')
            </button>
        </div>
    </div>

    <!-- Danger section -->
    <div class="pt-6 divide-y divide-gray-200">
        <div class="p-4 sm:p-6">
            <div class="rounded-lg border border-red-600 p-4 sm:p-6">
                <div>
                    <h2 class="text-lg leading-6 font-medium text-gray-900">@lang('app.danger_zone')</h2>
                    <p class="mt-1 text-sm text-gray-500">
                        @lang('app.danger_zone_subtitle')
                    </p>
                </div>
                <ul class="mt-2 divide-y divide-gray-200">
                    <li class="py-4 flex items-center justify-between">
                        <div class="flex flex-col">
                            <p class="text-sm font-medium text-gray-900" id="privacy-option-1-label">
                                @lang('app.delete_my_account')
                            </p>
                            <p class="text-sm text-gray-500" id="privacy-option-1-description">
                                @lang('app.delete_my_account_subtitle')
                            </p>
                        </div>

                        @if ($confirmDeletion)
                        <div class="flex items-start">
                            <div class="flex-1">
                                <input type="text" wire:model="deletionInput" class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm">
                                <span class="block mt-1 text-sm text-gray-600">{!! __('app.type_to_confirm', ['key' => $deletionKey]) !!}</span>
                            </div>
                            @if ($deletionInput === $deletionKey)
                            <button type="button" wire:click="deleteUser" class="flex-shrink-0 ml-5 bg-red-700 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                @lang('app.permanently_delete')
                            </button>
                            @else
                            <span class="flex-shrink-0 ml-5 bg-gray-700 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white cursor-not-allowed">
                                @lang('app.permanently_delete')
                            </span>
                            @endif
                        </div>
                        @else
                        <button type="button" wire:click="askForDeletion" class="ml-5 bg-red-700 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            @lang('app.delete_account')
                        </button>
                        @endif
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
