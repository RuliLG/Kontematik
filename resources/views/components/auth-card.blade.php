<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-tr from-pink-500 to-lightBlue-800">
    <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg" data-aos="fade-up" data-aos-delay="300" data-aos-duration="500">
        <div class="flex justify-center items-center mb-8">
            {{ $logo }}
        </div>

        {{ $slot }}
    </div>
    @if (request()->routeIs('login'))
    <a href="{{ route('register') }}" class="block w-full mx-auto mt-4 text-pink-50 font-medium text-center text-sm sm:max-w-md">Don't have an account yet? Register now</a>
    @endif
</div>
