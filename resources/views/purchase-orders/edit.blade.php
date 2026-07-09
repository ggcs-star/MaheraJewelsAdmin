@extends('layouts.admin')

@section('content')

<div class="container">

    <h2 class="mb-4">

        Edit Purchase Order

    </h2>

    @if(session('success'))

        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">

            {{ session('success') }}

        </div>

    @endif

    @if(session('error'))

        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">

            {{ session('error') }}

        </div>

    @endif

    @if($errors->any())

        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif

    <form
        action="{{ admin_route('purchase-orders.update',$purchaseOrder->id) }}"
        method="POST"
        enctype="multipart/form-data">

        @csrf

        @method('PUT')

        @include('purchase-orders.partials._supplier')

        @include('purchase-orders.partials._purchase-details')

        @include('purchase-orders.partials._products')

        @include('purchase-orders.partials._invoice')

        @include('purchase-orders.partials._summary')

    </form>

</div>

@endsection

@section('scripts')

@include('purchase-orders.scripts.supplier-js')

@include('purchase-orders.scripts.products-js')

@include('purchase-orders.scripts.calculation-js')

@endsection