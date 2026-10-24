 <x-layouts::app :title="__('Admin Activity Logs')">

<div class="mx-auto max-w-7xl space-y-6 p-6">

    {{-- Page Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-3">
            <div class="flex size-12 items-center justify-center rounded-xl
                        bg-green-50 text-green-600
                        dark:bg-green-900/30 dark:text-green-400">
                <flux:icon name="list-bullet" class="size-6" />
            </div>

            <div>
                <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                    Activity Logs
                </h1>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Review system activity and user actions.
                </p>
            </div>
        </div>

        <div class="inline-flex w-fit items-center gap-2 rounded-lg border
                    border-gray-200 bg-white px-4 py-2
                    dark:border-gray-700 dark:bg-gray-900">
            <flux:icon name="list-bullet" class="size-5 text-gray-500 dark:text-gray-400" />

            <span class="text-sm text-gray-500 dark:text-gray-400">
                Total records
            </span>

            <span class="text-sm font-semibold tabular-nums text-gray-900 dark:text-white">
                {{ $logs->total() }}
            </span>
        </div>
    </div>

    {{-- Activity Log List --}}
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white
                dark:border-gray-700 dark:bg-gray-900">

        {{-- List Header --}}
        <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
            <h2 class="text-base font-semibold text-gray-900 dark:text-white">
                Recent Activity
            </h2>

            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                A record of actions performed across your application.
            </p>
        </div>

        @forelse ($logs as $log)

            <div class="border-b border-gray-100 p-6 last:border-b-0
                        dark:border-gray-800">

                {{-- Action and Date --}}
                <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                    <div class="flex min-w-0 items-start gap-3">
                        <div class="flex size-10 shrink-0 items-center justify-center
                                    rounded-lg bg-gray-100 text-gray-600
                                    dark:bg-gray-800 dark:text-gray-300">
                            <flux:icon name="bolt" class="size-5" />
                        </div>

                        <div class="min-w-0">
                            <span class="inline-flex items-center rounded-full
                                         bg-green-50 px-2.5 py-1 text-xs font-medium
                                         text-green-700 ring-1 ring-inset ring-green-600/20
                                         dark:bg-green-900/30 dark:text-green-300">
                                {{ $log->action }}
                            </span>

                            <p class="mt-2 break-words text-sm leading-6 text-gray-700
                                      dark:text-gray-300">
                                {{ $log->description }}
                            </p>
                        </div>
                    </div>

                    <div class="shrink-0 sm:text-right">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ $log->created_at?->format('M d, Y') ?? 'N/A' }}
                        </p>

                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            {{ $log->created_at?->format('g:i A') ?? '' }}
                        </p>
                    </div>
                </div>

                {{-- Log Details --}}
                <div class="mt-5 grid grid-cols-1 gap-4 rounded-lg
                            bg-gray-50 p-4 sm:grid-cols-2 xl:grid-cols-3
                            dark:bg-gray-800/50">

                    {{-- User --}}
                    <div class="min-w-0">
                        <p class="text-xs font-medium uppercase tracking-wide
                                  text-gray-500 dark:text-gray-400">
                            User
                        </p>

                        <div class="mt-2 text-sm">
                            @if ($log->user)
                                <a href="{{ route('admin.users.show', $log->user) }}"
                                   class="font-medium text-gray-900 hover:text-green-600
                                          hover:underline dark:text-white
                                          dark:hover:text-green-400">
                                    {{ $log->user->name }}
                                </a>

                                <p class="mt-1 break-all text-xs text-gray-500 dark:text-gray-400">
                                    {{ $log->user->email }}
                                </p>
                            @else
                                <span class="text-gray-500 dark:text-gray-400">
                                    System / Unknown
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- IP Address --}}
                    <div class="min-w-0">
                        <p class="text-xs font-medium uppercase tracking-wide
                                  text-gray-500 dark:text-gray-400">
                            IP Address
                        </p>

                        <p class="mt-2 break-all font-mono text-sm text-gray-700
                                  dark:text-gray-300">
                            {{ $log->ip_address ?? 'N/A' }}
                        </p>
                    </div>

                    {{-- Loggable Record --}}
                    <div class="min-w-0">
                        <p class="text-xs font-medium uppercase tracking-wide
                                  text-gray-500 dark:text-gray-400">
                            Related Record
                        </p>

                        <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                            @if ($log->loggable)
                                <span class="font-medium text-gray-900 dark:text-white">
                                    {{ class_basename($log->loggable_type) }}
                                </span>

                                <span class="text-gray-500 dark:text-gray-400">
                                    #{{ $log->loggable_id }}
                                </span>
                            @else
                                <span class="text-gray-500 dark:text-gray-400">
                                    None
                                </span>
                            @endif
                        </p>
                    </div>

                </div>
            </div>

        @empty

            {{-- Empty State --}}
            <div class="px-6 py-14 text-center">
                <div class="mx-auto flex size-14 items-center justify-center
                            rounded-full bg-gray-100 text-gray-400
                            dark:bg-gray-800 dark:text-gray-500">
                    <flux:icon name="clipboard-list" class="size-7" />
                </div>

                <h3 class="mt-4 text-sm font-semibold text-gray-900 dark:text-white">
                    No activity recorded
                </h3>

                <p class="mx-auto mt-1 max-w-sm text-sm text-gray-500 dark:text-gray-400">
                    Activity will appear here as users interact with your application.
                </p>
            </div>

        @endforelse
    </div>

    {{-- Pagination --}}
    @if ($logs->hasPages())
        <div class="rounded-xl border border-gray-200 bg-white px-4 py-3
                    dark:border-gray-700 dark:bg-gray-900">
            {{ $logs->links() }}
        </div>
    @endif

</div>

</x-layouts::app>
