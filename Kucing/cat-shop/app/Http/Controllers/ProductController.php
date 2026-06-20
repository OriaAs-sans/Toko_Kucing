<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('name')->paginate(20);
        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'sku' => 'nullable|string|max:64|unique:products,sku',
            'name' => 'required|string|max:191',
            'cost_price' => 'required|numeric|min:0',
            'sell_price' => 'required|numeric|min:0',
            'stock_qty' => 'required|integer',
            'min_stock' => 'nullable|integer'
        ]);

        Product::create($data);
        return redirect()->route('products.index')->with('success', 'Product created');
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'sku' => 'nullable|string|max:64|unique:products,sku,' . $product->id,
            'name' => 'required|string|max:191',
            'cost_price' => 'required|numeric|min:0',
            'sell_price' => 'required|numeric|min:0',
            'stock_qty' => 'required|integer',
            'min_stock' => 'nullable|integer'
        ]);

        $product->update($data);
        return redirect()->route('products.index')->with('success', 'Product updated');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted');
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }
}
