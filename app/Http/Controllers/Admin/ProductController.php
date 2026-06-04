<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\ProductImage;
use App\Models\ProductSize;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'brand'])->latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        $brands = Brand::where('is_active', true)->get();
        return view('admin.products.create', compact('categories', 'brands'));
    }

    public function store(StoreProductRequest $request)
    {
        $data = $request->except(['thumbnail', 'images', 'sizes', 'colors']);

        if ($request->hasFile('thumbnail')) {
            Storage::disk('public')->makeDirectory('products/thumbnails');
            $path = $request->file('thumbnail')->store('products/thumbnails', 'public');
            if ($path) $data['thumbnail'] = $path;
        }

        $product = Product::create($data);

        if ($request->hasFile('images')) {
            Storage::disk('public')->makeDirectory('products/images');
            foreach ($request->file('images') as $image) {
                $path = $image->store('products/images', 'public');
                if ($path) {
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image' => $path,
                        'is_primary' => false,
                    ]);
                }
            }
        }

        if ($request->filled('sizes')) {
            foreach ($request->sizes as $size) {
                ProductSize::create([
                    'product_id' => $product->id,
                    'size' => $size['size'],
                    'stock' => $size['stock'],
                ]);
            }
        }

        if ($request->filled('colors')) {
            foreach ($request->colors as $color) {
                ProductColor::create([
                    'product_id' => $product->id,
                    'color' => $color['color'],
                    'color_code' => $color['color_code'] ?? null,
                    'stock' => $color['stock'],
                ]);
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function edit($id)
    {
        $product = Product::with(['images', 'sizes', 'colors'])->findOrFail($id);
        $categories = Category::where('is_active', true)->get();
        $brands = Brand::where('is_active', true)->get();
        return view('admin.products.edit', compact('product', 'categories', 'brands'));
    }

    public function update(UpdateProductRequest $request, $id)
    {
        $product = Product::findOrFail($id);
        $data = $request->except(['thumbnail', 'images', 'delete_images', 'sizes', 'colors', '_method']);

        if ($request->hasFile('thumbnail')) {
            Storage::disk('public')->makeDirectory('products/thumbnails');
            if ($product->thumbnail && !str_starts_with($product->thumbnail, 'http')) {
                Storage::disk('public')->delete($product->thumbnail);
            }
            $path = $request->file('thumbnail')->store('products/thumbnails', 'public');
            if ($path) $data['thumbnail'] = $path;
        }

        $product->update($data);

        if ($request->has('delete_images')) {
            $imagesToDelete = ProductImage::whereIn('id', $request->delete_images)
                ->where('product_id', $product->id)
                ->get();
            foreach ($imagesToDelete as $oldImage) {
                if (!str_starts_with($oldImage->image, 'http')) {
                    Storage::disk('public')->delete($oldImage->image);
                }
                $oldImage->delete();
            }
        }

        if ($request->hasFile('images')) {
            Storage::disk('public')->makeDirectory('products/images');
            foreach ($product->images as $oldImage) {
                if (!str_starts_with($oldImage->image, 'http')) {
                    Storage::disk('public')->delete($oldImage->image);
                }
                $oldImage->delete();
            }
            foreach ($request->file('images') as $image) {
                $path = $image->store('products/images', 'public');
                if ($path) {
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image' => $path,
                        'is_primary' => false,
                    ]);
                }
            }
        }

        if ($request->has('sizes')) {
            $product->sizes()->delete();
            foreach ($request->sizes as $size) {
                ProductSize::create([
                    'product_id' => $product->id,
                    'size' => $size['size'],
                    'stock' => $size['stock'],
                ]);
            }
        }

        if ($request->has('colors')) {
            $product->colors()->delete();
            foreach ($request->colors as $color) {
                ProductColor::create([
                    'product_id' => $product->id,
                    'color' => $color['color'],
                    'color_code' => $color['color_code'] ?? null,
                    'stock' => $color['stock'],
                ]);
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy($id)
    {
        Product::findOrFail($id)->delete();
        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    public function restore($id)
    {
        Product::withTrashed()->findOrFail($id)->restore();
        return redirect()->route('admin.products.index')
            ->with('success', 'Product restored successfully.');
    }
}
