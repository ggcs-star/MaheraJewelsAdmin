<div class="card mb-4 shadow-sm" id="platformSelectionCard" style="display:none;">
<div class="card-header fw-semibold"> 🛍️Select Platforms</div>

    <div class="card-body d-flex gap-4 flex-wrap">

        @foreach($platforms as $platform)
            @php
                $platformName = strtolower(trim($platform->name));
            @endphp

            <label class="d-flex align-items-center gap-3 border rounded px-3 py-2 shadow-sm cursor-pointer platform-option">

                <input
                    type="checkbox"
                    class="platform-checkbox"
                    name="platforms[]"
                    value="{{ $platform->id }}"
                    data-platform-id="{{ $platform->id }}"
                    data-platform-name="{{ $platform->name }}"
                >

                <div class="d-flex align-items-center gap-2">

                    {{-- AMAZON --}}
                    @if(str_contains($platformName,'amazon'))
                        <svg viewBox="0 0 200 60" class="h-5 w-auto">
                            <text x="0" y="42" font-size="38" font-weight="700" fill="#111">amazon</text>
                            <path d="M10 50 C40 70, 120 70, 150 50"
                                  stroke="#FF9900" stroke-width="5"
                                  fill="none" stroke-linecap="round"/>
                        </svg>

                    {{-- FLIPKART --}}
                    @elseif(str_contains($platformName,'flipkart'))
                        <svg viewBox="0 0 64 64" class="h-6 w-auto">
                            <rect width="64" height="64" rx="14" fill="#2874F0"/>
                            <text x="32" y="44" text-anchor="middle"
                                  font-size="40" font-weight="800"
                                  fill="#FFD700">F</text>
                        </svg>
{{-- WEBSITE --}}
@elseif(str_contains($platformName,'website'))

<span class="text-xl leading-none">🌐</span>

{{-- DEFAULT --}}
@else
    <div class="w-8 h-8 bg-gray-200 rounded-full"></div>
@endif


                    <span class="fw-semibold">{{ ucfirst($platform->name) }}</span>
                </div>
            </label>

        @endforeach

    </div>
</div>
