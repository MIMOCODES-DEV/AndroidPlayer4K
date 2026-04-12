<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ApiController extends Controller
{
    // ...existing code...
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

    // ...existing code...
    public function domainUrl(string $slug)
    {
        $product = Product::where('slug', $slug)->where('is_active', true)->first();

        if (! $product || ! $product->domainUrl) {
            return response()->json(null);
        }

        return response($product->domainUrl);
    }

    public function contactDetail(string $slug)
    {
        $product = Product::where('slug', $slug)->where('is_active', true)->first();

        if (! $product || ! $product->contactDetail) {
            return response()->json(null);
        }

        return response()->json($product->contactDetail);
    }

    public function apkVersion(string $slug, string $version)
    {
        $product = Product::where('slug', $slug)->where('is_active', true)->first();

        if (! $product || ! $product->version || ! $product->version->version) {
            return response()->json([]);
        }

        $ver = $product->version;

        // Simple string comparison matching old code behaviour
        if ($ver->version > $version) {
            return response()->json($ver);
        }

        return response()->json([]);
    }
}
