<x-layouts::app :title="__('My Invoices')">


<div class="mx-auto max-w-6xl px-4 py-8">

    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
            My Invoices
        </h1>

        <p class="mt-2 text-gray-600 dark:text-gray-400">
            View your invoices and bidding activity.
        </p>
    </div>

    @if($invoices->isEmpty())

        <div class="rounded-lg border border-gray-200 bg-white p-8 text-center shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                No Invoices
            </h2>

            <p class="mt-2 text-gray-500 dark:text-gray-400">
                You don't have any invoices yet.
            </p>

        </div>

    @else

        <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">

                    <thead class="bg-gray-50 dark:bg-gray-900">

                        <tr>

                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                Invoice
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                Issued
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                Status
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                Bids
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                Tickets
                            </th>

                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">
                                Action
                            </th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">

                        @foreach($invoices as $invoice)

                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">

                                {{-- Invoice --}}
                                <td class="whitespace-nowrap px-6 py-4">

                                    <span class="font-medium text-gray-900 dark:text-white">
                                        {{ $invoice->invoice_number }}
                                    </span>

                                </td>

                                {{-- Issued --}}
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600 dark:text-gray-400">

                                    {{ $invoice->issued_at->format('M d, Y h:i A') }}

                                </td>

                                {{-- Status --}}
                                <td class="whitespace-nowrap px-6 py-4">

                                    <span class="rounded-full px-3 py-1 text-xs font-medium
                                        {{ $invoice->status === 'outstanding'
                                            ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400'
                                            : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'
                                        }}"
                                    >
                                        {{ ucfirst($invoice->status) }}
                                    </span>

                                </td>

                                {{-- Bids --}}
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600 dark:text-gray-400">

                                    {{ $invoice->total_bids }}

                                </td>

                                {{-- Tickets --}}
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600 dark:text-gray-400">

                                    {{ $invoice->total_tickets_used }}

                                </td>

                                {{-- Action --}}
                                <td class="whitespace-nowrap px-6 py-4 text-right">

                                    <a
                                        href="{{ route('base.invoices.show', $invoice) }}"
                                        class="font-medium text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300"
                                    >
                                        View Invoice
                                    </a>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    @endif

</div>

</x-layouts::app>
