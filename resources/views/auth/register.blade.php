<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </x-slot>

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('register') }}" class="grid grid-cols-1 gap-4 md:grid-cols-2">
            @csrf

            <!-- Name -->
            <div class="md:col-span-2">
                <x-label for="name" :value="__('app.full_name')" />

                <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
            </div>

            <!-- Email Address -->
            <div class="md:col-span-2">
                <x-label for="email" :value="__('common.email')" />

                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
            </div>

            <!-- Password -->
            <div>
                <x-label for="password" :value="__('common.password')" />

                <x-input id="password" class="block mt-1 w-full"
                                type="password"
                                name="password"
                                required autocomplete="new-password" />
            </div>

            <!-- Confirm Password -->
            <div>
                <x-label for="password_confirmation" :value="__('common.confirm_password')" />

                <x-input id="password_confirmation" class="block mt-1 w-full"
                                type="password"
                                name="password_confirmation" required />
            </div>

            <div class="md:col-span-2">
                <p class="block font-medium text-sm text-gray-700">
                    {{ (new Translation())->getOrTranslate('Which use cases would you be interested in using?') }}
                </p>
                <div class="grid grid-cols-1 gap-4 mt-4 md:grid-cols-2">
                    @foreach ($niches as $niche)
                    <label class="w-full flex items-center justify-start">
                        <input type="checkbox" class="h-6 w-6 text-purple-600 rounded border border-gray-300" name="niche[{{ $niche->id }}]" value="1" {{ old('niche.' . $niche->id) ? 'checked' : '' }}>
                        <span class="ml-4 flex-1 text-gray-700 text-sm">{{ (new Translation())->getOrTranslate($niche->name) }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <div class="border-t border-gray-300 pt-4 mt-4 md:col-span-2">
                <label class="w-full flex items-center justify-start">
                    <input type="checkbox" class="h-6 w-6 text-purple-600 rounded border border-gray-300" name="accepts" value="1" required>
                    <span class="ml-4 flex-1 text-gray-700 text-sm">I've read and agree with the <a href="https://www.notion.so/kontematik/Legal-Notice-aecc535d36f34f4c86b5f1ea8db726cc" target="_blank" rel="noopener noreferrer" class="text-pink-600 hover:underline">terms of use</a> and <a href="https://www.notion.so/kontematik/Privacy-Policy-ae9c48c4b4864a8a803157d466e1feba" target="_blank" rel="noopener noreferrer" class="text-pink-600 hover:underline">privacy policy</a></span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4 md:col-span-2">
                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                    {{ (new Translation())->getOrTranslate('Already registered?') }}
                </a>

                <x-button class="ml-4">
                    {{ (new Translation())->getOrTranslate('Register') }}
                </x-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
