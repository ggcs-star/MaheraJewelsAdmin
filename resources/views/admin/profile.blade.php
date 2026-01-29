@extends('layouts.admin')

<script>
    window.profileConfig = {
        userInitials: "{{ strtoupper(collect(explode(' ', Auth::user()->name))->map(fn($w)=>substr($w,0,1))->take(2)->implode('')) }}",
        hasProfileImage: {{ Auth::user()->profile_image ? 'true' : 'false' }}
    };
</script>

@push('scripts')
<script src="{{ asset('assets/js/admin/profile.js') }}"></script>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-6">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white border-bottom py-4 px-5">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0 fw-bold text-dark">My Profile</h5>
                            <p class="text-muted small mb-0 mt-1">Manage your account details</p>
                        </div>
                        <button type="button"
                                id="editProfileBtn"
                                class="btn btn-outline-primary btn-sm px-4">
                            Edit Profile
                        </button>
                    </div>
                </div>

                <div class="card-body p-5">
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show rounded-2 shadow-sm mb-4" role="alert" id="successAlert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show rounded-2 shadow-sm mb-4" role="alert" id="errorAlert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show rounded-2 shadow-sm mb-4" role="alert" id="validationAlert">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    <form method="POST"
                          action="{{ route('admin.profile.update') }}"
                          enctype="multipart/form-data"
                          id="profileForm">
                        @csrf

                        <div class="text-center mb-5">
                            <input type="file"
                                   name="profile_image"
                                   id="profileImageInput"
                                   class="d-none"
                                   accept="image/png,image/jpeg,image/jpg"
                                   disabled>
                            
                            <input type="hidden" name="remove_profile_image" id="removeProfileImage" value="0">
                            
                            <label for="profileImageInput"
                                   id="avatarLabel"
                                   style="cursor: default;">
                                @if(Auth::user()->profile_image)
                                <div class="position-relative d-inline-block" id="avatarContainer">
                                    <img
                                        id="profileAvatarPreview"
                                        src="{{ asset('storage/' . Auth::user()->profile_image) }}"
                                        class="rounded-circle shadow"
                                        width="140"
                                        height="140"
                                        style="object-fit: cover;"
                                    >
                                    <div class="position-absolute bottom-0 end-0 bg-primary rounded-circle p-2 border border-3 border-white">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="white">
                                            <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                                        </svg>
                                    </div>
                                </div>
                                @else
                                <div class="position-relative d-inline-block" id="avatarContainer">
                                    <div
                                        id="profileAvatarPreview"
                                        class="d-flex align-items-center justify-content-center
                                               rounded-circle shadow
                                               text-white fw-bold"
                                        style="
                                            width:140px;
                                            height:140px;
                                            font-size: 3rem;
                                            background: linear-gradient(135deg,#4f46e5,#7c3aed);
                                        "
                                    >
                                        {{ strtoupper(
                                            collect(explode(' ', Auth::user()->name))
                                                ->map(fn($w) => substr($w,0,1))
                                                ->take(2)
                                                ->implode('')
                                        ) }}
                                    </div>
                                    <div class="position-absolute bottom-0 end-0 bg-primary rounded-circle p-2 border border-3 border-white">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="white">
                                            <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                                        </svg>
                                    </div>
                                </div>
                                @endif
                            </label>
                            
                            <div class="mt-3">
                                <p class="text-muted small mb-2 d-none" id="avatarHint">
                                    Click on avatar to change photo
                                </p>
                                <button type="button" 
                                        id="removeImageBtn" 
                                        class="btn btn-sm btn-outline-danger d-none">
                                    Remove Photo
                                </button>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-medium text-dark mb-2">
                                    Full Name <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                       name="name"
                                       value="{{ old('name', Auth::user()->name) }}"
                                       class="form-control rounded-2 border py-3 @error('name') is-invalid @enderror"
                                       readonly
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-medium text-dark mb-2">
                                    Email Address
                                </label>
                                <input type="email"
                                       value="{{ Auth::user()->email }}"
                                       class="form-control rounded-2 border py-3 bg-light"
                                       readonly>
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-medium text-dark mb-2">
                                    Mobile Number <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                       name="mobile"
                                       value="{{ old('mobile', Auth::user()->mobile) }}"
                                       class="form-control rounded-2 border py-3 @error('mobile') is-invalid @enderror"
                                       readonly
                                       required
                                       pattern="[0-9]{10}"
                                       maxlength="10">
                                @error('mobile')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mb-4">
                                <label class="form-label fw-medium text-dark mb-2">
                                    Address
                                </label>
                                <textarea name="address"
                                          class="form-control rounded-2 border py-3 @error('address') is-invalid @enderror"
                                          rows="3"
                                          readonly>{{ old('address', Auth::user()->address) }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-3 pt-4 border-top">
                            <button type="button"
                                    id="cancelEditBtn"
                                    class="btn btn-outline-secondary px-4 d-none">
                                Cancel
                            </button>
                            <button type="submit"
                                    id="saveProfileBtn"
                                    class="btn btn-primary px-4 d-none">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection