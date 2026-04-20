@extends('layouts.admin')

@section('content')
<div class="space-y-4">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
        <div>
            <h1 class="text-xl font-bold text-gray-800">Edit Supplier</h1>
            <p class="text-gray-500 text-xs mt-0.5">Update supplier information</p>
        </div>
        <a href="{{ admin_route('suppliers.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 border border-gray-200 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-all">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back
        </a>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-lg px-4 py-3">
            <div class="flex items-start gap-2">
                <svg class="w-4 h-4 text-red-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <p class="text-sm font-medium text-red-700 mb-1">Please fix the following errors:</p>
                    <ul class="text-xs text-red-600 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-lg border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-5">
            <form method="POST" action="{{ admin_route('suppliers.update', $supplier->id) }}">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-[11px] font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                            Supplier Type <span class="text-red-500">*</span>
                        </label>
                        <select name="type" class="w-full px-3 py-1.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-1 focus:ring-[#8B2452] transition-all text-sm bg-white">
                            <option value="manufacturer" {{ $supplier->type == 'manufacturer' ? 'selected' : '' }}>Manufacturer</option>
                            <option value="distributor" {{ $supplier->type == 'distributor' ? 'selected' : '' }}>Distributor</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                            Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name', $supplier->name) }}" class="w-full px-3 py-1.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-1 focus:ring-[#8B2452] transition-all text-sm" placeholder="Enter supplier name">
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-gray-700 uppercase tracking-wider mb-1.5">Company Name</label>
                        <input type="text" name="company_name" value="{{ old('company_name', $supplier->company_name) }}" class="w-full px-3 py-1.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-1 focus:ring-[#8B2452] transition-all text-sm" placeholder="Enter company name">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                    <div>
                        <label class="block text-[11px] font-bold text-gray-700 uppercase tracking-wider mb-1.5">Email</label>
                        <input type="email" name="email" value="{{ old('email', $supplier->email) }}" class="w-full px-3 py-1.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-1 focus:ring-[#8B2452] transition-all text-sm" placeholder="supplier@example.com">
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-gray-700 uppercase tracking-wider mb-1.5">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone', $supplier->phone) }}" class="w-full px-3 py-1.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-1 focus:ring-[#8B2452] transition-all text-sm" placeholder="Contact number">
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-gray-700 uppercase tracking-wider mb-1.5">Status</label>
                        <select name="status" class="w-full px-3 py-1.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-1 focus:ring-[#8B2452] transition-all text-sm bg-white">
                            <option value="active" {{ $supplier->status == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $supplier->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="block text-[11px] font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                            Commission Type <span class="text-red-500">*</span>
                        </label>
                        <select name="commission_type" class="w-full px-3 py-1.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-1 focus:ring-[#8B2452] transition-all text-sm bg-white">
                            <option value="percentage" {{ $supplier->commission_type == 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                            <option value="fixed" {{ $supplier->commission_type == 'fixed' ? 'selected' : '' }}>Fixed (₹)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                            Commission Value <span class="text-red-500">*</span>
                        </label>
                        <input type="number" step="0.01" name="commission_value" value="{{ old('commission_value', $supplier->commission_value) }}" class="w-full px-3 py-1.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-1 focus:ring-[#8B2452] transition-all text-sm" placeholder="0.00">
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block text-[11px] font-bold text-gray-700 uppercase tracking-wider mb-1.5">Address</label>
                    <textarea name="address" rows="2" class="w-full px-3 py-1.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-1 focus:ring-[#8B2452] transition-all text-sm" placeholder="Full address">{{ old('address', $supplier->address) }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
                    <div>
                        <label class="block text-[11px] font-bold text-gray-700 uppercase tracking-wider mb-1.5">City</label>
                        <input type="text" name="city" value="{{ old('city', $supplier->city) }}" class="w-full px-3 py-1.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-1 focus:ring-[#8B2452] transition-all text-sm" placeholder="City">
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-gray-700 uppercase tracking-wider mb-1.5">State</label>
                        <input type="text" name="state" value="{{ old('state', $supplier->state) }}" class="w-full px-3 py-1.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-1 focus:ring-[#8B2452] transition-all text-sm" placeholder="State">
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-gray-700 uppercase tracking-wider mb-1.5">Country</label>
                        <input type="text" name="country" value="{{ old('country', $supplier->country) }}" class="w-full px-3 py-1.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-1 focus:ring-[#8B2452] transition-all text-sm" placeholder="Country">
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-gray-700 uppercase tracking-wider mb-1.5">Pincode</label>
                        <input type="text" name="pincode" value="{{ old('pincode', $supplier->pincode) }}" class="w-full px-3 py-1.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-1 focus:ring-[#8B2452] transition-all text-sm" placeholder="Pincode">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="block text-[11px] font-bold text-gray-700 uppercase tracking-wider mb-1.5">GST Number</label>
                        <input type="text" name="gst_number" value="{{ old('gst_number', $supplier->gst_number) }}" class="w-full px-3 py-1.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-1 focus:ring-[#8B2452] transition-all text-sm" placeholder="GST Number">
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-gray-700 uppercase tracking-wider mb-1.5">PAN Number</label>
                        <input type="text" name="pan_number" value="{{ old('pan_number', $supplier->pan_number) }}" class="w-full px-3 py-1.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-1 focus:ring-[#8B2452] transition-all text-sm" placeholder="PAN Number">
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block text-[11px] font-bold text-gray-700 uppercase tracking-wider mb-1.5">Notes</label>
                    <textarea name="notes" rows="2" class="w-full px-3 py-1.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-1 focus:ring-[#8B2452] transition-all text-sm" placeholder="Internal notes">{{ old('notes', $supplier->notes) }}</textarea>
                </div>

                <div class="flex justify-end gap-3 mt-6 pt-2">
                    <a href="{{ admin_route('suppliers.index') }}" class="px-5 py-2 border border-gray-200 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-all">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex items-center gap-2 px-5 py-2 rounded-lg text-sm font-medium transition-all duration-200 shadow-sm" style="background: #8B2452; color: white;">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                        </svg>
                        Update Supplier
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection