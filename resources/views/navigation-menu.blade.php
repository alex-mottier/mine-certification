<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" wire:navigate>
                        <x-application-mark class="block h-9 w-auto" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link href="{{ route('home') }}" wire:navigate :active="request()->routeIs('home')">
                        {{ __('Home') }}
                    </x-nav-link>
                    @if(\Illuminate\Support\Facades\Auth::user()?->isAdmin())
                        <x-nav-link href="{{ route('users') }}" wire:navigate :active="request()->routeIs('users')">
                            {{ __('Users') }}
                        </x-nav-link>
                    @endif
                    @auth
                        <form method="POST" action="{{ route('logout') }}" class="flex justify-end w-[70rem] mr-24" x-data>
                            @csrf

                            <x-nav-link href="{{ route('logout') }}"
                                             @click.prevent="$root.submit();">
                                {{ __('Log Out') }}
                            </x-nav-link>
                        </form>
                    @else
                        <div class="flex justify-end w-[70rem] mr-24">
                            <x-nav-link href="{{ route('login') }}" wire:navigate :active="request()->routeIs('login')">
                                {{ __('Login') }}
                            </x-nav-link>
                            <x-nav-link href="{{ route('register') }}" wire:navigate :active="request()->routeIs('register')">
                                {{ __('Register') }}
                            </x-nav-link>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</nav>
