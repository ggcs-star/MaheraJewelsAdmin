@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Push Product to Marketplace</h2>
        <a href="{{ admin_route('products.index') }}" class="btn btn-secondary">
            ← Back to Inventory
        </a>
    </div>

    @include('products.partials._errors')

    <form action="{{ admin_route('products.push.store') }}" method="POST" enctype="multipart/form-data" id="pushProductForm">
        @csrf

        @include('products.push._product_selection')
        @include('products.push._product_details')
        @include('products.push._product_variants') 
        @include('products.push._platform_selection')
        @include('products.push._platform_pricing')

        <div class="text-end mt-4">
            <a href="{{ admin_route('products.index') }}" class="btn btn-secondary me-2">Cancel</a>
            <button type="submit" class="btn btn-primary">Push to Selected Platforms</button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
    @include('products.push.scripts.push-product-js')
@endsection
