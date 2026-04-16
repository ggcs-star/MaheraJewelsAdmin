@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Reels Management</h4>
            <p class="text-muted mb-0">Manage your Instagram reels</p>
        </div>
        <a href="{{ route('admin.reels.create') }}" class="btn btn-primary mt-3 mt-md-0">
            <i class="bi bi-plus-lg me-2"></i>Add New Reel
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-6">
            <div class="card bg-primary text-white h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-camera-reels-fill fs-1 opacity-50"></i>
                        <div class="ms-3">
                            <h6 class="mb-1">Total Reels</h6>
                            <h3 class="mb-0 fw-bold">{{ $reels->total() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card bg-success text-white h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-eye-fill fs-1 opacity-50"></i>
                        <div class="ms-3">
                            <h6 class="mb-1">Total Views</h6>
                            <h3 class="mb-0 fw-bold">{{ number_format($reels->sum('views_count')) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card bg-danger text-white h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-heart-fill fs-1 opacity-50"></i>
                        <div class="ms-3">
                            <h6 class="mb-1">Total Likes</h6>
                            <h3 class="mb-0 fw-bold">{{ number_format($reels->sum('likes_count')) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card bg-info text-white h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-share-fill fs-1 opacity-50"></i>
                        <div class="ms-3">
                            <h6 class="mb-1">Total Shares</h6>
                            <h3 class="mb-0 fw-bold">{{ number_format($reels->sum('shares_count')) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.reels.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Search</label>
                    <input type="text" name="search" class="form-control" placeholder="Title or description..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All</option>
                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Sort By</label>
                    <select name="sort" class="form-select">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                        <option value="views" {{ request('sort') == 'views' ? 'selected' : '' }}>Most Views</option>
                        <option value="likes" {{ request('sort') == 'likes' ? 'selected' : '' }}>Most Likes</option>
                        <option value="sort_order" {{ request('sort') == 'sort_order' ? 'selected' : '' }}>Sort Order</option> <!-- YEH ADD KARO -->

                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search me-2"></i>Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Reels Table -->
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-semibold">All Reels</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 py-3" width="50">#</th>
                            <th class="py-3" width="200">Reel Details</th>
                            <th class="py-3" width="80">Preview</th>
                            <th class="py-3" width="120">Product</th>
                            <th class="py-3 text-center" width="70">Views</th>
                            <th class="py-3 text-center" width="70">Likes</th>
                            <th class="py-3 text-center" width="70">Shares</th>
                            <th class="py-3 text-center" width="80">Status</th>
<th class="py-3 text-center" width="220">Actions</th>                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reels as $reel)
                        <tr>
                            <td class="px-4 fw-semibold">{{ $loop->iteration }}</td>
                            
                            <td>
                                <div>
                                    <div class="fw-semibold">{{ $reel->title ?? 'Untitled' }}</div>
                                    <small class="text-muted d-block">{{ Str::limit($reel->description ?? 'No description', 40) }}</small>
                                    <small class="text-muted">
                                        <i class="bi bi-calendar3 me-1"></i>{{ $reel->created_at->format('d M Y') }}
                                    </small>
                                </div>
                            </td>
                            
                            <td>
                                <div class="reel-thumb" onclick="openVideo('{{ \App\Helpers\S3Helper::url($reel->video) }}')"></div>
                                    <video muted preload="metadata">
                                        <source src="{{ \App\Helpers\S3Helper::url($reel->video) }}#t=0.5" type="video/mp4">
                                    </video>
                                    <div class="play-overlay">
                                        <i class="bi bi-play-fill"></i>
                                    </div>
                                </div>
                            </td>
                            
                            <td>
                                @if($reel->platformProduct && $reel->platformProduct->product)
                                    <span class="badge bg-light text-dark border">
                                        <i class="bi bi-tag me-1"></i>{{ Str::limit($reel->platformProduct->product->name, 15) }}
                                    </span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            
                            <td class="text-center">
                                <span class="badge bg-dark px-3 py-2">
                                    <i class="bi bi-eye-fill me-1"></i>{{ number_format($reel->views_count) }}
                                </span>
                            </td>
                            
                            <td class="text-center">
                                <span class="badge bg-danger px-3 py-2">
                                    <i class="bi bi-heart-fill me-1"></i>{{ number_format($reel->likes_count) }}
                                </span>
                            </td>
                            
                            <td class="text-center">
                                <span class="badge bg-info px-3 py-2">
                                    <i class="bi bi-share-fill me-1"></i>{{ number_format($reel->shares_count) }}
                                </span>
                            </td>
                            
                            <td class="text-center">
                                @if($reel->status)
                                    <span class="badge bg-success px-3 py-2">
                                        <i class="bi bi-check-circle-fill me-1"></i>Active
                                    </span>
                                @else
                                    <span class="badge bg-secondary px-3 py-2">
                                        <i class="bi bi-x-circle-fill me-1"></i>Inactive
                                    </span>
                                @endif
                            </td>
                         <td class="text-center">

    <div class="btn-group">

        <a href="{{ route('admin.reels.edit', $reel->id) }}" 
           class="btn btn-sm btn-warning">
            <i class="bi bi-pencil-square me-1"></i> Edit
        </a>

        <button type="button"
                class="btn btn-sm btn-danger"
                onclick="deleteReel('{{ $reel->id }}')">
            <i class="bi bi-trash me-1"></i> Delete
        </button>

    </div>

    <form id="delete-form-{{ $reel->id }}"
          action="{{ route('admin.reels.destroy', $reel->id) }}"
          method="POST"
          class="d-none">
        @csrf
        @method('DELETE')
    </form>

</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <i class="bi bi-camera-reels display-4 text-muted"></i>
                                <h5 class="mt-3">No Reels Found</h5>
                                <p class="text-muted mb-3">Get started by creating your first reel</p>
                                <a href="{{ route('admin.reels.create') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-circle me-2"></i>Create Reel
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        @if(method_exists($reels, 'links'))
        <div class="card-footer bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">
                    Showing {{ $reels->firstItem() ?? 0 }} to {{ $reels->lastItem() ?? 0 }} of {{ $reels->total() }}
                </small>
                <div>
                    {{ $reels->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Video Modal -->
<div class="modal fade" id="videoModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content bg-dark border-0">
            <div class="modal-body p-0 position-relative">
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"></button>
                <div class="reel-player">
                    <video id="modalVideo" class="w-100 h-100" controls autoplay></video>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stats Modal -->
<div class="modal fade" id="statsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reel Statistics</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center" id="statsContent">
                Loading...
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center p-4">
                <i class="bi bi-exclamation-triangle-fill text-warning display-4"></i>
                <h5 class="mt-3">Delete Reel?</h5>
                <p class="text-muted small">This action cannot be undone.</p>
                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
    /* Reel Thumbnail */
    .reel-thumb {
        width: 60px;
        height: 106px;
        border-radius: 8px;
        overflow: hidden;
        position: relative;
        cursor: pointer;
        background: #000;
        border: 2px solid transparent;
        transition: all 0.2s;
    }
    
    .reel-thumb:hover {
        border-color: #0d6efd;
        transform: scale(1.05);
    }
    
    .reel-thumb video {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .play-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.2s;
    }
    
    .reel-thumb:hover .play-overlay {
        opacity: 1;
    }
    
    .play-overlay i {
        color: white;
        font-size: 20px;
        background: rgba(0,0,0,0.5);
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    /* Reel Player */
    .reel-player {
        width: 280px;
        height: 498px;
        background: #000;
    }
    
.action-buttons{
    display:flex;
    align-items:center;
    justify-content:center;
    gap:6px;
}

.action-buttons .btn{
    display:inline-flex;
    align-items:center;
    width:auto !important;
    flex:0 0 auto;
    white-space:nowrap;
}
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
        border-radius: 6px;
    }
    
    .btn-sm i {
        font-size: 1rem;
    }
    
    /* Table */
    .table thead th {
        background: #f8f9fa;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        color: #6c757d;
        border-bottom: 1px solid #dee2e6;
    }
    
    .table td {
        vertical-align: middle;
    }
    
    /* Badges */
    .badge {
        font-weight: 500;
        font-size: 0.7rem;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .reel-thumb {
            width: 50px;
            height: 88px;
        }
        
        .reel-player {
            width: 240px;
            height: 426px;
        }
        
        .btn-sm {
            padding: 0.15rem 0.3rem;
        }
        
        .btn-sm i {
            font-size: 0.9rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
// Video Player
function openVideo(url) {
    const video = document.getElementById('modalVideo');
    video.src = url;
    new bootstrap.Modal(document.getElementById('videoModal')).show();
    
    document.getElementById('videoModal').addEventListener('hidden.bs.modal', function() {
        video.pause();
        video.src = '';
    }, { once: true });
}

// Stats Modal
function showStats(id) {
    const statsModal = new bootstrap.Modal(document.getElementById('statsModal'));
    const statsContent = document.getElementById('statsContent');
    
    statsContent.innerHTML = '<div class="spinner-border text-primary my-4" role="status"></div>';
    statsModal.show();
    
    // Simulate API call - Replace with actual fetch
    setTimeout(() => {
        statsContent.innerHTML = `
            <div class="row g-3 p-3">
                <div class="col-6">
                    <div class="bg-light p-3 rounded">
                        <small class="text-muted">Views</small>
                        <h4 class="mb-0">1.2K</h4>
                    </div>
                </div>
                <div class="col-6">
                    <div class="bg-light p-3 rounded">
                        <small class="text-muted">Likes</small>
                        <h4 class="mb-0">856</h4>
                    </div>
                </div>
                <div class="col-6">
                    <div class="bg-light p-3 rounded">
                        <small class="text-muted">Shares</small>
                        <h4 class="mb-0">234</h4>
                    </div>
                </div>
                <div class="col-6">
                    <div class="bg-light p-3 rounded">
                        <small class="text-muted">Comments</small>
                        <h4 class="mb-0">67</h4>
                    </div>
                </div>
            </div>
        `;
    }, 1000);
}

// Delete Function
let deleteId = null;

function deleteReel(id) {
    deleteId = id;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
    if (deleteId) {
        document.getElementById(`delete-form-${deleteId}`).submit();
    }
});
</script>
@endpush