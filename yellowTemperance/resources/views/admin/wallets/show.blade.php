<x-layouts::app :title="__('Wallet Details')">
    <div class="mx-auto max-w-6xl space-y-6 p-6">

        {{-- Page Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    Wallet Details
                </h1>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Wallet #{{ $wallet->id }}
                </p>
            </div>

            <a href="{{ route('admin.wallets.index') }}"
               class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium
                      text-gray-700 hover:bg-gray-100
                      dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-800">
                Back to Wallets
            </a>
        </div>

        {{-- Wallet Owner --}}
        <div class="rounded-xl border border-gray-200 bg-white p-6
                    dark:border-gray-700 dark:bg-gray-900">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                Wallet Owner
            </h2>

            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Name</p>
                    <p class="font-medium text-gray-900 dark:text-white">
                        {{ $wallet->user?->name ?? 'Unknown User' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Email</p>
                    <p class="font-medium text-gray-900 dark:text-white">
                        {{ $wallet->user?->email ?? 'No email available' }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Wallet Balances --}}
        <div class="grid gap-4 sm:grid-cols-2">

            <div class="rounded-xl border border-gray-200 bg-white p-5
                        dark:border-gray-700 dark:bg-gray-900">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Total Balance
                </p>

                <p class="mt-2 text-2xl font-bold text-gray-900 dark:text-white">
                    ${{ number_format((float) $wallet->balance, 2) }}
                </p>
            </div>


            <div class="rounded-xl border border-gray-200 bg-white p-5
                        dark:border-gray-700 dark:bg-gray-900">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Available Balance
                </p>

                <p class="mt-2 text-2xl font-bold text-green-600">
                    ${{ number_format((float) $wallet->getAvailableBalance(), 2) }}
                </p>
            </div>

        </div>

        {{-- Transaction History --}}
        <div class="overflow-hidden rounded-xl border border-gray-200
                    bg-white dark:border-gray-700 dark:bg-gray-900">

            <div class="border-b border-gray-200 p-5 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Transaction History
                </h2>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    All recorded transactions for this wallet.
                </p>
            </div>

            @if ($wallet->transactions->isEmpty())
                <div class="p-8 text-center text-gray-500 dark:text-gray-400">
                    No transactions have been recorded for this wallet.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">

                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase
                                           tracking-wider text-gray-500 dark:text-gray-300">
                                    Transaction
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase
                                           tracking-wider text-gray-500 dark:text-gray-300">
                                    Type
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase
                                           tracking-wider text-gray-500 dark:text-gray-300">
                                    Amount
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase
                                           tracking-wider text-gray-500 dark:text-gray-300">
                                    Date
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($wallet->transactions as $transaction)
                                <tr>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                        {{ $transaction->description ?? 'Wallet transaction' }}
                                    </td>

                                    <td class="px-6 py-4 text-sm">
                                        <span class="inline-flex rounded-full px-2 py-1 text-xs font-medium
                                            {{ $transaction->type === 'funding'
                                                ? 'bg-green-100 text-green-800'
                                                : 'bg-gray-100 text-gray-800' }}">
                                            {{ str($transaction->type)->replace('_', ' ')->title() }}
                                        </span>
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium
                                               text-gray-900 dark:text-white">
                                        ${{ number_format((float) $transaction->amount, 2) }}
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4 text-sm
                                               text-gray-500 dark:text-gray-400">
                                        {{ $transaction->created_at?->format('M d, Y h:i A') ?? 'N/A' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>
            @endif
        </div>

    </div>
</x-layouts::app>
