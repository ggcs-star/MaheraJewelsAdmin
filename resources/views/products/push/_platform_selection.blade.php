<div class="card border-0 shadow-sm rounded-4 mb-4" id="platformSelectionCard" style="display:none;">
    <div class="card-header bg-white border-bottom py-2">
        <h6 class="fw-bold mb-0">Select Sales Platforms</h6>
        <small class="text-muted">Uncheck to disable and release allocated stock</small>
    </div>
    <div class="card-body">
        <div class="d-flex flex-wrap gap-3">
            @foreach($platforms as $platform)
                <label class="d-flex align-items-center gap-2 border rounded-3 px-3 py-2 cursor-pointer platform-option"
                       style="background: #f8f9fa; transition: all 0.2s;"
                       onmouseover="this.style.background='#e9ecef'"
                       onmouseout="this.style.background='#f8f9fa'">
                    
                    <input type="checkbox"
                           class="platform-checkbox"
                           value="{{ $platform->id }}"
                           data-platform-id="{{ $platform->id }}"
                           data-platform-name="{{ $platform->name }}"
                           id="platform_checkbox_{{ $platform->id }}">

                    <div class="d-flex align-items-center gap-2">
                        @php $name = strtolower($platform->name); @endphp
                        @if(str_contains($name, 'amazon'))
                            <span class="fw-bold" style="color:#232F3E;">Amazon</span>
                        @elseif(str_contains($name, 'flipkart'))
                            <span class="fw-bold" style="color:#2874F0;">Flipkart</span>
                        @elseif(str_contains($name, 'website'))
                            <span>🌐 <span class="fw-semibold">Own Website</span></span>
                        @elseif(str_contains($name, 'offline'))
                            <span>🏪 <span class="fw-semibold">Offline</span></span>
                        @elseif(str_contains($name, 'meesho'))
                            <span>🛍️ <span class="fw-semibold">Meesho</span></span>
                        @else
                            <span class="fw-semibold">{{ ucfirst($platform->name) }}</span>
                        @endif
                    </div>
                </label>
            @endforeach
        </div>
    </div>
</div>