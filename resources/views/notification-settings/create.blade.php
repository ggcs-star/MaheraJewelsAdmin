@extends('layouts.admin.admin-settings')

@section('settings-content')

<div class="max-w-4xl mx-auto">

    <div class="bg-white rounded-xl shadow border">

        <div class="px-6 py-4 border-b">

            <h2 class="text-xl font-bold">
                Add Notification Email
            </h2>

        </div>

        <form action="{{ route('admin.notification-settings.store') }}"
              method="POST">

            @csrf

            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">

                <div>

                    <label class="block mb-2 font-medium">

                        Name

                    </label>

                    <input
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        class="w-full border rounded-lg px-4 py-2">

                </div>

                <div>

                    <label class="block mb-2 font-medium">

                        Email

                    </label>

                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        class="w-full border rounded-lg px-4 py-2">

                </div>

                <div>

                    <label class="inline-flex items-center gap-2">

                        <input
                            type="checkbox"
                            name="receive_order"
                            checked>

                        <span>

                            Receive Order Mail

                        </span>

                    </label>

                </div>

                <div>

                    <label class="inline-flex items-center gap-2">

                        <input
                            type="checkbox"
                            name="receive_cancel">

                        <span>

                            Receive Cancel Mail

                        </span>

                    </label>

                </div>

                <div>

                    <label class="inline-flex items-center gap-2">

                        <input
                            type="checkbox"
                            name="receive_registration">

                        <span>

                            Receive Registration Mail

                        </span>

                    </label>

                </div>

                <div>

                    <label class="inline-flex items-center gap-2">

                        <input
                            type="checkbox"
                            name="receive_contact">

                        <span>

                            Receive Contact Mail

                        </span>

                    </label>

                </div>

                <div>

                    <label class="inline-flex items-center gap-2">

                        <input
                            type="checkbox"
                            name="is_active"
                            checked>

                        <span>

                            Active

                        </span>

                    </label>

                </div>
                                <div class="md:col-span-2">

                    <div class="flex justify-end gap-3">

                        <a href="{{ route('admin.notification-settings.index') }}"
                           class="px-5 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100">

                            Cancel

                        </a>

                        <button
                            type="submit"
                            class="px-6 py-2 rounded-lg text-white"
                            style="background: var(--primary-light);">

                            Save Notification Email

                        </button>

                    </div>

                </div>

            </div>

        </form>

    </div>

</div>

@endsection