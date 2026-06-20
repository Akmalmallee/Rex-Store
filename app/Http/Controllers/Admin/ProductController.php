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
use App\Models\ProductModel;
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

        if ($request->hasFile('model_file')) {
            Storage::disk('public')->makeDirectory('product-models');
            $path = $request->file('model_file')->store('product-models', 'public');
            if ($path) {
                ProductModel::create([
                    'product_id' => $product->id,
                    'model_file' => $path,
                    'is_active' => true,
                ]);
            }
        }

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
            foreach (array_map('trim', explode(',', $request->sizes)) as $sizeName) {
                if ($sizeName) {
                    ProductSize::create([
                        'product_id' => $product->id,
                        'size' => $sizeName,
                        'stock' => 0,
                    ]);
                }
            }
        }

        if ($request->filled('colors')) {
            foreach (array_map('trim', explode(',', $request->colors)) as $colorName) {
                if ($colorName) {
                    ProductColor::create([
                        'product_id' => $product->id,
                        'color' => $colorName,
                        'stock' => 0,
                    ]);
                }
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function edit($id)
    {
        $product = Product::with(['images', 'sizes', 'colors', 'productModel'])->findOrFail($id);
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
            try {
                $path = $request->file('thumbnail')->store('products/thumbnails', 'public');
                \Log::info('Thumbnail uploaded', ['path' => $path, 'file_exists' => Storage::disk('public')->exists($path)]);
                if ($path) $data['thumbnail'] = $path;
            } catch (\Exception $e) {
                \Log::error('Thumbnail upload error', ['error' => $e->getMessage()]);
            }
        }

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
            $data['updated_at'] = now();
        }

        $product->update($data);

        if ($request->hasFile('model_file')) {
            Storage::disk('public')->makeDirectory('product-models');
            if ($product->productModel) {
                $oldModel = $product->productModel;
                if ($oldModel->model_file && !str_starts_with($oldModel->model_file, 'http')) {
                    Storage::disk('public')->delete($oldModel->model_file);
                }
                $path = $request->file('model_file')->store('product-models', 'public');
                if ($path) {
                    $oldModel->update(['model_file' => $path]);
                }
            } else {
                $path = $request->file('model_file')->store('product-models', 'public');
                if ($path) {
                    ProductModel::create([
                        'product_id' => $product->id,
                        'model_file' => $path,
                        'is_active' => true,
                    ]);
                }
            }
        }

        if ($request->filled('sizes')) {
            $product->sizes()->delete();
            foreach (array_map('trim', explode(',', $request->sizes)) as $sizeName) {
                if ($sizeName) {
                    ProductSize::create([
                        'product_id' => $product->id,
                        'size' => $sizeName,
                        'stock' => 0,
                    ]);
                }
            }
        }

        if ($request->filled('colors')) {
            $product->colors()->delete();
            foreach (array_map('trim', explode(',', $request->colors)) as $colorName) {
                if ($colorName) {
                    ProductColor::create([
                        'product_id' => $product->id,
                        'color' => $colorName,
                        'stock' => 0,
                    ]);
                }
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
