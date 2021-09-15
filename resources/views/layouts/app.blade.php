<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @if (Auth::check())
        <meta name="api_token" content="{{ Auth::user()->api_token }}">
        @endif

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
        @livewireStyles
        @stack('styles')

        <!-- Scripts -->
        <!-- Fathom - beautiful, simple website analytics -->
        <script src="https://donkey.kontematik.com/script.js" data-site="STOBJFSB" defer></script>
        <!-- / Fathom -->
        <script src="{{ mix('js/app.js') }}" defer></script>
        <!-- Hotjar Tracking Code for app.kontematik.com -->
        <script>
            (function(h,o,t,j,a,r){
                h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
                h._hjSettings={hjid:2444733,hjsv:6};
                a=o.getElementsByTagName('head')[0];
                r=o.createElement('script');r.async=1;
                r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
                a.appendChild(r);
            })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
        </script>

        <script type='text/javascript'>
            window.$crisp=[];
            window.CRISP_WEBSITE_ID='ccbf9cf8-ef32-4fcb-b08d-e4a818d4ad88';
            (function(){d=document;s=d.createElement('script');s.src='https://client.crisp.chat/l.js';s.async=1;d.getElementsByTagName('head')[0].appendChild(s);})();
        </script>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-white">
            @include('layouts.navigation')

            @if (isset($header))
            <!-- Page Heading -->
            <header class="bg-lightBlue-900 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>

            @if (Auth::check())
            <div class="fixed right-0 left-0 bottom-0 max-w-xl mx-auto px-6 py-6 rounded-xl bg-pink-600 shadow-lg mb-24 md:mr-8 translate-y-20 transition duration-150 hidden" id="kontematik-extension-alert">
                <div class="w-full" role="main" aria-label="description">
                    <h2 role="heading" class="text-lg xl:text-xl text-white font-bold mb-4">@lang('app.extension_prompt_title')</h2>
                    <p role="contentinfo" class="xl:text-lg text-white font-normal leading-7">@lang('app.extension_prompt_text')</p>
                </div>
                <a href="https://chrome.google.com/webstore/detail/kontematik/nkakelmmhcciklmlacojbdbajbadkkmn" target="_blank" rel="noopener noreferrer" aria-label="Download" class="absolute left-0 top-0 w-full h-full"></a>
                <button type="button" class="absolute top-0 right-0 p-2 text-pink-100 hover:text-white close">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="fixed right-0 left-0 bottom-0 max-w-xl mx-auto px-6 py-6 rounded-xl bg-pink-600 shadow-lg mb-24 md:mr-8 translate-y-20 transition duration-150 hidden" id="kontematik-extension-auth-alert">
                <div class="w-full" role="main" aria-label="description">
                    <h2 role="heading" class="text-lg xl:text-xl text-white font-bold mb-4">@lang('app.extension_auth_title')</h2>
                    <p role="contentinfo" class="xl:text-lg text-white font-normal leading-7">@lang('app.extension_auth_text')</p>
                </div>
                <button type="button" class="absolute top-0 right-0 p-2 text-pink-100 hover:text-white close">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            @endif

            @livewireScripts
            @stack('scripts')
        </div>
    </body>
</html>
