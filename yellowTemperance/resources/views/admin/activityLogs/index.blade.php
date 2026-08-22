@extends('layouts.admin')

@section('content')

<h1>Activity Logs</h1>

<h1>
    Activity Logs: {{ $logs->total() }}
</h1>
<div>

    @forelse ($logs as $log)

        <div class="border rounded-lg p-4 mb-4">

            <div>
                <strong>Action:</strong>
                {{ $log->action }}
            </div>

            <div>
                <strong>Description:</strong>
                {{ $log->description }}
            </div>

            <div>
                <strong>User:</strong>

                @if ($log->user)
                    {{ $log->user->name }}
                    ({{ $log->user->email }})
                @else
                    System / Unknown
                @endif
            </div>

            <div>
                <strong>IP:</strong>
                {{ $log->ip_address ?? 'N/A' }}
            </div>

            <div>
                <strong>Date:</strong>
                {{ $log->created_at }}
            </div>

            <div>
                <strong>Loggable:</strong>

                @if ($log->loggable)
                    {{ class_basename($log->loggable_type) }}
                    #{{ $log->loggable_id }}
                @else
                    None
                @endif
            </div>

        </div>

    @empty

        <p>No activity has been recorded.</p>

    @endforelse

</div>

{{ $logs->links() }}
@endsection
