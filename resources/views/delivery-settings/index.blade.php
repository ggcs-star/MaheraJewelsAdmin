@extends('layouts.admin.admin-settings')

@section('settings-content')

<div class="container-fluid">

<div class="d-flex justify-content-between align-items-center mb-4">
<h4 class="fw-bold mb-0">Delivery Settings</h4>

<a href="{{ admin_route('delivery-settings.create') }}"
class="btn btn-primary">
+ Add Setting
</a>
</div>



<div class="card shadow-sm mb-4">
<div class="card-body">

<form method="GET" id="deliveryFilterForm" class="row g-3 align-items-end">

<div class="col-md-4">

<label class="form-label fw-semibold">Search</label>

<div class="position-relative">

<input
type="text"
name="search"
id="deliverySearch"
class="form-control pe-5"
placeholder="Delivery / Platform / Tax"
value="{{ request('search') }}"
autocomplete="off">

<span
id="clearDeliverySearch"
class="position-absolute top-50 end-0 translate-middle-y me-3 text-muted"
style="cursor:pointer;display:none;">
✕
</span>

</div>

</div>


<div class="col-md-2">

<button
type="button"
id="openDeliveryFilterSidebar"
class="btn btn-secondary w-100">

Filter

</button>

</div>

</form>

</div>
</div>



<form method="POST"
action="{{ admin_route('delivery-settings.bulk-delete') }}"
id="deliveryBulkDeleteForm">

@csrf

<button
type="button"
id="bulkDeleteDeliveryBtn"
class="btn btn-danger mb-3">

Delete Selected

</button>

</form>



<div class="card shadow-sm">

<div class="table-responsive">

<table class="table table-hover align-middle mb-0">

<thead class="table-light">

<tr>

<th width="40">
<input type="checkbox" id="selectAllDelivery">
</th>

<th>ID</th>
<th>Delivery Fee</th>
<th>Platform Fee</th>
<th>Tax %</th>
<th>Free Delivery Above</th>

<th width="150">Actions</th>

</tr>

</thead>

<tbody>

@forelse($settings as $setting)

<tr style="cursor:pointer"
onclick="window.location='{{ admin_route('delivery-settings.show',$setting) }}'">

<td onclick="event.stopPropagation()">

<input type="checkbox"
class="delivery-row-checkbox"
name="ids[]"
value="{{ $setting->id }}"
form="deliveryBulkDeleteForm">

</td>

<td>{{ $setting->id }}</td>
<td>{{ $setting->delivery_fee }}</td>
<td>{{ $setting->platform_fee }}</td>
<td>{{ $setting->tax_percent }}</td>
<td>{{ $setting->free_delivery_above }}</td>


<td onclick="event.stopPropagation()">

<div class="d-flex gap-1">

<a href="{{ admin_route('delivery-settings.edit',$setting) }}"
class="btn btn-sm btn-primary">
Edit
</a>

<form
action="{{ admin_route('delivery-settings.destroy',$setting) }}"
method="POST"
onsubmit="return confirm('Delete this record?');">

@csrf
@method('DELETE')

<button class="btn btn-sm btn-danger">
Delete
</button>

</form>

</div>

</td>

</tr>

@empty

<tr>
<td colspan="7" class="text-center py-4 text-muted">
No records found
</td>
</tr>

@endforelse

</tbody>

</table>

</div>


@if($settings->hasPages())

<div class="card-footer">
{{ $settings->withQueryString()->links() }}
</div>

@endif

</div>



<div id="deliveryFilterSidebar" class="filter-sidebar">

<div class="filter-sidebar-header">

<h5>Advanced Filter</h5>

<button type="button" id="closeDeliveryFilterSidebar">✕</button>

</div>


<div class="filter-sidebar-body">

<div class="mb-3">

<label>Field</label>

<select id="advField" class="form-control">

<option value="delivery_fee">Delivery Fee</option>
<option value="platform_fee">Platform Fee</option>
<option value="tax_percent">Tax</option>
<option value="free_delivery_above">Free Delivery</option>

</select>

</div>


<div class="mb-3">

<label>Condition</label>

<select id="advCondition" class="form-control">

<option value="like">Contains</option>
<option value="=">Equals</option>
<option value="!=">Not Equals</option>
<option value="starts_with">Starts With</option>
<option value="ends_with">Ends With</option>

</select>

</div>


<div class="mb-3">

<label>Value</label>

<input type="text" id="advValue" class="form-control">

</div>


<button
type="button"
id="applyDeliveryAdvancedFilter"
class="btn btn-primary w-100">

Apply Filter

</button>


<button
type="button"
id="clearDeliveryAdvancedFilter"
class="btn btn-outline-secondary w-100 mt-2">

Clear Filter

</button>

</div>

</div>


</div>



<script>

let searchInput = document.getElementById("deliverySearch");
let clearSearch = document.getElementById("clearDeliverySearch");
let form = document.getElementById("deliveryFilterForm");

if(searchInput.value.length > 0){
clearSearch.style.display="block";
}

searchInput.addEventListener("keyup",function(){

if(this.value.length > 0){
clearSearch.style.display="block";
}else{
clearSearch.style.display="none";
}

setTimeout(()=>form.submit(),400);

});

clearSearch.onclick=function(){

searchInput.value="";

form.submit();

}



document.getElementById("openDeliveryFilterSidebar").onclick=function(){
document.getElementById("deliveryFilterSidebar").classList.add("show");
}

document.getElementById("closeDeliveryFilterSidebar").onclick=function(){
document.getElementById("deliveryFilterSidebar").classList.remove("show");
}


document.getElementById("applyDeliveryAdvancedFilter").onclick=function(){

let field=document.getElementById("advField").value;
let cond=document.getElementById("advCondition").value;
let value=document.getElementById("advValue").value;

let url=new URL(window.location.href);

url.searchParams.set("adv_field",field);
url.searchParams.set("adv_condition",cond);
url.searchParams.set("adv_value",value);

window.location=url;

}

document.getElementById("clearDeliveryAdvancedFilter").onclick=function(){

let url=new URL(window.location.href);

url.searchParams.delete("adv_field");
url.searchParams.delete("adv_condition");
url.searchParams.delete("adv_value");

window.location=url;

}

</script>


@endsection