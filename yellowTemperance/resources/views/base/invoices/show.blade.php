<x-layouts::app :title="__('Base Auctions')" class="">


<div class="mx-auto max-w-6xl px-4 py-8">

    {{-- Header --}}
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                Invoice {{ $invoice->invoice_number }}
            </h1>

            <p class="mt-2 text-gray-600 dark:text-gray-400">
                Issued {{ $invoice->issued_at->format('M d, Y h:i A') }}
            </p>
        </div>

        <a
            href="{{ route('base.invoices.index') }}"
            class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700"
        >
            Back to Invoices
        </a>
    </div>

    {{-- Invoice Summary --}}
    <div class="mb-8 grid gap-6 md:grid-cols-3">

        <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Status
            </p>

            <p class="mt-2 text-lg font-semibold text-gray-900 dark:text-white">
                {{ ucfirst($invoice->status) }}
            </p>
        </div>

        <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Total Bids
            </p>

            <p class="mt-2 text-lg font-semibold text-gray-900 dark:text-white">
                {{ $invoice->total_bids }}
            </p>
        </div>

        <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Tickets Used
            </p>

            <p class="mt-2 text-lg font-semibold text-gray-900 dark:text-white">
                {{ $invoice->total_tickets_used }}
            </p>
        </div>

    </div>

    {{-- Customer Information --}}
    <div class="mb-8 rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

        <h2 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">
            Customer
        </h2>

        <div class="grid gap-4 md:grid-cols-2">

            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Name
                </p>

                <p class="font-medium text-gray-900 dark:text-white">
                    {{ $invoice->user->name }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Email
                </p>

                <p class="font-medium text-gray-900 dark:text-white">
                    {{ $invoice->user->email }}
                </p>
            </div>

        </div>

    </div>

    {{-- Bidding Activity --}}
    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">

        <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                Bidding Activity
            </h2>

            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Bids and tickets associated with this invoice.
            </p>
        </div>

        @if($invoice->items->isEmpty())

            <div class="p-8 text-center text-gray-500 dark:text-gray-400">
                No bidding activity is associated with this invoice.
            </div>

        @else

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">

                    <thead class="bg-gray-50 dark:bg-gray-900">
                        <tr>

                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                Product
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                Auction
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                Bid Amount
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                Tickets
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                Date
                            </th>

                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">

                        @foreach($invoice->items as $item)

                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">

                                {{-- Product --}}
                                <td class="whitespace-nowrap px-6 py-4">

                                    @if($item->product)
                                        <span class="font-medium text-gray-900 dark:text-white">
                                            {{ $item->product->name }}
                                        </span>
                                    @else
                                        <span class="text-gray-500 dark:text-gray-400">
                                            Product unavailable
                                        </span>
                                    @endif

                                </td>

                                {{-- Auction --}}
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600 dark:text-gray-400">

                                    @if($item->auction_id)
                                        Auction #{{ $item->auction_id }}
                                    @else
                                        -
                                    @endif

                                </td>

                                {{-- Bid Amount --}}
                                <td class="whitespace-nowrap px-6 py-4 font-medium text-gray-900 dark:text-white">

                                    ${{ number_format($item->bid_amount, 2) }}

                                </td>

                                {{-- Tickets --}}
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600 dark:text-gray-400">

                                    {{ $item->tickets_used }}

                                </td>

                                {{-- Date --}}
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600 dark:text-gray-400">

                                    {{ $item->bid_placed_at?->format('M d, Y h:i A') ?? 'N/A' }}

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @endif

    </div>

    {{-- Invoice Period --}}
    @if($invoice->period_start || $invoice->period_end)

        <div class="mt-6 text-sm text-gray-500 dark:text-gray-400">

            <span class="font-medium">
                Invoice Period:
            </span>

            {{ $invoice->period_start?->format('M d, Y') ?? 'N/A' }}

            -

            {{ $invoice->period_end?->format('M d, Y') ?? 'N/A' }}

        </div>

    @endif

</div>

</x-layouts::app>
