@extends('layouts.admin')

@section('title', 'Instagram Integration')

@section('content')
<div class="container-fluid">

    <div class="row">

        <div class="col-lg-8 mx-auto">

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @php
                $account = \App\Models\SocialAccount::where('platform','instagram')->first();
            @endphp

            <div class="card shadow">

                <div class="card-header bg-primary text-white">

                    <h4 class="mb-0">
                        Instagram Integration
                    </h4>

                </div>

                <div class="card-body">

                    @if($account)

                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label><strong>Status</strong></label>

                                <div>
                                    @if($account->is_active)
                                        <span class="badge bg-success">
                                            Connected
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            Disconnected
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label><strong>Username</strong></label>
                                <p>{{ $account->instagram_username }}</p>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label><strong>Business ID</strong></label>
                                <p>{{ $account->instagram_business_id }}</p>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label><strong>Token Expiry</strong></label>
                                <p>{{ optional($account->expires_at)->format('d M Y H:i') }}</p>
                            </div>

                        </div>

                 <div class="d-flex gap-2">

    <a href="{{ route('instagram.connect') }}" class="btn btn-primary">
        Connect Another Account
    </a>

    <form method="POST" action="{{ route('instagram.disconnect') }}">
        @csrf
        <button type="submit"
                class="btn btn-danger"
                onclick="return confirm('Disconnect current Instagram account?')">
            Disconnect
        </button>
    </form>

</div>

                    @else

                        <div class="text-center py-5">

                            <img
                                src="https://upload.wikimedia.org/wikipedia/commons/a/a5/Instagram_icon.png"
                                width="90">

                            <h4 class="mt-4">
                                Instagram Not Connected
                            </h4>

                            <p class="text-muted">
                                Connect your Instagram Business account once.
                            </p>

                            <a href="{{ route('instagram.connect') }}"
                               class="btn btn-lg btn-primary">

                                Connect Instagram

                            </a>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>
@endsection