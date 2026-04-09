<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ApiController extends Controller
{
    /**
     * Generic endpoints for king4k (backward compat: /api/domain_Url)
     */
    public function genericDomainUrl()
    {
        return $this->domainUrl('king4k');
    }

    public function genericContactDetail()
    {
        return $this->contactDetail('king4k');
    }

    public function genericApkVersion($version)
    {
        return $this->apkVersion('king4k', $version);
    }

    /**
     * Per-product endpoints: /api/{slug}/domain_Url
     */
    public function domainUrl(string $slug)
    {
        $product = Product::where('slug', $slug)->where('is_active', true)->first();

        if (! $product || ! $product->domainUrl) {
            return response()->json(['url' => null]);
        }

        return response()->json(['url' => $product->domainUrl->url]);
    }

    public function contactDetail(string $slug)
    {
        $product = Product::where('slug', $slug)->where('is_active', true)->first();

        if (! $product || ! $product->contactDetail) {
            return response()->json(['email' => null, 'phone' => null, 'info' => null]);
        }

        $contact = $product->contactDetail;

        return response()->json([
            'email' => $contact->email,
            'phone' => $contact->phone,
            'info'  => $contact->info,
        ]);
    }

    public function apkVersion(string $slug, string $version)
    {
        $product = Product::where('slug', $slug)->where('is_active', true)->first();

        if (! $product || ! $product->version || ! $product->version->version) {
            return response()->json([]);
        }

        $ver = $product->version;

        // Only return if DB version is strictly greater than what the client reports
        if (version_compare($ver->version, $version, '>')) {
            return response()->json([
                'version'     => $ver->version,
                'description' => $ver->description,
                'file'        => $ver->file ? asset('storage/' . $ver->file) : null,
            ]);
        }

        return response()->json([]);
    }
}
