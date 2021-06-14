<x-integrations-layout>
    <div class="h-screen flex justify-center items-center">
        <div class="w-full bg-white rounded-lg shadow-xl p-4 max-w-xl md:p-8">
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/3/3f/HubSpot_Logo.svg/800px-HubSpot_Logo.svg.png" alt="Hubspot Logo" class="h-16 w-auto block mx-auto">
            @if ($token === null)
            <p class="text-lg text-gray-700 text-center mt-8">Please, log in to your Hubspot account and grant access to Kontematik to start working with your favorite copywriting assistant</p>
            <form action="{{ route('hubspot') }}" method="POST">
                @csrf
                <button type="submit" class="block mx-auto mt-8 px-8 py-4 text-lg font-semibold text-center rounded  text-white" style="background-color: #FE7A59;">Grant access</button>
            </form>
            @elseif ($token === false)
            <p class="text-lg text-gray-700 text-center mt-8">Could not authenticate with OAuth</p>
            <form action="{{ route('hubspot') }}" method="POST">
                @csrf
                <button type="submit" class="block mx-auto mt-8 px-8 py-4 text-lg font-semibold text-center rounded  text-white" style="background-color: #FE7A59;">Try again</button>
            </form>
            @elseif ($token)
            <p class="text-lg text-gray-700 text-center mt-8">Done! Please, open your Kontematik extension to start using it with Hubspot!</p>
            <meta name="kontematik-refresh-token" content="{{ $token['refresh_token'] }}">
            <meta name="kontematik-access-token" content="{{ $token['access_token'] }}">
            @endif
        </div>
    </div>
</x-integrations-layout>
