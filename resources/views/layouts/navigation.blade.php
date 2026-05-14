<nav x-data="{ open: false }"
    class="bg-white border-b border-gray-100 fixed w-full z-50 top-0 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">

            <div class="flex-shrink-0 flex flex-col justify-center items-start">
                <a href="/"
                    class="font-serif text-2xl font-bold tracking-[0.2em] text-gray-900 uppercase leading-none">
                    Kana Covers
                </a>
            </div>

            <div class="hidden md:flex space-x-8 items-center">
                @if (Auth::User()?->role === 'user')
                    <a href="/calendar"
                        class="text-xs font-bold text-gray-500 hover:text-black transition uppercase tracking-widest">
                        Calendar
                    </a>
                @endif
                
                <a href="{{ route('fabrics.index') }}"
                    class="text-xs font-bold text-gray-500 hover:text-black transition uppercase tracking-widest">
                    Collections
                </a>
                @if (Auth::User()?->role !== 'admin')
                    <a href="{{ route ('shop.reviews')}}"
                        class="text-xs font-bold text-gray-500 hover:text-black transition uppercase tracking-widest">
                        Reviews
                    </a>
                @endif
                <a href="/venues"
                    class="text-xs font-bold text-gray-500 hover:text-black transition uppercase tracking-widest">
                    Venues
                </a>

                @if (Auth::User()?->role === 'admin')
                    <a href="/calendar/admin"
                        class="text-xs font-bold text-gray-500 hover:text-black transition uppercase tracking-widest">
                        Calendar
                    </a>
                    <a href="/admin/product-reviews"
                        class="text-xs font-bold text-gray-500 hover:text-black transition uppercase tracking-widest">
                        Product Reviews
                    </a>

                    <a href="/orders"
                        class="text-xs font-bold text-gray-500 hover:text-black transition uppercase tracking-widest">
                        Orders
                    </a>

                    <a href="/admin/shop-reviews"
                        class="text-xs font-bold text-gray-500 hover:text-black transition uppercase tracking-widest">
                        Shop Reviews
                    </a>

                    <a href="{{ route('admin.venues.index') }}"
                        class="text-xs font-bold text-gray-500 hover:text-black transition uppercase tracking-widest">
                        Manage Venues
                    </a>
                    
                    <a href="{{ route('admin.notifications.broadcast') }}"
                        class="text-xs font-bold text-gray-500 hover:text-black transition uppercase tracking-widest">
                        Broadcast
                    </a>
                @endif
            </div>

            <div class="hidden md:flex items-center space-x-6">

                <a href="{{ route('notifications.index') }}"
                    class="relative text-gray-500 hover:text-black transition p-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                        </path>
                    </svg>

                    @if (Auth::user()?->unreadNotifications()->exists())
                        <span
                            class="absolute top-1 right-1 block h-2.5 w-2.5 rounded-full bg-red-600 ring-2 ring-white animate-pulse"></span>
                    @endif
                </a>

                <a href="{{ route('dashboard') }}"
                    class="text-xs font-bold text-gray-900 hover:text-gray-600 uppercase tracking-wider">
                    Dashboard
                </a>

                <div class="hidden sm:flex sm:items-center">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="inline-flex items-center px-3 py-2 border border-transparent text-xs leading-4 font-bold rounded-md text-white bg-black hover:bg-gray-800 transition ease-in-out duration-150 uppercase tracking-wider">
                                <div>Account</div>
                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile Settings') }}
                            </x-dropdown-link>

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
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden bg-white border-t border-gray-100">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('fabrics.index')">
                {{ __('Collections') }}
            </x-responsive-nav-link>
            @if (Auth::user()?->role === 'user')
                <x-responsive-nav-link href="/calendar">
                    {{ __('Calendar') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('shop.reviews')">
                    {{ __('Reviews') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('notifications.index')">
                    {{ __('Notifications') }}
                </x-responsive-nav-link>
            @endif

            @if (Auth::user()?->role === 'admin')
                <x-responsive-nav-link href="/calendar/admin">
                    {{ __('Calendar (Admin)') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link href="/orders">
                    {{ __('Orders') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link href="/inventory-logs">
                    {{ __('Inventory') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link href="/admin/product-reviews">
                    {{ __('Product Reviews') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link href="/admin/shop-reviews">
                    {{ __('Shop Reviews') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.venues.index')">
                    {{ __('Manage Venues') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.notifications.broadcast')">
                    {{ __('Broadcast') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()?->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()?->email }}</div>
            </div>
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile Settings') }}
                </x-responsive-nav-link>
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
