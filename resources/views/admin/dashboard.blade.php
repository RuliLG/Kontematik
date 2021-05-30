<x-app-layout bg="bg-gray-100">
    <div class="relative min-h-screen flex flex-col">
        <!-- 3 column wrapper -->
        <div class="flex-grow w-full max-w-7xl mx-auto xl:px-8 lg:flex">
            <!-- Left sidebar & main wrapper -->
            <div class="flex-1 min-w-0 bg-white xl:flex">
                <!-- Account profile -->
                <div class="xl:flex-shrink-0 xl:w-64 xl:border-r xl:border-gray-200 bg-white">
                    <div class="pl-4 pr-6 py-6 sm:pl-6 lg:pl-8 xl:pl-0">
                        <div class="flex items-center justify-between">
                            <div class="flex-1 space-y-8">
                                <div class="space-y-8 sm:space-y-0 sm:flex sm:justify-between sm:items-center xl:block xl:space-y-8">
                                    <!-- Profile -->
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0 h-12 w-12">
                                            <img class="h-12 w-12 rounded-full" src="https://images.unsplash.com/photo-1517365830460-955ce3ccd263?ixlib=rb-1.2.1&ixqx=01KW5UG9Sk&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=256&h=256&q=80" alt="">
                                        </div>
                                        <div class="space-y-1">
                                            <div class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</div>
                                            <span class="text-sm text-gray-500 group-hover:text-gray-900 font-medium">{{ Auth::user()->email }}</span>
                                        </div>
                                    </div>
                                    <!-- Action buttons -->
                                    <div class="flex flex-col sm:flex-row xl:flex-col">
                                        <a href="{{ route('admin.new-service') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-lightBlue-600 hover:bg-lightBlue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-lightBlue-500 xl:w-full">
                                            New Service
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Projects List -->
                <div class="bg-white lg:min-w-0 lg:flex-1">
                    <div class="pl-4 pr-6 pt-4 pb-4 border-b border-t border-gray-200 sm:pl-6 lg:pl-8 xl:pl-6 xl:border-t-0">
                        <div class="flex items-center">
                            <h1 class="flex-1 text-lg font-medium">Services</h1>
                        </div>
                    </div>
                    <ul class="relative z-0 divide-y divide-gray-200 border-b border-gray-200">
                        @foreach ($services as $service)
                        <li class="relative pl-4 pr-6 py-5 hover:bg-gray-50 sm:py-6 sm:pl-6 lg:pl-8 xl:pl-6">
                            @livewire('admin.service-item', ['service' => $service])
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
