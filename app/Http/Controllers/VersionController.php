<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;

class VersionController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::active()->orderBy('id')->get();
        $selected = null;
        $version = null;

        $selectedId = $request->filled('product_id') ? intval($request->product_id) : ($products->first()->id ?? null);
        if ($selectedId) {
            $selected = $products->firstWhere('id', $selectedId);
            $version = $selected?->version;
        }

        return view('versions.index', compact('products', 'selected', 'version'));
    }

    public function update(Request $request, Product $product)
    {
        ini_set('memory_limit', '256M');
        set_time_limit(120);

        $validated = $request->validate([
            'version'     => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:500'],
            'file'        => ['nullable', 'file', 'mimes:apk,png,jpg,jpeg,pdf,svg', 'max:204800'], // 200MB
        ]);

        $data = [
            'version'     => $validated['version'] ?? null,
            'description' => $validated['description'] ?? null,
        ];

        if ($request->hasFile('file')) {
            // Delete old file if exists
            $existing = $product->version;
            if ($existing && $existing->file) {
                Storage::disk('public')->delete($existing->file);
            }

            $uploadedFile = $request->file('file');
            $filename = \Illuminate\Support\Str::uuid() . '.' . $uploadedFile->getClientOriginalExtension();
            $data['file'] = $uploadedFile->storeAs('versions', $filename, 'public');
        }

        $product->version()->updateOrCreate(
            ['product_id' => $product->id],
            $data
        );

        $redirectProduct = $request->input('product_id', $product->slug);
        return redirect()->route('versions.index', ['product_id' => $redirectProduct])
            ->with('success', 'Version updated successfully.');
    }
}
