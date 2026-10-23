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
</x-layouts::app>
