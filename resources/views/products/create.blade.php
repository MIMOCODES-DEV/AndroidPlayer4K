@extends('layouts.app')

@section('page-title', 'Add Product')
@section('page-subtitle', 'Register a new application')

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

    <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
        @csrf

        {{-- Row 1: Product Info + Domain URL --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-5">
            <div class="ap-card">
                <div class="ap-section-title">Product Info</div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="ap-label">Product Name *</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="ap-input" placeholder="e.g. King 4k" required />
                    </div>
                    <div>
                        <label class="ap-label">API Slug * <span style="font-weight:400; font-size:0.7rem; color:var(--text-muted);">letters, numbers, dashes</span></label>
                        <input type="text" name="slug" value="{{ old('slug') }}" class="ap-input" style="font-family:monospace;" placeholder="e.g. king4k" required />
                    </div>
                </div>
            </div>
            <div class="ap-card">
                <div class="ap-section-title">Domain URL</div>
                <div>
                    <label class="ap-label">URL</label>
                    <input type="url" name="url" value="{{ old('url') }}" class="ap-input" placeholder="https://example.com" />
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
                        <input type="email" name="email" value="{{ old('email') }}" class="ap-input" placeholder="info@example.com" />
                    </div>
                    <div>
                        <label class="ap-label">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="ap-input" placeholder="+1 000 000 0000" />
                    </div>
                </div>
                <div>
                    <label class="ap-label">Info</label>
                    <textarea name="info" rows="4" class="ap-input" style="resize:none;" placeholder="Additional contact info...">{{ old('info') }}</textarea>
                </div>
            </div>
            <div class="ap-card">
                <div class="ap-section-title">Version</div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="ap-label">Version Number</label>
                        <input type="text" name="version" value="{{ old('version') }}" class="ap-input" placeholder="e.g. 1.0.0" />
                    </div>
                    <div>
                        <label class="ap-label">Description</label>
                        <input type="text" name="description" value="{{ old('description') }}" class="ap-input" placeholder="What changed in this version?" />
                    </div>
                </div>
                <div>
                    <label class="ap-label">APK / File</label>
                    <input type="file" name="file" accept=".apk,.png,.jpg,.jpeg,.pdf,.svg" class="ap-input" />
                    <p style="font-size:0.75rem; color:var(--text-muted); margin-top:6px;">APK</p>
                </div>
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end;">
            <button type="submit" class="ap-btn">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                Create Product
            </button>
        </div>
    </form>
</div>
@endsection
