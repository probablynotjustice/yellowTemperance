<!DOCTYPE html>
<html>
    <head>
        <!--Nothig here yet-->
    </head>

 <x-admin-sidebar />
<x-layouts::app :title="__('Dashboard')" class="">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <p> This is the admin Dashboard  Without filament</p>
        <p><a href="{{ route('admin.products.index') }}">Manage Products</a></p>
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">

            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                    <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
                    <!--Admin wallet balance Shouldnt exist,
                        Ill make sure of that later-->
                    <button href="/ticketall">
                    <p>Wallet Balance:</p>
                        <span>

                            ${{ $user->wallet?->balance ?? '0.00' }}
                        </span>
                </button>
            </div>
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
        </div>
        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
        </div>
    </div>

    <div>
        <h2>Total Users</h2>
            <p>{{ $stats['users'] }}</p>

            <h2>Products</h2>
            <p>{{ $stats['products'] }}</p>

            <h2>Auctions</h2>
            <p>{{ $stats['auctions'] }}</p>

            <h2>Customers</h2>
            <p>{{ $stats['customers'] }}</p>

            <h2>Vendors</h2>
            <p>{{ $stats['vendors'] }}</p>
    </div>

    <div>
        <h2>Recent Users</h2>
            @foreach ($recentUsers as $user)
                <div>
                    {{ $user->name }}
                    <br>
                    {{ $user->email }}
                </div>
            @endforeach
    </div>

    <div>
        <h2>Recent Products</h2>

            @foreach ($recentProducts as $product)
                <div>
                    {{ $product->name }}

                    @if($product->vendor)
                        <br>
                        Vendor: {{ $product->vendor->name }}
                    @endif
                </div>
            @endforeach
    </div>
</x-layouts::app>


</html>
