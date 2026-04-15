<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use App\Models\DomainUrl;
use App\Models\ContactDetail;
use App\Models\AppVersion;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['domainUrl', 'contactDetail', 'version'])
            ->orderBy('id')
            ->get();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $allowedExts = ['apk', 'png', 'jpg', 'jpeg', 'pdf', 'svg'];
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:100'],
            'slug'        => ['required', 'string', 'max:100', 'alpha_dash', 'unique:products,slug'],
            'url'         => ['nullable', 'url', 'max:500'],
            'email'       => ['nullable', 'email', 'max:255'],
            'phone'       => ['nullable', 'string', 'max:50'],
            'info'        => ['nullable', 'string', 'max:2000'],
            'version'     => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:500'],
            'file'        => ['nullable', 'file', 'max:204800', function ($attribute, $value, $fail) use ($allowedExts) {
                if (!in_array(strtolower($value->getClientOriginalExtension()), $allowedExts)) {
                    $fail('The file must be of type: apk, png, jpg, jpeg, pdf, svg.');
                }
            }],
        ], [], [
            'slug.unique' => 'The slug has already been taken. Please choose another.'
        ]);

        $product = Product::create([
            'name'      => $validated['name'],
            'slug'      => $validated['slug'],
            'is_active' => true,
        ]);

        DomainUrl::create([
            'product_id' => $product->id,
            'url'        => $validated['url'] ?? null,
        ]);

        ContactDetail::create([
            'product_id' => $product->id,
            'email'      => $validated['email'] ?? null,
            'phone'      => $validated['phone'] ?? null,
            'info'       => $validated['info'] ?? null,
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $uploadedFile = $request->file('file');
            $filename = \Illuminate\Support\Str::uuid() . '.' . $uploadedFile->getClientOriginalExtension();
            $filePath = $uploadedFile->storeAs('versions', $filename, 'public');
        }

        AppVersion::create([
            'product_id'  => $product->id,
            'version'     => $validated['version'] ?? null,
            'description' => $validated['description'] ?? null,
            'file'        => $filePath,
        ]);

        return redirect()->route('products.index')->with('success', 'Product "' . $product->name . '" created successfully.');
    }

    public function edit(Product $product)
    {
        $product->load(['domainUrl', 'contactDetail', 'version']);
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $allowedExts = ['apk', 'png', 'jpg', 'jpeg', 'pdf', 'svg'];
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:100'],
            'slug'        => ['required', 'string', 'max:100', 'alpha_dash', 'unique:products,slug,' . $product->id],
            'is_active'   => ['sometimes', 'boolean'],
            'url'         => ['nullable', 'url', 'max:500'],
            'email'       => ['nullable', 'email', 'max:255'],
            'phone'       => ['nullable', 'string', 'max:50'],
            'info'        => ['nullable', 'string', 'max:2000'],
            'version'     => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:500'],
            'file'        => ['nullable', 'file', 'max:204800', function ($attribute, $value, $fail) use ($allowedExts) {
                if (!in_array(strtolower($value->getClientOriginalExtension()), $allowedExts)) {
                    $fail('The file must be of type: apk, png, jpg, jpeg, pdf, svg.');
                }
            }],
        ]);

        $product->update([
            'name'      => $validated['name'],
            'slug'      => $validated['slug'],
            'is_active' => $request->boolean('is_active'),
        ]);

        $product->domainUrl()->updateOrCreate(
            ['product_id' => $product->id],
            ['url' => $validated['url'] ?? null]
        );

        $product->contactDetail()->updateOrCreate(
            ['product_id' => $product->id],
            [
                'email' => $validated['email'] ?? null,
                'phone' => $validated['phone'] ?? null,
                'info'  => $validated['info'] ?? null,
            ]
        );

        $versionData = [
            'version'     => $validated['version'] ?? null,
            'description' => $validated['description'] ?? null,
        ];

        if ($request->hasFile('file')) {
            $existing = $product->version;
            if ($existing && $existing->file) {
                Storage::disk('public')->delete($existing->file);
            }
            $uploadedFile = $request->file('file');
            $filename = \Illuminate\Support\Str::uuid() . '.' . $uploadedFile->getClientOriginalExtension();
            $versionData['file'] = $uploadedFile->storeAs('versions', $filename, 'public');
        }

        $product->version()->updateOrCreate(
            ['product_id' => $product->id],
            $versionData
        );

        return redirect()->route('products.index')->with('success', 'Product "' . $product->name . '" updated successfully.');
    }

    public function destroy(Product $product)
    {
        // Delete APK file if exists
        if ($product->version && $product->version->file) {
            Storage::disk('public')->delete($product->version->file);
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}

