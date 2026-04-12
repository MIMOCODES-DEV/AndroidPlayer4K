<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ContactDetailController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::active()->orderBy('id')->get();
        $selected = null;
        $contact = null;

        if ($request->filled('product')) {
            $selected = Product::where('slug', $request->product)->firstOrFail();
            $contact = $selected->contactDetail;
        }

        return view('contact-details.index', compact('products', 'selected', 'contact'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => [
                'nullable',
                'string',
                'max:50',
                'regex:/^[0-9\s\-\(\)\+]+$/',
            ],
            'info'  => ['nullable', 'string', 'max:2000'],
        ], [
            'phone.regex' => 'The phone number may only contain numbers, spaces, dashes, parentheses, and plus sign.'
        ]);

        $product->contactDetail()->updateOrCreate(
            ['product_id' => $product->id],
            [
                'email' => $validated['email'] ?? null,
                'phone' => $validated['phone'] ?? null,
                'info'  => $validated['info'] ?? null,
            ]
        );

        $redirectProduct = $request->input('product_id', $product->slug);
        return redirect()->route('contact-details.index', ['product_id' => $redirectProduct])
            ->with('success', 'Contact details updated successfully.');
    }
}
