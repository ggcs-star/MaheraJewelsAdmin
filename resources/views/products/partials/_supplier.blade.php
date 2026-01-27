<div class="card mb-4 shadow-sm">
    <div class="card-header bg-white fw-semibold d-flex align-items-center gap-2">
        <span class="text-primary fs-5">🚚</span>
        <span>Supplier</span>
    </div>

    <div class="card-body">
        <div class="row g-3">

            <!-- Supplier Select -->
            <div class="col-md-4">
                <label class="form-label fw-semibold">
                    Select Supplier
                </label>
                <select id="supplierSelect"
                        name="supplier_id"
                        class="form-select">
                    <option value="">Select supplier</option>
                    @foreach ($suppliers as $sup)
                        <option value="{{ $sup->id }}"
                            data-name="{{ $sup->name }}"
                            data-company="{{ $sup->company_name }}"
                            data-phone="{{ $sup->phone }}"
                            data-email="{{ $sup->email }}"
                            data-type="{{ ucfirst($sup->type) }}"
                            data-commission="{{ $sup->commission_type }} ({{ $sup->commission_value }})">
                            {{ $sup->name }}
                            @if($sup->company_name)
                                ({{ $sup->company_name }})
                            @endif
                        </option>
                    @endforeach
                </select>
                <div class="form-text">
                    Optional – used for purchase & commission calculation.
                </div>
            </div>

            <!-- Supplier Details -->
            <div class="col-md-12 d-none" id="supplierBox">
                <div class="border rounded bg-light p-3">
                    <div class="fw-semibold mb-2 text-primary">
                        Supplier Details
                    </div>

                    <div class="row g-2 small">
                        <div class="col-md-3">
                            <div class="text-muted">Name</div>
                            <div id="sName">—</div>
                        </div>

                        <div class="col-md-3">
                            <div class="text-muted">Company</div>
                            <div id="sCompany">—</div>
                        </div>

                        <div class="col-md-3">
                            <div class="text-muted">Phone</div>
                            <div id="sPhone">—</div>
                        </div>

                        <div class="col-md-3">
                            <div class="text-muted">Email</div>
                            <div id="sEmail">—</div>
                        </div>

                        <div class="col-md-3">
                            <div class="text-muted">Type</div>
                            <div id="sType">—</div>
                        </div>

                        <div class="col-md-3">
                            <div class="text-muted">Commission</div>
                            <div id="sCommission" class="fw-semibold text-danger">—</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
