@extends('layouts.admin')

@section('content')

<h1>{{ $category->name }}</h1>

@foreach($category->products as $product)

    <h3>{{ $product->name }}</h3>

    @forelse($product->auctions as $auction)

        <div>
            <strong>Auction #{{ $auction->id }}</strong><br>

            Starting Bid:
            ${{ $auction->starting_bid }}

            <br>

            Ticket Cost:
            ${{ $auction->ticket_cost }}

            <br>

            Status:
            {{ $auction->status }}

            <br>

            <a href="{{ route('admin.auctions.show', $auction) }}">
                View Auction
            </a>
        </div>

        <hr>

    @empty

        <p>No auctions for this product.</p>

    @endforelse

@endforeach

@endsection
