<x-layouts::app :title="__('Admin Individual User ') " class="">



<div class="p-6">

    {{-- USER HEADER                               --}}


    <div class="mb-6">
        <div class="flex items-center justify-between">

            <div>
                <flux:heading size="xl">
                    {{ $user->name }}
                </flux:heading>

                <flux:text class="mt-1">
                    {{ $user->email }}
                </flux:text>
            </div>

            <a href="{{ route('admin.users.index') }}">
                ← Back to Users
            </a>

        </div>
    </div>



    {{-- WALLET --}}


    <div class="mb-8">

        <flux:heading size="lg" class="mb-4">
            Wallet
        </flux:heading>

        <div class="rounded-xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">

            <div class="flex items-center justify-between">

                <div>
                    <flux:text class="text-sm text-zinc-500">
                        Ticket Balance
                    </flux:text>

                    <div class="mt-1 text-3xl font-bold">
                        {{ $user->wallet->balance ?? 0 }}
                    </div>
                </div>

                <div class="text-4xl">
                    🎟️
                </div>

            </div>

        </div>

    </div>

    {{-- BIDS --}}


    <div class="mb-8">

        <flux:heading size="lg" class="mb-4">
            Bid History
        </flux:heading>

        @if($user->bids->isEmpty())

            <div class="rounded-xl border border-zinc-200 p-6 text-center dark:border-zinc-700">
                <flux:text>
                    This user has not placed any bids.
                </flux:text>
            </div>

        @else

            <div class="overflow-x-auto rounded-xl border border-zinc-200 dark:border-zinc-700">

                <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">

                    <thead class="bg-zinc-50 dark:bg-zinc-800">

                        <tr>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase">
                                Auction
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase">
                                Product
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase">
                                Bid
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase">
                                Tickets
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase">
                                Result
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase">
                                Date
                            </th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">

                        @foreach($user->bids->sortByDesc('created_at') as $bid)

                            @php
                                $auction = $bid->auction;

                                $won = $auction
                                    && $auction->winner_id === $user->id;
                            @endphp

                            <tr>

                                <td class="px-6 py-4">
                                    <a href="{{ route('admin.auctions.show', $auction) }}"
                                        class="block hover:underline">
                                    #{{ $auction->id ?? 'N/A' }}
                                </a>
                                </td>

                                <td class="px-6 py-4">
                                    <a href="{{ route('admin.products.show', $auction->product) }}"
                                        class="block hover:underline">
                                    {{ $auction->product->name ?? 'Product unavailable' }}
                                    </a>
                                </td>

                                <td class="px-6 py-4 font-semibold">
                                  {{--  <a href="{{ route('admin.invoices.show', $user->invoices) }}"
                                        class="block hover:underline">

                                        Ill Need to build the Relationship between InvoiceItem
                                        and Invoive inorder to get this working

                                        --}}
                                    ${{ number_format($bid->promise_amount, 2) }}
                                    </a>
                                </td>

                                <td class="px-6 py-4">
                                    {{ $bid->ticket_cost }}
                                </td>

                                <td class="px-6 py-4">

                                    @if($won)

                                        <span class="font-semibold text-green-600">
                                            WON
                                        </span>

                                    @elseif($auction?->status === 'completed')

                                        <span class="font-semibold text-red-600">
                                            LOST
                                        </span>

                                    @else

                                        <span class="text-zinc-500">
                                            Active
                                        </span>

                                    @endif

                                </td>

                                <td class="px-6 py-4">
                                    {{ $bid->created_at->format('M d, Y h:i A') }}
                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @endif

    </div>


    {{-- WINS --}}


    <div class="mb-8">

        <flux:heading size="lg" class="mb-4">
            Auctions Won
        </flux:heading>

        @if($wins->isEmpty())

            <div class="rounded-xl border border-zinc-200 p-6 text-center dark:border-zinc-700">

                <flux:text>
                    This user has not won any auctions.
                </flux:text>

            </div>

        @else

            <div class="overflow-x-auto rounded-xl border border-zinc-200 dark:border-zinc-700">

                <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">

                    <thead class="bg-zinc-50 dark:bg-zinc-800">

                        <tr>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase">
                                Auction
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase">
                                Product
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase">
                                Winning Bid
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase">
                                Tickets
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase">
                                Auction Ended
                            </th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">

                        @foreach($wins->sortByDesc('created_at') as $bid)

                            <tr>

                                <td class="px-6 py-4">
                                    #{{ $bid->auction->id }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ $bid->auction->product->name ?? 'Product unavailable' }}
                                </td>

                                <td class="px-6 py-4 font-semibold">
                                    ${{ number_format($bid->promise_amount, 2) }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ $bid->ticket_cost }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ $bid->auction->ends_at->format('M d, Y h:i A') }}
                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @endif

    </div>

    {{-- OUTSTANDING INVOICES FROM WINS= --}}


    <div class="mb-8">

        <div class="mb-4 flex items-center justify-between">

            <flux:heading size="lg">
                Outstanding Invoices from Wins
            </flux:heading>

            <span class="rounded-full bg-red-100 px-3 py-1 text-sm font-semibold text-red-700">
                {{ $outstandingInvoices->count() }}
            </span>

        </div>


        @if($outstandingInvoices->isEmpty())

            <div class="rounded-xl border border-zinc-200 p-6 text-center dark:border-zinc-700">

                <flux:text>
                    This user has no outstanding invoices from winning auctions.
                </flux:text>

            </div>

        @else

            <div class="overflow-x-auto rounded-xl border border-zinc-200 dark:border-zinc-700">

                <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">

                    <thead class="bg-zinc-50 dark:bg-zinc-800">

                        <tr>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase">
                                Invoice
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase">
                                Auction
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase">
                                Product
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase">
                                Amount
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase">
                                Status
                            </th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">

                        @foreach($outstandingInvoices as $invoice)

                            @foreach($invoice->items as $item)

                                @if(
                                    $item->bid
                                    && $item->bid->auction
                                    && $item->bid->auction->winner_id === $user->id
                                )

                                    <tr>

                                        <td class="px-6 py-4">

                                            <a
                                                href="{{ route('admin.invoices.show', $invoice) }}"
                                                class="font-semibold underline"
                                            >
                                                {{ $invoice->invoice_number }}
                                            </a>

                                        </td>

                                        <td class="px-6 py-4">
                                            #{{ $item->bid->auction->id }}
                                        </td>

                                        <td class="px-6 py-4">
                                            {{ $item->bid->auction->product->name ?? 'Product unavailable' }}
                                        </td>

                                        <td class="px-6 py-4 font-semibold">
                                            ${{ number_format($item->bid->promise_amount, 2) }}
                                        </td>

                                        <td class="px-6 py-4">

                                            <span class="font-semibold text-red-600">
                                                Outstanding
                                            </span>

                                        </td>

                                    </tr>

                                @endif

                            @endforeach

                        @endforeach

                    </tbody>

                </table>

            </div>

        @endif

    </div>


    <div class="mt-6">

        <a href="{{ route('admin.users.index') }}">
            ← Back to Users
        </a>

    </div>

</div>


</x-layouts::app>
