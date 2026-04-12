@extends('layouts.app')

@section('page-title', 'Manage Products')
@section('page-subtitle', 'All registered applications')

@section('header-actions')
<a href="{{ route('products.create') }}" class="ap-btn" style="font-size:0.8125rem; padding:8px 18px;">
    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
    Add Product
</a>
@endsection

@section('content')

{{-- Stats row --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
    <div class="ap-card flex items-center gap-4" style="padding:18px 20px;">
        <div style="width:42px; height:42px; border-radius:12px; background:rgba(105,108,255,0.12); display:flex; align-items:center; justify-content:center;">
            <svg width="20" height="20" fill="none" stroke="var(--accent)" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
        </div>
        <div>
            <div style="font-size:1.25rem; font-weight:700; color:var(--text-primary);">{{ $products->count() }}</div>
            <div style="font-size:0.75rem; color:var(--text-muted);">Total Products</div>
        </div>
    </div>
    <div class="ap-card flex items-center gap-4" style="padding:18px 20px;">
        <div style="width:42px; height:42px; border-radius:12px; background:rgba(40,199,111,0.12); display:flex; align-items:center; justify-content:center;">
            <svg width="20" height="20" fill="none" stroke="var(--success)" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
            <div style="font-size:1.25rem; font-weight:700; color:var(--text-primary);">{{ $products->where('is_active', true)->count() }}</div>
            <div style="font-size:0.75rem; color:var(--text-muted);">Active</div>
        </div>
    </div>
    <div class="ap-card flex items-center gap-4" style="padding:18px 20px;">
        <div style="width:42px; height:42px; border-radius:12px; background:rgba(255,159,67,0.12); display:flex; align-items:center; justify-content:center;">
            <svg width="20" height="20" fill="none" stroke="var(--warning)" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101"/></svg>
        </div>
        <div>
            <div style="font-size:1.25rem; font-weight:700; color:var(--text-primary);">{{ $products->filter(fn($p) => $p->domainUrl?->url)->count() }}</div>
            <div style="font-size:0.75rem; color:var(--text-muted);">With Domain</div>
        </div>
    </div>
    <div class="ap-card flex items-center gap-4" style="padding:18px 20px;">
        <div style="width:42px; height:42px; border-radius:12px; background:rgba(234,84,85,0.12); display:flex; align-items:center; justify-content:center;">
            <svg width="20" height="20" fill="none" stroke="var(--danger)" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
        </div>
        <div>
            <div style="font-size:1.25rem; font-weight:700; color:var(--text-primary);">{{ $products->filter(fn($p) => $p->version?->version)->count() }}</div>
            <div style="font-size:0.75rem; color:var(--text-muted);">With Version</div>
        </div>
    </div>
</div>

{{-- Products Table --}}
<div class="ap-card" style="padding:0; overflow:hidden;">
    <div style="padding:20px 24px; border-bottom:1px solid var(--border);">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <div>
                <div style="font-size:0.9375rem; font-weight:700; color:var(--text-primary);">Products List</div>
                <div style="font-size:0.8125rem; color:var(--text-muted); margin-top:2px;">Edit any product to update its domain URL, contact details, and version.</div>
            </div>
            <a href="{{ route('products.create') }}" class="ap-btn" style="font-size:0.8125rem; padding:8px 18px;">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                Add Product
            </a>
        </div>
    </div>

    <div style="overflow-x:auto; -webkit-overflow-scrolling:touch;">
        <table class="ap-table" style="min-width:900px;">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Product Name</th>
                    <th>API Slug</th>
                    <th>Domain URL</th>
                    <th>Contact Email</th>
                    <th>Version</th>
                    <th>Status</th>
                    <th style="text-align:center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr>
                    <td style="color:var(--text-muted); font-size:0.8125rem;">{{ $loop->iteration }}</td>
                    <td style="font-weight:600; color:var(--text-primary);">{{ $product->name }}</td>
                    <td><span class="ap-badge ap-badge-accent">{{ $product->slug }}</span></td>
                    <td style="max-width:200px;">
                        @if($product->domainUrl?->url)
                            <a href="{{ $product->domainUrl->url }}" target="_blank"
                               class="truncate block domain-url-link" style="max-width:180px; text-decoration:none;"
                               title="{{ $product->domainUrl->url }}"
                               onmouseover="this.style.textDecoration='underline';" onmouseout="this.style.textDecoration='none';">
                                {{ $product->domainUrl->url }}
                            </a>
                        @else
                            <span style="color:var(--text-muted);">—</span>
                        @endif
                    </td>
                    <td>{{ $product->contactDetail?->email ?: '—' }}</td>
                    <td>
                        @if($product->version?->version)
                            <span class="ap-badge ap-badge-success">v{{ $product->version->version }}</span>
                        @else
                            <span style="color:var(--text-muted);">—</span>
                        @endif
                    </td>
                    <td>
                        @if($product->is_active)
                            <span class="ap-badge ap-badge-success">Active</span>
                        @else
                            <span class="ap-badge ap-badge-muted">Inactive</span>
                        @endif
                    </td>
                    <td style="text-align:center;">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('products.edit', $product) }}" class="ap-btn-edit">Edit</a>
                            <form method="POST" action="{{ route('products.destroy', $product) }}"
                                  onsubmit="return confirm('Delete \'{{ addslashes($product->name) }}\'?\nThis will permanently remove all its domain URL, contact details and version data.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="ap-btn-danger">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center; padding:40px 20px; color:var(--text-muted);">
                        No products yet.
                        <a href="{{ route('products.create') }}" style="color:var(--accent); text-decoration:none;" onmouseover="this.style.textDecoration='underline';" onmouseout="this.style.textDecoration='none';">Add the first one.</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

