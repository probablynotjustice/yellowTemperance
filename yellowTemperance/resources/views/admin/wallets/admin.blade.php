<x-layouts::app :title="__('My Wallet')">
    <div class="mx-auto max-w-4xl space-y-6 p-6">

        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                My Admin Wallet
            </h1>

            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Your personal wallet balance and funds.
            </p>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-6
                    dark:border-gray-700 dark:bg-gray-900">

            <p class="text-sm text-gray-500 dark:text-gray-400">
                Total Balance
            </p>

            <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">
                ${{ number_format((float) $wallet->balance, 2) }}
            </p>

            <div class="mt-6 grid gap-4 sm:grid-cols-2">
                <div class="rounded-lg bg-gray-50 p-4 dark:bg-gray-800">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Held Balance
                    </p>

                    <p class="mt-1 text-xl font-semibold text-amber-600">
                        ${{ number_format((float) $wallet->held_balance, 2) }}
                    </p>
                </div>

                <div class="rounded-lg bg-gray-50 p-4 dark:bg-gray-800">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Available Balance
                    </p>

                    <p class="mt-1 text-xl font-semibold text-green-600">
                        ${{ number_format((float) $wallet->getAvailableBalance(), 2) }}
                    </p>
                </div>
            </div>
        </div>

    </div>

    <div class="mx-auto max-w-5xl space-y-6 p-6">

        {{-- Page Header --}}
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                Wallet Update
            </h1>

            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Add funds to your wallet using a preset amount or a custom value.
            </p>
        </div>


        <div class="rounded-xl border border-gray-200 bg-white p-6
                    dark:border-gray-700 dark:bg-gray-900">

            <div class="mb-5">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Quick Add
                </h2>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Choose an amount to add.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">


                <form method="POST" action="{{ route('base.wallets.add', 1) }}">
                    @csrf

                    <button type="submit"
                        class="group w-full rounded-xl border border-gray-200
                               bg-gray-50 p-5 text-left transition
                               hover:border-green-500 hover:bg-green-50
                               dark:border-gray-700 dark:bg-gray-800
                               dark:hover:border-green-500 dark:hover:bg-gray-700">

                        <span class="block text-sm font-medium text-gray-500
                                     dark:text-gray-400">
                            Add funds
                        </span>

                        <span class="mt-2 block text-2xl font-bold text-gray-900
                                     group-hover:text-green-600 dark:text-white">
                            +$1.00
                        </span>
                    </button>
                </form>


                <form method="POST" action="{{ route('base.wallets.add', 10) }}">
                    @csrf

                    <button type="submit"
                        class="group w-full rounded-xl border border-gray-200
                               bg-gray-50 p-5 text-left transition
                               hover:border-green-500 hover:bg-green-50
                               dark:border-gray-700 dark:bg-gray-800
                               dark:hover:border-green-500 dark:hover:bg-gray-700">

                        <span class="block text-sm font-medium text-gray-500
                                     dark:text-gray-400">
                            Add funds
                        </span>

                        <span class="mt-2 block text-2xl font-bold text-gray-900
                                     group-hover:text-green-600 dark:text-white">
                            +$10.00
                        </span>
                    </button>
                </form>


                <form method="POST" action="{{ route('base.wallets.add', 100) }}">
                    @csrf

                    <button type="submit"
                        class="group w-full rounded-xl border border-gray-200
                               bg-gray-50 p-5 text-left transition
                               hover:border-green-500 hover:bg-green-50
                               dark:border-gray-700 dark:bg-gray-800
                               dark:hover:border-green-500 dark:hover:bg-gray-700">

                        <span class="block text-sm font-medium text-gray-500
                                     dark:text-gray-400">
                            Add funds
                        </span>

                        <span class="mt-2 block text-2xl font-bold text-gray-900
                                     group-hover:text-green-600 dark:text-white">
                            +$100.00
                        </span>
                    </button>
                </form>

            </div>
        </div>


        <div class="rounded-xl border border-gray-200 bg-white p-6
                    dark:border-gray-700 dark:bg-gray-900">

            <div class="mb-5">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Custom Amount
                </h2>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Enter how much you would like to add.
                </p>
            </div>

            <form method="POST" action="{{ route('base.wallets.add.custom') }}"
                  class="space-y-4">
                @csrf

                <div>
                    <label for="amount"
                        class="mb-2 block text-sm font-medium text-gray-700
                               dark:text-gray-300">
                        Amount ($)
                    </label>

                    <input
                        type="number"
                        id="amount"
                        name="amount"
                        step="1"
                        min="1"
                        max="1000"
                        value="{{ old('amount') }}"
                        placeholder="Enter an amount"
                        required
                        class="block w-full rounded-lg border border-gray-300
                               bg-white px-4 py-3 text-gray-900 shadow-sm
                               outline-none transition
                               focus:border-green-500 focus:ring-2
                               focus:ring-green-500/20
                               dark:border-gray-600 dark:bg-gray-800
                               dark:text-white dark:placeholder-gray-400"
                    >

                    @error('amount')
                        <p class="mt-2 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                        Minimum: $1.00 · Maximum: $1,000.00
                    </p>
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                        class="inline-flex items-center justify-center rounded-lg
                               bg-green-600 px-5 py-3 text-sm font-semibold
                               text-white shadow-sm transition
                               hover:bg-green-700 focus:outline-none
                               focus:ring-2 focus:ring-green-500
                               focus:ring-offset-2
                               dark:focus:ring-offset-gray-900">
                        Add to Wallet
                    </button>
                </div>
            </form>
        </div>

    </div>

