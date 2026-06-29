@extends('layouts.admin')

@section('content')

<div class="bg-white rounded-xl shadow">

    <div class="p-5 border-b">
        <h1 class="text-2xl font-bold">
            Notifications
        </h1>
    </div>

    @forelse($notifications as $notification)

        <div
            class="p-5 border-b hover:bg-gray-50 {{ !$notification->is_read ? 'bg-red-50' : '' }}"
        >
            <div class="flex justify-between">

                <div>

                    <h5 class="font-semibold text-gray-800">
                        {{ $notification->title }}
                    </h5>

                    <p class="text-gray-600 mt-1">
                        {{ $notification->message }}
                    </p>

                    <small class="text-gray-400">
                        {{ $notification->created_at->diffForHumans() }}
                    </small>

                </div>

                @if(!$notification->is_read)
                    <span
                        class="px-2 py-1 text-xs rounded bg-red-100 text-red-600 h-fit"
                    >
                        New
                    </span>
                @endif

            </div>

            @if($notification->action_url)
                <div class="mt-3">
                    <a
                        href="{{ $notification->action_url }}"
                        class="text-[#8B2452] font-medium"
                    >
                        View Details →
                    </a>
                </div>
            @endif
        </div>

    @empty

        <div class="p-10 text-center text-gray-500">
            No notifications found.
        </div>

    @endforelse

    <div class="p-5">
        {{ $notifications->links() }}
    </div>

</div>

@endsection