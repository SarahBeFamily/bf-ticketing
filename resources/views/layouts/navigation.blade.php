@php
    $unread_nots = !isset($unread_nots) ? Auth::user()->getUnreadNotification() : $unread_nots;
@endphp

<nav x-data="{ open: false }" class="bg-secondary dark:bg-secondary">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    {{-- Voci per team --}}
                    @can('edit users', App\Models\User::class)
                        <x-nav-link :href="route('companies.index')" :active="request()->routeIs('companies.index')">
                            {{ __('Aziende') }}
                        </x-nav-link>

                        <x-nav-link :href="route('customers.index')" :active="request()->routeIs('customers.index')">
                            {{ __('Clienti') }}
                        </x-nav-link>
                    @endcan

                    <x-nav-link :href="route('projects.index')" :active="request()->routeIs('projects.index')">
                        {{ __('Progetti') }}
                    </x-nav-link>

                    <x-nav-link :href="route('tickets.index')" :active="request()->routeIs('tickets.index')">
                        {{ __('Tickets') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <div class="inline-flex items-center">
                                @if (Auth::user()->avatar())
                                    <img class="h-8 w-8 rounded-full" src="{{ Auth::user()->avatar() }}" alt="{{ Auth::user()->name }}">
                                @endif
                                {{ Auth::user()->name }}
                            </div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profilo') }}
                        </x-dropdown-link>

                        <x-dropdown-link :href="route('profile.notifications')">
                            {{ __('Notifiche') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            {{-- Drop down notifiche --}}

            <div class="notification sm:flex sm:items-center">
                
                <x-dropdown align="right" width="96">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <svg version="1.0" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20px" height="20px" viewBox="0 0 64 64" enable-background="new 0 0 64 64" xml:space="preserve" fill="white">
                                <g stroke-width="0"></g>
                                <g stroke-linecap="round" stroke-linejoin="round"></g>
                                <g> <g>
                                    <path fill="white" d="M56,44c-1.832,0-4-2.168-4-4V20C52,8.973,43.027,0,32,0S12,8.973,12,20v20c0,1.793-2.207,4-4,4 c-2.211,0-4,1.789-4,4s1.789,4,4,4h48c2.211,0,4-1.789,4-4S58.211,44,56,44z"></path> <path fill="white" d="M32,64c4.418,0,8-3.582,8-8H24C24,60.418,27.582,64,32,64z"></path>
                                </g> </g></svg>
                            
                            @if ($unread_nots->count() > 0)
                                <span class="notification-counter relative flex h-3 w-3 -mt-5 -ml-2">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-accent-100 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-3 w-3 bg-accent-100 text-white"></span>
                                </span>
                            

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            @endif
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        @if ($unread_nots->count() > 0)
                            @foreach ($unread_nots->reverse() as $notification)
                                <x-dropdown-link :href="route('profile.notifications', $notification)">
                                    {{ $notification->data }}
                                </x-dropdown-link>
                            @endforeach
                        @else
                            <x-dropdown-link>
                                Nessuna notifica non letta
                            </x-dropdown-link>
                        @endif
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        {{-- Voci per team --}}
        @can('edit users', App\Models\User::class)
            <div class="pt-2 pb-3 space-y-1">
                <x-responsive-nav-link :href="route('companies.index')" :active="request()->routeIs('companies.index')">
                    {{ __('Aziende') }}
                </x-responsive-nav-link>
            </div>

            <div class="pt-2 pb-3 space-y-1">
                <x-responsive-nav-link :href="route('customers.index')" :active="request()->routeIs('customers.index')">
                    {{ __('Clienti') }}
                </x-responsive-nav-link>
            </div>
        @endcan
        
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('projects.index')" :active="request()->routeIs('projects.index')">
                {{ __('Progetti') }}
            </x-responsive-nav-link>
        </div>

        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('tickets.index')" :active="request()->routeIs('tickets.index')">
                {{ __('Tickets') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
