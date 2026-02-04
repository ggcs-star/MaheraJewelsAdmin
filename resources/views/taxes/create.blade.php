@extends('layouts.admin')

@section('content')
<div class="container">
    <h2 class="mb-3">Add Tax</h2>

    <form method="POST" action="{{ admin_route('taxes.store') }}">
        @csrf

        @include('taxes.form')

        <button class="btn btn-primary">Save</button>
        <a href="{{ admin_route('taxes.index') }}" class="btn btn-secondary">
            Cancel
        </a>
    </form>
</div>
@endsection
