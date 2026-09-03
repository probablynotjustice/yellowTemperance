<x-layouts::app :title="__('Dashboard')" class="">


<admin-layout>
    <div>
        <a href="{{ route('admin.categories.index') }}">
            ← Back to Categories
        </a>

        <a href="{{ route('admin.categories.edit', $category) }}">
            Edit Category
        </a>
    </div>

    <div class="border p-4 rounded mb-4">

        <h1>{{ $category->name }}</h1>

        @if($category->description)
            <p>{{ $category->description }}</p>
        @endif

    </div>


    <h2>Products & Auctions</h2>


    @forelse($category->products as $product)

        <div class="border p-4 rounded mb-4">

            <h3>
                <a href="{{ route('admin.products.show', $product) }}">
                    {{ $product->name }}
                </a>
            </h3>

            <p>
                {{ $product->description }}
            </p>

            <p>
                Vendor:
                {{ $product->vendor->name }}
            </p>


            <h4>Auctions</h4>

            @forelse($product->auctions as $auction)

                <div class="border p-3 rounded mb-2">

                    <h5>
                        Auction #{{ $auction->id }}
                    </h5>

                    <p>
                        Status:
                        {{ $auction->status }}
                    </p>

                    <p>
                        Starting Bid:
                        ${{ $auction->starting_bid }}
                    </p>

                    <p>
                        Ticket Cost:
                        ${{ $auction->ticket_cost }}
                    </p>

                    <p>
                        Starts:
                        {{ $auction->starts_at }}
                    </p>

                    <p>
                        Ends:
                        {{ $auction->ends_at }}
                    </p>

                    <a href="{{ route('admin.auctions.show', $auction) }}">
                        View Auction
                    </a>

                </div>

            @empty

                <p>
                    No auctions have been created for this product.
                </p>

            @endforelse

        </div>

    @empty

        <p>
            No products have been assigned to this category.
        </p>

    @endforelse
    </admin-layout>
</x-layouts::app>
