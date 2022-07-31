<nav x-data="{ open: false }" class="bg-white">
    <!-- Primary Navigation Menu -->
    <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-assets.logo class="block h-10 w-10 text-gray-500" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-links.nav-link href="{{ route('dashboard') }}" active="{{ request()->routeIs('dashboard') }}">
                        {{ __('Dashboard') }}
                    </x-links.nav-link>

                    <x-links.nav-link href="{{ route('enrollments.index') }}" active="{{ request()->routeIs('enrollments.index') }}">
                        {{ __('Enrollments') }}
                    </x-links.nav-link>

                    <x-links.nav-link href="{{ route('billing.index') }}" active="{{ request()->routeIs('billing.index') }}">
                        {{ __('Billing') }}
                    </x-links.nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <x-misc.dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-200 ease-in-out">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ml-1">
                                <x-assets.icons.chevron-down class="h-5 w-50" />
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <!-- Account Management -->
                        <div class="block px-4 py-2 text-xs text-gray-500">
                            Manage Account
                        </div>

                        <x-misc.dropdown-link href="{{ route('profile.show') }}">Profile</x-misc.dropdown-link>

                        <div class="border-t border-gray-100"></div>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-misc.dropdown-link href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-misc.dropdown-link>
                        </form>
                    </x-slot>
                </x-misc.dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-200 ease-in-out">
                    <x-assets.icons.menu class="h-5 w-5" x-show="! open" />
                    <x-assets.icons.x class="h-5 w-5" x-show="open" />
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-links.responsive-nav-link href="{{ route('dashboard') }}" active="{{ request()->routeIs('dashboard') }}">
                {{ __('Dashboard') }}
            </x-links.responsive-nav-link>

            <x-links.responsive-nav-link href="{{ route('enrollments.index') }}" active="{{ request()->routeIs('enrollments.index') }}">
                {{ __('Enrollments') }}
            </x-links.responsive-nav-link>

            <x-links.responsive-nav-link href="{{ route('billing.index') }}" active="{{ request()->routeIs('billing.index') }}">
                {{ __('Billing') }}
            </x-links.responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-links.responsive-nav-link href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-links.responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
