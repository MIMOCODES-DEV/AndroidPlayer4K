@extends('layouts.app')

@section('page-title', 'Edit Product')
@section('page-subtitle', $product->name)

@section('header-actions')
<a href="{{ route('products.index') }}" class="ap-btn-outline" style="font-size:0.8125rem; padding:8px 18px;">
    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
    Back
</a>
@endsection

@section('content')
<div>

    @if($errors->any())
        <div class="ap-card flex items-start gap-3 mb-5" style="padding:14px 20px; border-left:4px solid var(--danger);">
            <svg width="20" height="20" fill="none" stroke="var(--danger)" stroke-width="2" viewBox="0 0 24 24" class="shrink-0 mt-0.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div>@foreach($errors->all() as $error)<p style="font-size:0.875rem; color:var(--danger);">{{ $error }}</p>@endforeach</div>
        </div>
    @endif

    <form method="POST" action="{{ route('products.update', $product) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Row 1: Product Info + Domain URL --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-5">
            <div class="ap-card">
                <div class="ap-section-title">Product Info</div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="ap-label">Product Name *</label>
                        <input type="text" name="name" value="{{ old('name', $product->name) }}" class="ap-input" required />
                    </div>
                    <div>
                        <label class="ap-label">API Slug * <span style="font-weight:400; font-size:0.7rem; color:var(--warning);">&#9888; breaks Android API</span></label>
                        <input type="text" name="slug" value="{{ old('slug', $product->slug) }}" class="ap-input" style="font-family:monospace;" required />
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <input type="hidden" name="is_active" value="0" />
                    <label style="display:flex; align-items:center; gap:10px; cursor:pointer;">
                        <div style="position:relative; width:44px; height:24px;">
                            <input type="checkbox" name="is_active" value="1"
                                   {{ old('is_active', $product->is_active) ? 'checked' : '' }}
                                   style="position:absolute; opacity:0; width:100%; height:100%; cursor:pointer; z-index:2; margin:0;"
                                   onchange="this.nextElementSibling.style.background = this.checked ? 'var(--accent)' : 'var(--border-input)'; this.nextElementSibling.querySelector('span').style.transform = this.checked ? 'translateX(20px)' : 'translateX(0)';" />
                            <div style="width:44px; height:24px; border-radius:12px; transition:background 0.2s; {{ old('is_active', $product->is_active) ? 'background:var(--accent);' : 'background:var(--border-input);' }}">
                                <span style="display:block; width:18px; height:18px; margin-top:3px; margin-left:3px; background:#fff; border-radius:50%; transition:transform 0.2s; {{ old('is_active', $product->is_active) ? 'transform:translateX(20px);' : '' }}"></span>
                            </div>
                        </div>
                        <span class="ap-label" style="margin-bottom:0;">Active (visible to Android app)</span>
                    </label>
                </div>
            </div>
            <div class="ap-card">
                <div class="ap-section-title">Domain URL</div>
                <div>
                    <label class="ap-label">URL</label>
                    <input type="url" name="url" value="{{ old('url', $product->domainUrl?->url) }}" class="ap-input" placeholder="https://example.com" />
                </div>
            </div>
        </div>

        {{-- Row 2: Contact Details + Version --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-6">
            <div class="ap-card">
                <div class="ap-section-title">Contact Details</div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="ap-label">Email</label>
                        <input type="email" name="email" value="{{ old('email', $product->contactDetail?->email) }}" class="ap-input" placeholder="info@example.com" />
                    </div>
                    <div>
                        <label class="ap-label">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone', $product->contactDetail?->phone) }}" class="ap-input" placeholder="+1 000 000 0000" />
                    </div>
                </div>
                <div>
                    <label class="ap-label">Info</label>
                    <textarea name="info" rows="4" class="ap-input" style="resize:none;" placeholder="Additional contact info...">{{ old('info', $product->contactDetail?->info) }}</textarea>
                </div>
            </div>
            <div class="ap-card">
                <div class="ap-section-title">Version</div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="ap-label">Version Number</label>
                        <input type="text" name="version" value="{{ old('version', $product->version?->version) }}" class="ap-input" placeholder="e.g. 1.0.0" />
                    </div>
                    <div>
                        <label class="ap-label">Description</label>
                        <input type="text" name="description" value="{{ old('description', $product->version?->description) }}" class="ap-input" placeholder="What changed?" />
                    </div>
                </div>
                <div>
                    <label class="ap-label">APK / File</label>
                    @if($product->version?->file)
                        <div class="flex items-center gap-2 mb-3" style="padding:10px 14px; border-radius:10px; background:rgba(37,99,235,0.06); border:1px solid rgba(37,99,235,0.12); overflow-x:auto; max-width:100%;">
                            <svg width="16" height="16" fill="none" stroke="var(--accent)" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                            <span style="font-size:0.8125rem; color:var(--text-secondary); white-space:nowrap;">Current:</span>
                            <a href="{{ asset('storage/' . $product->version->file) }}" target="_blank" class="domain-url-link"
                               style="font-size:0.8125rem; text-decoration:none; max-width:160px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; display:inline-block; vertical-align:bottom;"
                               onmouseover="this.style.textDecoration='underline';" onmouseout="this.style.textDecoration='none';"
                               title="{{ $product->version->file }}">
                                {{ basename($product->version->file) }}
                            </a>
                        </div>
                    @endif
                    <input type="file" name="file" accept=".apk,.png,.jpg,.jpeg,.pdf,.svg" class="ap-input" />
                    <p style="font-size:0.75rem; color:var(--text-muted); margin-top:6px;">APK. Upload to replace current file.</p>
                </div>
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end;">
            <button type="submit" class="ap-btn">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                Save Changes
            </button>
        </div>
    </form>
</div>
@endsection
