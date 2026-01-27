@extends('layouts.admin')

@section('content')
<div class="container">
    <h2 class="mb-4">Edit Inventory</h2>

    @include('products.partials._errors')

    <form action="{{ admin_route('products.update', $product->id) }}"
          method="POST"
          enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- BASIC INFO --}}
        @include('products.partials._basic_info', [
            'product' => $product
        ])

        {{-- IMAGES --}}
        @include('products.partials._images', [
            'product' => $product
        ])

        {{-- CATEGORY --}}
        @include('products.partials._category', [
            'product' => $product,
            'categories' => $categories
        ])

        {{-- SUPPLIER --}}
        @include('products.partials._supplier', [
            'product' => $product,
            'suppliers' => $suppliers
        ])

        {{-- VARIANTS --}}
        @include('products.partials._variants', [
            'product' => $product
        ])

        {{-- SETTINGS --}}
        @include('products.partials._settings', [
            'product' => $product
        ])

        <div class="text-end">
            <a href="{{ admin_route('products.index') }}"
               class="btn btn-secondary">Back</a>

            <button class="btn btn-primary">
                Update Product
            </button>
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