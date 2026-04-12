<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class DomainUrlController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::active()->orderBy('id')->get();
        $selected = null;
        $domainUrl = null;

        if ($request->filled('product')) {
            $selected = Product::where('slug', $request->product)->firstOrFail();
            $domainUrl = $selected->domainUrl;
        }

        return view('domain-urls.index', compact('products', 'selected', 'domainUrl'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'url' => ['nullable', 'url', 'max:500'],
        ]);

        $product->domainUrl()->updateOrCreate(
            ['product_id' => $product->id],
            ['url' => $validated['url'] ?? null]
        );

        $redirectProduct = $request->input('product_id', $product->slug);
        return redirect()->route('domain-urls.index', ['product_id' => $redirectProduct])
            ->with('success', 'Domain URL updated successfully.');
    }
}
