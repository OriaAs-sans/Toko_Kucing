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
            'min_stock' => 'nullable|integer',
            'image' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time().'_'.preg_replace('/[^A-Za-z0-9\-\.]/', '_', $file->getClientOriginalName());
            $file->storeAs('public/products', $filename);
            $data['image'] = $filename;
        }

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
            'min_stock' => 'nullable|integer',
            'image' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time().'_'.preg_replace('/[^A-Za-z0-9\-\.]/', '_', $file->getClientOriginalName());
            $file->storeAs('public/products', $filename);
            $data['image'] = $filename;
        }

        $product->update($data);
        return redirect()->route('products.index')->with('success', 'Product updated');
    }

    public function adjustStock(Request $request, Product $product)
    {
        $data = $request->validate([
            'stock_qty' => 'required|integer'
        ]);
        $product->stock_qty = $data['stock_qty'];
        $product->save();
        return redirect()->route('products.index')->with('success', 'Stock updated');
    }

    public function replaceImage(Request $request, Product $product)
    {
        $data = $request->validate([
            'image' => 'nullable|image|max:4096',
            'image_url' => 'nullable|url'
        ]);

        // If uploaded file present, store it
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time().'_'.preg_replace('/[^A-Za-z0-9\-\.]/', '_', $file->getClientOriginalName());
            $file->storeAs('public/products', $filename);
            $product->image = $filename;
            $product->save();
            return redirect()->route('products.index')->with('success', 'Image updated');
        }

        // If provided external URL, try to download
        if (!empty($data['image_url'])) {
            try {
                $resp = \Illuminate\Support\Facades\Http::get($data['image_url']);
                if ($resp->successful()) {
                    $ext = pathinfo(parse_url($data['image_url'], PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';
                    $filename = time().'_external.' . $ext;
                    \Illuminate\Support\Facades\Storage::put('public/products/'.$filename, $resp->body());
                    $product->image = $filename;
                    $product->save();
                    return redirect()->route('products.index')->with('success', 'Image downloaded and saved');
                }
            } catch (\Exception $e) {
                return redirect()->route('products.index')->with('success', 'Failed to download image: '.$e->getMessage());
            }
        }

        return redirect()->route('products.index')->with('success', 'No image provided');
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
