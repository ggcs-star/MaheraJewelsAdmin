@extends('layouts.admin')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-lg-7">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Create Reel</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.reels.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Reel Title</label>
                                <input type="text" name="title" id="titleInput" class="form-control" required onkeyup="updatePreview()">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Product</label>
                                <select name="platform_product_id" id="productSelect" class="form-control" required onchange="updatePreview()">
                                    <option value="">Select Product</option>
                                    @foreach($products as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" id="descriptionInput" class="form-control" rows="3" maxlength="150" onkeyup="updatePreview()"></textarea>
                                <small class="text-muted">Max 150 characters</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Sort Order</label>
                                <input type="number" name="sort_order" class="form-control" value="0" placeholder="Enter sort order (lower number = higher priority)">
                                <small class="text-muted">Items will be displayed in ascending order</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Upload Reel Video</label>
                                <div class="upload-area" onclick="document.getElementById('videoInput').click()">
                                    <input type="file" id="videoInput" name="video" hidden accept="video/mp4,video/mov,video/webm" onchange="loadVideo(event)">
                                    <div class="text-center">
                                        <i class="bi bi-cloud-upload fs-1 text-primary"></i>
                                        <p class="mb-1">Drag or Click to Upload Reel Video</p>
                                        <small class="text-muted">MP4 / MOV / WEBM • Max Size: 100MB • Max Duration: 60s</small>
                                    </div>
                                </div>
                                <div id="videoError" class="text-danger mt-2"></div>
                            </div>
                            <div class="mb-3">
                                <label>Status</label>
                                <select name="status" class="form-control">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                            <button class="btn btn-primary w-100">Create Reel</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card shadow border-0">
                    <div class="card-header bg-dark text-white">Instagram Reel Live Preview</div>
                    <div class="card-body text-center">
                        <div class="reel-container">
                            <div class="video-bg">
                                <video id="videoBg" muted loop playsinline></video>
                            </div>
                            <video id="videoPreview" autoplay muted loop playsinline></video>
                            <div class="reel-overlay">
                                <h6 id="previewTitle">Reel Title</h6>
                                <p id="previewProduct"></p>
                                <p id="previewDescription"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        .reel-container{
            width:320px;
            height:570px;
            margin:auto;
            border-radius:20px;
            overflow:hidden;
            background:#000;
            position:relative;
            box-shadow:0 30px 70px rgba(0,0,0,0.3);
        }
        .reel-container video{
            position:absolute;
            top:0;
            left:0;
            width:100%;
            height:100%;
        }
        .video-bg video{
            object-fit:cover;
            filter:blur(30px);
            transform:scale(1.4);
            opacity:0.6;
        }
        #videoPreview{
            z-index:2;
            background:#000;
        }
        .reel-overlay{
            position:absolute;
            bottom:0;
            left:0;
            right:0;
            padding:18px;
            background:linear-gradient(transparent,rgba(0,0,0,0.9));
            color:#fff;
            z-index:3;
        }
        .upload-area{
            border:2px dashed #ddd;
            padding:35px;
            cursor:pointer;
            border-radius:10px;
            transition:all .3s;
        }
        .upload-area:hover{
            border-color:#6366f1;
            background:#f9fafb;
        }
    </style>
    <script>
        function updatePreview(){
            let title = document.getElementById('titleInput').value;
            let desc = document.getElementById('descriptionInput').value;
            let product = document.getElementById('productSelect');
            let productName = product.options[product.selectedIndex].text;
            document.getElementById('previewTitle').innerText = title || 'Reel Title';
            document.getElementById('previewProduct').innerText = productName;
            document.getElementById('previewDescription').innerText = desc;
        }
        function loadVideo(event){
            let file = event.target.files[0];
            let errorBox = document.getElementById("videoError");
            errorBox.innerText = "";
            if(!file) return;
            let maxSize = 100 * 1024 * 1024;
            if(file.size > maxSize){
                errorBox.innerText = "Video must be less than 100MB";
                event.target.value="";
                return;
            }
            let allowed = ["video/mp4","video/quicktime","video/webm"];
            if(!allowed.includes(file.type)){
                errorBox.innerText="Only MP4, MOV, WEBM allowed";
                event.target.value="";
                return;
            }
            let url = URL.createObjectURL(file);
            let video = document.getElementById('videoPreview');
            let bg = document.getElementById('videoBg');
            video.src = url;
            bg.src = url;
            video.onloadedmetadata = function(){
                if(video.duration > 60){
                    errorBox.innerText = "Reel must be under 60 seconds";
                    video.src="";
                    bg.src="";
                    return;
                }
                let ratio = video.videoWidth / video.videoHeight;
                if(ratio < 1){
                    video.style.objectFit = "cover";
                }else{
                    video.style.objectFit = "contain";
                }
            }
        }
    </script>
@endsection