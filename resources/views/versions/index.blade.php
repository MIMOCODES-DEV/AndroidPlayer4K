@extends('layouts.app')

@section('page-title', 'Update Version')
@section('page-subtitle', 'Push new app releases')

{{-- $selected and $version come from the controller --}}

@section('content')
<div>
    <div class="ap-section-title">App Versions</div>
    <div class="flex gap-2 mb-6 overflow-x-auto" id="product-tabs">
        @foreach($products as $product)
        <button type="button" class="ap-btn-outline product-tab-btn {{ $selected && $selected->id === $product->id ? 'tab-active' : '' }}" style="min-width:140px; font-weight:600; border-width:2px; border-color:{{ $selected && $selected->id === $product->id ? 'var(--accent)' : 'var(--border-input)' }}; color:{{ $selected && $selected->id === $product->id ? 'var(--accent)' : 'var(--text-secondary)' }}; background:{{ $selected && $selected->id === $product->id ? 'rgba(37,99,235,0.08)' : 'var(--bg-card)' }};" onclick="selectProduct({{ $product->id }})">
            {{ $product->name }}
        </button>
        @endforeach
    </div>
    @if($selected)
    <div class="ap-card" style="width:100%;">
        <div class="flex items-center gap-3 mb-5" style="padding-bottom:14px; border-bottom:1px solid var(--border);">
            <div style="width:36px; height:36px; border-radius:10px; background:rgba(255,159,67,0.12); display:flex; align-items:center; justify-content:center;">
                <svg width="18" height="18" fill="none" stroke="var(--warning)" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            </div>
            <div>
                <div style="font-size:0.9375rem; font-weight:700; color:var(--text-primary);">{{ $selected->name }}</div>
                <div style="font-size:0.75rem; color:var(--text-muted);">Version & Release Management</div>
            </div>
        </div>

        @if($errors->any())
            <div class="flex items-start gap-3 mb-4" style="padding:12px 16px; border-radius:10px; background:rgba(234,84,85,0.08); border:1px solid rgba(234,84,85,0.15);">
                <svg width="18" height="18" fill="none" stroke="var(--danger)" stroke-width="2" viewBox="0 0 24 24" class="shrink-0 mt-0.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div>@foreach($errors->all() as $error)<p style="font-size:0.8125rem; color:var(--danger);">{{ $error }}</p>@endforeach</div>
            </div>
        @endif

        <form method="POST" action="{{ route('versions.update', $selected) }}" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="ap-label">Version Number</label>
                    <input type="text" name="version" value="{{ old('version', $version?->version) }}" class="ap-input" placeholder="e.g. 1.2.0" />
                </div>
                <div>
                    <label class="ap-label">Description</label>
                    <input type="text" name="description" value="{{ old('description', $version?->description) }}" class="ap-input" placeholder="What changed in this version?" />
                </div>
            </div>

            <div class="mb-5">
                <label class="ap-label">APK / File</label>
                @if($version?->file)
                    <div class="flex items-center gap-2 mb-3" style="padding:10px 14px; border-radius:10px; background:rgba(105,108,255,0.06); border:1px solid rgba(105,108,255,0.12);">
                        <svg width="16" height="16" fill="none" stroke="var(--accent)" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                        <span style="font-size:0.8125rem; color:var(--text-secondary);">Current:</span>
                        <a href="{{ asset('storage/' . $version->file) }}" target="_blank"
                           style="font-size:0.8125rem; color:var(--accent); text-decoration:none;"
                           onmouseover="this.style.textDecoration='underline';" onmouseout="this.style.textDecoration='none';">
                            {{ basename($version->file) }}
                        </a>
                    </div>
                @endif
                <input type="file" name="file" accept=".apk,.png,.jpg,.jpeg,.pdf,.svg" class="ap-input" />
                <p style="font-size:0.75rem; color:var(--text-muted); margin-top:6px;">APK, PNG, JPG, PDF, SVG — max 50 MB. Leave empty to keep current file.</p>
            </div>

            <div style="display: flex; justify-content: flex-end;">
                <button type="submit" class="ap-btn">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    Update Version
                </button>
            </div>
        </form>
    </div>
    @endif
</div>
<script>
    function selectProduct(id) {
        const url = new URL(window.location.href);
        url.searchParams.set('product_id', id);
        window.location.href = url.toString();
    }

    // On page load, scroll the selected tab into view (centered)
    document.addEventListener('DOMContentLoaded', function() {
        var selectedTab = document.querySelector('#product-tabs .tab-active');
        if (selectedTab && selectedTab.scrollIntoView) {
            selectedTab.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
        }
    });
</script>
@endsection
