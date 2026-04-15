@extends('layouts.app')

@section('page-title', 'Contact Details')
@section('page-subtitle', 'Manage app support info')

@php
    $selectedId = request('product_id') ? intval(request('product_id')) : ($products->first()->id ?? null);
    $selected = $products->firstWhere('id', $selectedId);
@endphp

@section('content')
<div>
    <div class="ap-section-title">App Contact Details</div>
    @if($errors->any())
        <div class="flex items-start gap-3 mb-4" style="padding:12px 16px; border-radius:10px; background:rgba(234,84,85,0.08); border:1px solid rgba(234,84,85,0.15);">
            <svg width="18" height="18" fill="none" stroke="var(--danger)" stroke-width="2" viewBox="0 0 24 24" class="shrink-0 mt-0.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div>@foreach($errors->all() as $error)<p style="font-size:0.8125rem; color:var(--danger);">{{ $error }}</p>@endforeach</div>
        </div>
    @endif
    <div class="flex gap-2 mb-6" style="flex-wrap:wrap;" id="product-tabs">
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
                <svg width="18" height="18" fill="none" stroke="var(--success)" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <div style="font-size:0.9375rem; font-weight:700; color:var(--text-primary);">{{ $selected->name }}</div>
                <div style="font-size:0.75rem; color:var(--text-muted);">Contact Details</div>
            </div>
        </div>
        <form method="POST" action="{{ route('contact-details.update', $selected) }}">
            <input type="hidden" name="product_id" value="{{ $selected->id }}" />
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="ap-label">Email</label>
                    <input type="email" name="email" value="{{ old('email', $selected->contactDetail?->email) }}" class="ap-input" placeholder="info@example.com" />
                </div>
                <div>
                    <label class="ap-label">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $selected->contactDetail?->phone) }}" class="ap-input" placeholder="+1 000 000 0000" />
                </div>
            </div>
            <div class="mb-5">
                <label class="ap-label">Info</label>
                <textarea name="info" rows="4" class="ap-input" style="resize:none;" placeholder="Additional contact information...">{{ old('info', $selected->contactDetail?->info) }}</textarea>
            </div>
            <div style="display: flex; justify-content: flex-end;">
                <button type="submit" class="ap-btn">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    Update Contact
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