{{-- Wallet Transaction History --}}
<div class="overflow-hidden rounded-xl border border-gray-200
            bg-white dark:border-gray-700 dark:bg-gray-900">

    <div class="border-b border-gray-200 p-6 dark:border-gray-700">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
            Transaction History
        </h2>

        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Review your recent wallet activity.
        </p>
    </div>

    @if (!$wallet || $wallet->transactions->isEmpty())
        <div class="p-8 text-center">
            <div class="mx-auto flex h-12 w-12 items-center justify-center
                        rounded-full bg-gray-100 dark:bg-gray-800">
                <icon name="receipt-text" size="xl" />
            </div>

            <p class="mt-3 font-medium text-gray-900 dark:text-white">
                No transactions yet
            </p>

            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Your wallet activity will appear here once you add funds.
            </p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">

                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold
                                   uppercase tracking-wider text-gray-500
                                   dark:text-gray-400">
                            Transaction
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold
                                   uppercase tracking-wider text-gray-500
                                   dark:text-gray-400">
                            Type
                        </th>

                        <th class="px-6 py-3 text-right text-xs font-semibold
                                   uppercase tracking-wider text-gray-500
                                   dark:text-gray-400">
                            Amount
                        </th>

                        <th class="px-6 py-3 text-right text-xs font-semibold
                                   uppercase tracking-wider text-gray-500
                                   dark:text-gray-400">
                            Date
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($wallet->transactions as $transaction)
                        <tr class="transition hover:bg-gray-50 dark:hover:bg-gray-800/60">

                            <td class="px-6 py-4">
                                <p class="text-sm font-medium text-gray-900
                                          dark:text-white">
                                    {{ $transaction->description ?? 'Wallet transaction' }}
                                </p>

                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    Transaction #{{ $transaction->id }}
                                </p>
                            </td>

                            <td class="whitespace-nowrap px-6 py-4">
                                <span class="inline-flex rounded-full px-2.5 py-1
                                    text-xs font-medium
                                    {{ $transaction->type === 'funding'
                                        ? 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300'
                                        : 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300' }}">
                                    {{ str($transaction->type)->replace('_', ' ')->title() }}
                                </span>
                            </td>

                            <td class="whitespace-nowrap px-6 py-4 text-right">
                                <span class="text-sm font-semibold
                                    {{ $transaction->type === 'funding'
                                        ? 'text-green-600 dark:text-green-400'
                                        : 'text-gray-900 dark:text-white' }}">
                                    {{ $transaction->type === 'funding' ? '+' : '' }}${{ number_format((float) $transaction->amount, 2) }}
                                </span>
                            </td>

                            <td class="whitespace-nowrap px-6 py-4 text-right
                                       text-sm text-gray-500 dark:text-gray-400">
                                {{ $transaction->created_at?->format('M d, Y') ?? 'N/A' }}
                                <span class="mt-1 block text-xs">
                                    {{ $transaction->created_at?->format('g:i A') }}
                                </span>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>


</x-layouts::app>
