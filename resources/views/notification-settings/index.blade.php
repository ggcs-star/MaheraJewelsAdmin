@extends('layouts.admin.admin-settings')

@section('settings-content')

<div class="space-y-6">

    <div class="flex items-center justify-between">

        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Notification Settings
            </h1>

            <p class="text-sm text-gray-500">
                Manage all notification recipient emails.
            </p>
        </div>

        <a href="{{ route('admin.notification-settings.create') }}"
           class="px-5 py-2 rounded-lg text-white shadow"
           style="background: var(--primary-light);">

            + Add Email

        </a>

    </div>


    @if(session('success'))

        <div class="bg-green-100 text-green-700 px-4 py-3 rounded-lg">

            {{ session('success') }}

        </div>

    @endif


    <div class="bg-white rounded-xl shadow border overflow-hidden">

        <div class="overflow-x-auto">

            <table class="min-w-full">

                <thead class="bg-gray-100">

                <tr>

                    <th class="px-5 py-4 text-left">
                        Name
                    </th>

                    <th class="px-5 py-4 text-left">
                        Email
                    </th>

                    <th class="px-5 py-4 text-center">
                        Order
                    </th>

                    <th class="px-5 py-4 text-center">
                        Cancel
                    </th>

                    <th class="px-5 py-4 text-center">
                        Registration
                    </th>

                    <th class="px-5 py-4 text-center">
                        Contact
                    </th>

                    <th class="px-5 py-4 text-center">
                        Status
                    </th>

                    <th class="px-5 py-4 text-center">
                        Action
                    </th>

                </tr>

                </thead>

                <tbody>

                @forelse($notifications as $notification)

                    <tr class="border-t">

                        <td class="px-5 py-4">

                            {{ $notification->name }}

                        </td>

                        <td class="px-5 py-4">

                            {{ $notification->email }}

                        </td>

                        <td class="text-center">

                            @if($notification->receive_order)

                                ✅

                            @else

                                ❌

                            @endif

                        </td>

                        <td class="text-center">

                            @if($notification->receive_cancel)

                                ✅

                            @else

                                ❌

                            @endif

                        </td>
                                                <td class="text-center">

                            @if($notification->receive_registration)

                                ✅

                            @else

                                ❌

                            @endif

                        </td>

                        <td class="text-center">

                            @if($notification->receive_contact)

                                ✅

                            @else

                                ❌

                            @endif

                        </td>

                        <td class="text-center">

                            @if($notification->is_active)

                                <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs">
                                    Active
                                </span>

                            @else

                                <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs">
                                    Inactive
                                </span>

                            @endif

                        </td>

                        <td class="text-center">

                            <div class="flex justify-center gap-3">

                                <a href="{{ route('admin.notification-settings.edit',$notification) }}"
                                   class="text-blue-600 hover:text-blue-800">

                                    Edit

                                </a>

                                <form action="{{ route('admin.notification-settings.destroy',$notification) }}"
                                      method="POST"
                                      onsubmit="return confirm('Delete this email?')">

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="text-red-600 hover:text-red-800">

                                        Delete

                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="8"
                            class="py-8 text-center text-gray-500">

                            No Notification Emails Found.

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

    <div>

        {{ $notifications->links() }}

    </div>

</div>

@endsection