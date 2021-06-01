<a href="{{ route($route) }}" class="{{ request()->routeIs($route) ? 'bg-gradient-to-r from-lightBlue-200 to-pink-200 border-lightBlue-500 group border-l-4 px-3 py-2 flex items-center text-sm font-medium text-lightBlue-700' : 'border-transparent text-gray-900 hover:bg-gray-50 hover:text-gray-900' }} group border-l-4 px-3 py-2 flex items-center text-sm font-medium" aria-current="page">
    <!--
        Heroicon name: outline/user-circle

        Current: "text-teal-500 group-hover:text-teal-500", Default: "text-gray-400 group-hover:text-gray-500"
    -->
    {{ $slot }}
    <span class="truncate">
        {{ $label }}
    </span>
</a>
