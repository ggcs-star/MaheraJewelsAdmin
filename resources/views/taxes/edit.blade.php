@extends('layouts.admin')

@section('content')
<div class="container">
    <h2 class="mb-3">Edit Tax</h2>

    <form method="POST"
          action="{{ admin_route('taxes.update', $tax->id) }}">
        @csrf
        @method('PUT')

        @include('taxes.form', ['tax' => $tax])

        <button class="btn btn-primary">Update</button>
        <a href="{{ admin_route('taxes.index') }}" class="btn btn-secondary">
            Cancel
        </a>
    </form>
</div>
@endsection
