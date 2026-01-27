@extends('layouts.admin')

@section('content')
<div class="container">
    <h2 class="mb-4">Create Inventory</h2>

    @include('products.partials._errors')

    <form action="{{ admin_route('products.store') }}"
          method="POST"
          enctype="multipart/form-data">
        @csrf

        @include('products.partials._basic_info')
        @include('products.partials._images')
        @include('products.partials._category')
        @include('products.partials._supplier')
        {{-- @include('products.partials._pricing') --}}
        
        @include('products.partials._variants')
        @include('products.partials._settings')

        <div class="text-end">
            <a href="{{ admin_route('products.index') }}"
               class="btn btn-secondary">Back</a>
            <button class="btn btn-primary">Save Product</button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
    @include('products.scripts.gallery-js')
    @include('products.scripts.variants-js')
    @include('products.scripts.categories-js')
    @include('products.scripts.supplier-js')
@endsection
