<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="flex min-h-screen bg-white dark:bg-zinc-800 ">

        <flux:sidebar sticky
            collapsible="mobile"
            class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.header>
                <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />
                <flux:sidebar.collapse class="lg:hidden" />
            </flux:sidebar.header>
            <p class="font-bold text-orange-600">Ticket Count: {{ auth()->user()->wallet->balance }} </p>

            <flux:sidebar.nav>
                <flux:sidebar.group :heading="__('Platform')" class="grid">

        {{-- ========================================= --}}
        {{-- VENDOR SIDEBAR                            --}}
        {{-- ========================================= --}}
             @if(auth()->user()->hasRole('vendor'))

            <flux:sidebar.item
                icon="home"
                :href="route('vendor.dashboard')"
                :current="request()->routeIs('vendor.dashboard')"
                wire:navigate
            >
                Vendor Dashboard
            </flux:sidebar.item>

            <flux:sidebar.item
                icon="shopping-bag"
                :href="route('vendor.products.index')"
                :current="request()->routeIs('vendor.products.*')"
                wire:navigate
            >
                My Products
            </flux:sidebar.item>

            <flux:sidebar.item
                icon="home"
                :href="route('vendor.auctions.index')"
                :current="request()->routeIs('vendor.auctions.*')"
                wire:navigate
            >
                My Auctions
            </flux:sidebar.item>

            <flux:sidebar.item
                icon="wallet"
                :href="route('vendor.wallets.index')"
                :current="request()->routeIs('vendor.wallets.*')"
                wire:navigate
            >
                Wallet
            </flux:sidebar.item>
        {{-- ========================================= --}}
        {{-- ADMIN SIDEBAR                             --}}
        {{-- ========================================= --}}

        @elseif(auth()->user()->hasRole('admin'))

            <flux:sidebar.item
                icon="home"
                :href="route('admin.dashboard')"
                :current="request()->routeIs('admin.dashboard')"
                wire:navigate
            >
                Admin Dashboard
            </flux:sidebar.item>

            <flux:sidebar.item
                icon="users"
                :href="route('admin.users.index')"
                :current="request()->routeIs('admin.users.*')"
                wire:navigate
            >
                Users
            </flux:sidebar.item>

            <flux:sidebar.item
                icon="chat-bubble-left-right"
                :href="route('admin.comments.index')"
                :current="request()->routeIs('admin.comments.*')"
                wire:navigate
            >
                Comments
            </flux:sidebar.item>

            <flux:sidebar.item
                icon="wallet"
                :href="route('admin.wallets.index')"
                :current="request()->routeIs('admin.wallets.*')"
                wire:navigate
            >
                Wallets
            </flux:sidebar.item>


        {{-- ========================================= --}}
        {{-- BASE / CUSTOMER SIDEBAR                   --}}
        {{-- ========================================= --}}

        @else

            <flux:sidebar.item
                icon="home"
                :href="route('base.dashboard')"
                :current="request()->routeIs('base.dashboard')"
                wire:navigate
            >
                Base Dashboard
            </flux:sidebar.item>

            <flux:sidebar.item
                icon="home"
                :href="route('base.auctions.index')"
                :current="request()->routeIs('base.auctions.*')"
                wire:navigate
            >
                Active Auctions
            </flux:sidebar.item>

            <flux:sidebar.item
                icon="home"
                :href="route('base.wallets.index')"
                :current="request()->routeIs('base.wallets.*')"
                wire:navigate
            >
                Tickets
            </flux:sidebar.item>

            <flux:sidebar.item
                icon="home"
                :href="route('base.comments.index')"
                :current="request()->routeIs('base.comments.*')"
                wire:navigate
            >
                Comments
            </flux:sidebar.item>

                        <flux:sidebar.item
                icon="home"
                :href="route('base.comments.index')"
                :current="request()->routeIs('base.comments.*')"
                wire:navigate
            >
                Recipts
            </flux:sidebar.item>

        @endif
        {{-- -}
                    <flux:sidebar.item icon="home" :href="route('base.dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                        {{ __('Base Dashboard') }} <!--inteded to navigate to Admin View-->

                    </flux:sidebar.item>

                 @if(auth()->user()->roles->contains('name', 'admin'))
                    <flux:sidebar.item
                        icon="shield-check"
                        :href="route('admin.dashboard')"
                    >
                        Admin Panel
                    </flux:sidebar.item>
                @endif

@if(auth()->user()->roles->contains('name', 'vendor', 'admin'))
    <flux:sidebar.item
        icon="shield-check"
        :href="route('vendor.products.index')"
    >
        Sales Management
    </flux:sidebar.item>
@endif
                    <flux:sidebar.item icon="home" :href="route('admin.comments.index')" :current="request()->routeIs('base.comment')" wire:navigate> <!--Need: Check again later-->
                        {{ __('Comment') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="home" :href="route('admin.wallets.index')" :current="request()->routeIs('wallet.index')" wire:navigate>
                        {{ __('Tickets') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="home" :href="route('dashboard')" :current="request()->routeIs('vendor.dashboard')" wire:navigate>
                        {{ __('Vendor Dashboard') }}
                    </flux:sidebar.item>
                     <flux:sidebar.item icon="home" :href="route('base.auctions.index')" :current="request()->routeIs('base.auctions.index')" wire:navigate> <!--Need: Check again later-->
                        {{ __('Active Auctions') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="users" :href="route('admin.users.index')">
                        Users
                    </flux:sidebar.item>

                    --}}
                </flux:sidebar.group>
            </flux:sidebar.nav>

            <flux:spacer />

            <flux:sidebar.nav>
                <flux:sidebar.item icon="folder-git-2" href="https://github.com/laravel/livewire-starter-kit" target="_blank">
                    {{ __('Repository') }}
                </flux:sidebar.item>

                <flux:sidebar.item icon="book-open-text" href="https://laravel.com/docs/starter-kits#livewire" target="_blank">
                    {{ __('Documentation') }}
                </flux:sidebar.item>
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf

                        <flux:sidebar.item
                            as="button"
                            type="submit"
                            icon="arrow-right-start-on-rectangle"
                            class="w-full cursor-pointer"
                        >
                            {{ __('Log Out') }}
                        </flux:sidebar.item>
                    </form>
            </flux:sidebar.nav>

            <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name" />
        </flux:sidebar>


        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <flux:avatar
                                    :name="auth()->user()->name"
                                    :initials="auth()->user()->initials()"
                                />

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                                    <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                            {{ __('Settings') }}
                        </flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item
                            as="button"
                            type="submit"
                            icon="arrow-right-start-on-rectangle"
                            class="w-full cursor-pointer"
                            data-test="logout-button"
                        >
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @fluxScripts
    </body>

</html>
