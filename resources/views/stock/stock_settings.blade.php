@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4 py-4" style="background: #f1f5f9; min-height: 100vh;">
    <div class="mb-4">
        <h1 class="h3 mb-1" style="color: #0f172a; font-weight: 700;">Stock Alert Settings</h1>
        <p class="text-muted small mb-0">Configure low stock threshold and alert email address</p>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card" style="border: none; border-radius: 24px; background: #ffffff;">
                <div class="card-header bg-white" style="border-radius: 24px 24px 0 0; border-bottom: 1px solid #e2e8f0; padding: 20px 24px;">
                    <div class="d-flex align-items-center gap-2">
                        <div style="background: #fef3c7; padding: 8px; border-radius: 12px;">
                            <i class="fas fa-bell" style="color: #f59e0b; font-size: 18px;"></i>
                        </div>
                        <h5 class="mb-0 fw-bold" style="color: #0f172a;">Alert Configuration</h5>
                    </div>
                </div>
                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert" style="background: #f0fdf4; border: none; border-radius: 16px; color: #166534;">
                            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.stock.settings.update') }}">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label fw-semibold" style="color: #1e293b;">Low Stock Threshold</label>
                            <div class="input-group">
                                <span class="input-group-text" style="background: #f8fafc; border-right: none; border-radius: 12px 0 0 12px;">
                                    <i class="fas fa-exclamation-triangle" style="color: #f97316;"></i>
                                </span>
                                <input type="number" name="threshold" class="form-control" value="{{ $setting->threshold }}" required style="border-left: none; border-radius: 0 12px 12px 0; padding: 10px 15px;">
                            </div>
                            <small class="text-muted mt-2 d-block">When remaining stock reaches or goes below this number, an email alert will be sent.</small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold" style="color: #1e293b;">Admin Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text" style="background: #f8fafc; border-right: none; border-radius: 12px 0 0 12px;">
                                    <i class="fas fa-envelope" style="color: #3b82f6;"></i>
                                </span>
                                <input type="email" name="admin_email" class="form-control" value="{{ $setting->admin_email }}" required style="border-left: none; border-radius: 0 12px 12px 0; padding: 10px 15px;">
                            </div>
                            <small class="text-muted mt-2 d-block">Low stock alerts will be sent to this email address.</small>
                        </div>

                        <div class="d-flex gap-3 mt-4">
                            <button type="submit" class="btn" style="background: #4f46e5; color: white; border-radius: 12px; padding: 10px 24px; font-weight: 500;">
                                <i class="fas fa-save me-2"></i> Save Changes
                            </button>
                            <a href="{{ route('admin.stock.index') }}" class="btn" style="background: #f1f5f9; color: #475569; border-radius: 12px; padding: 10px 24px; font-weight: 500;">
                                <i class="fas fa-arrow-left me-2"></i> Back to Stock Management
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card" style="border: none; border-radius: 24px; background: #ffffff;">
                <div class="card-header bg-white" style="border-radius: 24px 24px 0 0; border-bottom: 1px solid #e2e8f0; padding: 20px 24px;">
                    <div class="d-flex align-items-center gap-2">
                        <div style="background: #e0e7ff; padding: 8px; border-radius: 12px;">
                            <i class="fas fa-info-circle" style="color: #4f46e5; font-size: 18px;"></i>
                        </div>
                        <h5 class="mb-0 fw-bold" style="color: #0f172a;">How It Works</h5>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex gap-3 mb-4">
                        <div style="background: #eef2ff; width: 40px; height: 40px; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                            <span class="fw-bold" style="color: #4f46e5;">1</span>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1" style="color: #0f172a;">Set Threshold</h6>
                            <p class="text-muted small mb-0">Set the minimum stock quantity that triggers an alert.</p>
                        </div>
                    </div>
                    <div class="d-flex gap-3 mb-4">
                        <div style="background: #eef2ff; width: 40px; height: 40px; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                            <span class="fw-bold" style="color: #4f46e5;">2</span>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1" style="color: #0f172a;">System Checks</h6>
                            <p class="text-muted small mb-0">System automatically checks all products and variants for low stock.</p>
                        </div>
                    </div>
                    <div class="d-flex gap-3 mb-4">
                        <div style="background: #eef2ff; width: 40px; height: 40px; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                            <span class="fw-bold" style="color: #4f46e5;">3</span>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1" style="color: #0f172a;">Email Alert</h6>
                            <p class="text-muted small mb-0">Email is sent to configured admin email with list of low stock items.</p>
                        </div>
                    </div>
                    <div class="d-flex gap-3">
                        <div style="background: #eef2ff; width: 40px; height: 40px; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                            <span class="fw-bold" style="color: #4f46e5;">4</span>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1" style="color: #0f172a;">Take Action</h6>
                            <p class="text-muted small mb-0">Restock the products to maintain inventory levels.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-4" style="border: none; border-radius: 24px; background: #ffffff;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3">
                        <div style="background: #fef2f2; padding: 12px; border-radius: 16px;">
                            <i class="fas fa-clock" style="color: #dc2626; font-size: 24px;"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1" style="color: #0f172a;">Automated Checks</h6>
                            <p class="text-muted small mb-0">Low stock check runs automatically every 6 hours. You will receive email alerts only when products fall below threshold.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .form-control:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79,70,229,0.1);
        outline: none;
    }
    .btn:hover {
        transform: translateY(-1px);
        transition: all 0.2s ease;
    }
    .alert {
        border-left: 4px solid #10b981;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
@endsection