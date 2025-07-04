<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ProductController extends Controller
{
    public function index()
    {
        return Product::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $product = new Product($request->except('image'));

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            
            // Save original
            $imagePath = 'products/' . $filename;
            Storage::disk('public')->put($imagePath, file_get_contents($image));
            
            // Save thumbnail
            $thumbnail = Image::make($image)->resize(300, 300)->encode();
            $thumbnailPath = 'products/thumbnails/' . $filename;
            Storage::disk('public')->put($thumbnailPath, $thumbnail);
            
            $product->image_path = $imagePath;
        }

        $product->save();

        return response()->json($product, 201);
    }

    public function show(Product $product)
    {
        return $product;
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'price' => 'sometimes|numeric|min:0',
            'stock' => 'sometimes|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $product->fill($request->except('image'));

        if ($request->hasFile('image')) {
            // Delete old images
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
                $thumbnailPath = str_replace('products/', 'products/thumbnails/', $product->image_path);
                Storage::disk('public')->delete($thumbnailPath);
            }

            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            
            // Save original
            $imagePath = 'products/' . $filename;
            Storage::disk('public')->put($imagePath, file_get_contents($image));
            
            // Save thumbnail
            $thumbnail = Image::make($image)->resize(300, 300)->encode();
            $thumbnailPath = 'products/thumbnails/' . $filename;
            Storage::disk('public')->put($thumbnailPath, $thumbnail);
            
            $product->image_path = $imagePath;
        }

        $product->save();

        return response()->json($product);
    }

    public function destroy(Product $product)
    {
        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
            $thumbnailPath = str_replace('products/', 'products/thumbnails/', $product->image_path);
            Storage::disk('public')->delete($thumbnailPath);
        }

        $product->delete();

        return response()->json(null, 204);
    }

    public function getImage($id)
    {
        $product = Product::findOrFail($id);
        
        if (!$product->image_path) {
            return response()->json(['error' => 'Image not found'], 404);
        }

        $path = storage_path('app/public/' . $product->image_path);
        
        if (!file_exists($path)) {
            return response()->json(['error' => 'Image file not found'], 404);
        }

        return response()->file($path);
    }
}