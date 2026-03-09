@extends('layouts.admin')

@section('content')

<div class="container-fluid">

<div class="card shadow-sm border-0">

<div class="card-header bg-white d-flex justify-content-between align-items-center">

<h5 class="mb-0 fw-semibold">Reels</h5>

<a href="{{ route('admin.reels.create') }}" class="btn btn-primary">
+ Add Reel
</a>

</div>

<div class="card-body p-0">

<div class="table-responsive">

<table class="table table-hover align-middle mb-0">

<thead class="table-light">

<tr>

<th style="width:70px">#</th><th>Title</th>
<th style="width:140px">Preview</th>
<th style="width:120px">Status</th>
<th style="width:180px">Action</th>

</tr>

</thead>

<tbody>

@forelse($reels as $reel)

<tr>

<td class="fw-semibold">
{{ $loop->iteration }}
</td>

<td>

<div class="fw-semibold">
{{ $reel->title ?? 'No Title' }}
</div>

<small class="text-muted">
{{ Str::limit($reel->description,50) }}
</small>

</td>

<td>

<div class="reel-thumb"
onclick="openVideo('{{ Storage::disk('s3')->url($reel->video) }}')">

<video muted preload="metadata">

<source src="{{ Storage::disk('s3')->url($reel->video) }}#t=0.5" type="video/mp4">

</video>

<div class="play-icon">
▶
</div>

</div>

</td>

<td>

@if($reel->status)

<span class="badge bg-success">
Active
</span>

@else

<span class="badge bg-secondary">
Inactive
</span>

@endif

</td>

<td>

<a href="{{ route('admin.reels.edit',$reel->id) }}"
class="btn btn-sm btn-warning">

Edit

</a>

<form
action="{{ route('admin.reels.destroy',$reel->id) }}"
method="POST"
class="d-inline">

@csrf
@method('DELETE')

<button
class="btn btn-sm btn-danger"
onclick="return confirm('Delete this reel?')">

Delete

</button>

</form>

</td>

</tr>

@empty

<tr>

<td colspan="5" class="text-center text-muted p-4">

No reels found

</td>

</tr>

@endforelse

</tbody>

</table>

</div>

</div>

@if(method_exists($reels,'links'))

<div class="card-footer bg-white">

{{ $reels->links() }}

</div>

@endif

</div>

</div>


<!-- VIDEO MODAL -->

<div class="modal fade" id="videoModal" tabindex="-1">

<div class="modal-dialog modal-dialog-centered">

<div class="modal-content">

<div class="modal-body p-0 text-center">

<div class="reel-player">

<video id="modalVideo" controls autoplay></video>

</div>

</div>

</div>

</div>

</div>


<style>

/* reel thumbnail */

.reel-thumb{

width:90px;
height:150px;
border-radius:10px;
overflow:hidden;
position:relative;
cursor:pointer;
background:#000;

}

.reel-thumb video{

width:100%;
height:100%;
object-fit:cover;

}

/* play icon */

.play-icon{

position:absolute;
top:50%;
left:50%;
transform:translate(-50%,-50%);
font-size:20px;
color:#fff;
background:rgba(0,0,0,0.5);
width:35px;
height:35px;
display:flex;
align-items:center;
justify-content:center;
border-radius:50%;

}

/* modal reel player */

.reel-player{

width:320px;
height:570px;
margin:auto;
background:#000;
border-radius:14px;
overflow:hidden;

}

.reel-player video{

width:100%;
height:100%;
object-fit:contain;

}

</style>


<script>

function openVideo(url){

let modalVideo = document.getElementById('modalVideo')

modalVideo.src = url

let modal = new bootstrap.Modal(document.getElementById('videoModal'))

modal.show()

}

</script>

@endsection