@extends('layouts.app')

@section('page-title', 'Domain URLs')
@section('page-subtitle', 'Manage all product endpoints')

@php
    $selectedId = request('product_id') ? intval(request('product_id')) : ($products->first()->id ?? null);
    $selected = $products->firstWhere('id', $selectedId);
@endphp

@section('content')
<div class="ap-section-title">App Domain URLs</div>
@if($errors->any())
    <div class="flex items-start gap-3 mb-4" style="padding:12px 16px; border-radius:10px; background:rgba(234,84,85,0.08); border:1px solid rgba(234,84,85,0.15);">
        <svg width="18" height="18" fill="none" stroke="var(--danger)" stroke-width="2" viewBox="0 0 24 24" class="shrink-0 mt-0.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span style="font-size:0.8125rem; color:var(--danger);">{{ $errors->first() }}</span>
    </div>
@endif
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
        <div style="width:36px; height:36px; border-radius:10px; background:rgba(40,199,111,0.12); display:flex; align-items:center; justify-content:center;">
            <svg width="18" height="18" fill="none" stroke="var(--success)" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 010 20M12 2a15.3 15.3 0 000 20"/></svg>
        </div>
        <div>
            <div style="font-size:0.9375rem; font-weight:700; color:var(--text-primary);">{{ $selected->name }}</div>
            <div style="font-size:0.75rem; color:var(--text-muted);">Domain URL</div>
        </div>
    </div>
    <form method="POST" action="{{ route('domain-urls.update', $selected) }}">
        <input type="hidden" name="product_id" value="{{ $selected->id }}" />
        @csrf
        @method('PUT')
        <div class="mb-5">
            <label class="ap-label">Domain URL</label>
            <input type="url" name="url" value="{{ old('url', $selected->domainUrl?->url) }}" class="ap-input" placeholder="https://example.com" required />
        </div>
        <div style="display: flex; justify-content: flex-end;">
            <button type="submit" class="ap-btn">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                Update URL
            </button>
        </div>
    </form>
</div>
@endif
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
