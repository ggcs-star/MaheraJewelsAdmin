<div class="card mb-4 shadow-sm" id="platformSelectionCard" style="display:none;">
    <div class="card-header fw-semibold">🌐 Select Platforms</div>

    <div class="card-body d-flex gap-4 flex-wrap">
        @foreach($platforms as $platform)
            <label class="d-flex align-items-center gap-2">
               <input
    type="checkbox"
    class="platform-checkbox"
    name="platforms[]"
    value="{{ $platform->id }}"
    data-platform-id="{{ $platform->id }}"
    data-platform-name="{{ $platform->name }}"
>

                {{ ucfirst($platform->name) }}
            </label>
        @endforeach
    </div>
</div>
