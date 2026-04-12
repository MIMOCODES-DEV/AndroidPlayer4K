@extends('layouts.app')

@section('page-title', 'Domain URLs')
@section('page-subtitle', 'Manage all product endpoints')

@php
    $selectedId = request('product_id') ? intval(request('product_id')) : ($products->first()->id ?? null);
    $selected = $products->firstWhere('id', $selectedId);
@endphp

@section('content')
<div class="ap-card" style="width:100%;">
    <div class="ap-section-title">App Domain URLs</div>
    @if($errors->any())
        <div class="flex items-start gap-3 mb-4" style="padding:12px 16px; border-radius:10px; background:rgba(234,84,85,0.08); border:1px solid rgba(234,84,85,0.15);">
            <svg width="18" height="18" fill="none" stroke="var(--danger)" stroke-width="2" viewBox="0 0 24 24" class="shrink-0 mt-0.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span style="font-size:0.8125rem; color:var(--danger);">{{ $errors->first() }}</span>
        </div>
    @endif
    <div class="flex gap-2 mb-6 overflow-x-auto" id="product-tabs">
        @foreach($products as $product)
        <button type="button" class="ap-btn-outline product-tab-btn {{ $selected && $selected->id === $product->id ? 'tab-active' : '' }}" style="min-width:120px; font-weight:600; border-width:2px; border-color:{{ $selected && $selected->id === $product->id ? 'var(--accent)' : 'var(--border-input)' }}; color:{{ $selected && $selected->id === $product->id ? 'var(--accent)' : 'var(--text-secondary)' }}; background:{{ $selected && $selected->id === $product->id ? 'rgba(37,99,235,0.08)' : 'var(--bg-card)' }};" onclick="selectProduct({{ $product->id }})">
            {{ $product->name }}
        </button>
        @endforeach
    </div>
    <style>
    @media (max-width: 600px) {
        #product-tabs .product-tab-btn {
            min-width: 90px !important;
            font-size: 0.85rem !important;
            padding: 8px 10px !important;
        }
    }
    </style>
    @if($selected)
    <form method="POST" action="{{ route('domain-urls.update', $selected) }}">
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
    @endif
</div>
<script>
    function selectProduct(id) {
        const url = new URL(window.location.href);
        url.searchParams.set('product_id', id);
        window.location.href = url.toString();
    }
</script>
@endsection
