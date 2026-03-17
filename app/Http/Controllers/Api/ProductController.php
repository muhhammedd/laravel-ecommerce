<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductListResource;
use App\Http\Resources\ProductResource;
use App\Models\Api\Product;
use App\Models\ProductCategory;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $perPage = request('per_page', 10);
        $search = request('search', '');
        $sortField = request('sort_field', 'created_at');
        $sortDirection = request('sort_direction', 'desc');

        $query = Product::query()
            ->where('title', 'like', "%{$search}%")
            ->orderBy($sortField, $sortDirection)
            ->paginate($perPage);

        return ProductListResource::collection($query);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        $data = $request->validated();
        $data['created_by'] = $request->user()->id;
        $data['updated_by'] = $request->user()->id;

        /** @var \Illuminate\Http\UploadedFile[] $images */
        $images = $data['images'] ?? [];
        $imagePositions = $data['image_positions'] ?? [];
        $categories = $data['categories'] ?? [];

        $product = Product::create($data);

        $this->saveCategories($categories, $product);
        $this->saveImages($images, $imagePositions, $product);

        return new ProductResource($product);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product      $product
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, Product $product)
    {
        $data = $request->validated();
        $data['updated_by'] = $request->user()->id;

        /** @var \Illuminate\Http\UploadedFile[] $images */
        $images = $data['images'] ?? [];
        $deletedImages = $data['deleted_images'] ?? [];
        $imagePositions = $data['image_positions'] ?? [];
        $categories = $data['categories'] ?? [];

        $this->saveCategories($categories, $product);
        $this->saveImages($images, $imagePositions, $product);
        if (count($deletedImages) > 0) {
            $this->deleteImages($deletedImages, $product);
        }

        $product->update($data);

        return new ProductResource($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        // Delete all image files from disk, then remove the product's image folder
        $images = ProductImage::where('product_id', $product->id)->get();
        foreach ($images as $image) {
            if ($image->path) {
                Storage::disk('public')->delete($image->path);
            }
            $image->delete();
        }
        // Remove the whole product image directory (images/{product_id}/)
        Storage::disk('public')->deleteDirectory('images/' . $product->id);

        $product->delete();

        return response()->noContent();
    }

    private function saveCategories($categoryIds, Product $product)
    {
        ProductCategory::where('product_id', $product->id)->delete();
        $data = array_map(fn($id) => (['category_id' => $id, 'product_id' => $product->id]), $categoryIds);

        ProductCategory::insert($data);
    }

    /**
     *
     *
     * @param UploadedFile[] $images
     * @return string
     * @throws \Exception
     * @author Zura Sekhniashvili <zurasekhniashvili@gmail.com>
     */
    private function saveImages($images, $positions, Product $product)
    {
        // Update positions for existing images
        foreach ($positions as $id => $position) {
            ProductImage::query()
                ->where('id', $id)
                ->update(['position' => $position]);
        }

        // Store each new image inside its own product folder: images/{product_id}/
        $productFolder = 'images/' . $product->id;

        foreach ($images as $id => $image) {
            $path = $image->store($productFolder, 'public'); // images/{product_id}/xxxx.png
            // Storage::disk('public')->url() already returns the full URL, no need to wrap with URL::to()
            $url = Storage::disk('public')->url($path);

            ProductImage::create([
                'product_id' => $product->id,
                'path' => $path,
                'url' => $url,
                'mime' => $image->getClientMimeType(),
                'size' => $image->getSize(),
                'position' => $positions[$id] ?? $id + 1,
            ]);
        }
    }

    private function deleteImages($imageIds, Product $product)
    {
        $images = ProductImage::query()
            ->where('product_id', $product->id)
            ->whereIn('id', $imageIds)
            ->get();

        foreach ($images as $image) {
            // Delete the individual image file from the public disk
            if ($image->path) {
                Storage::disk('public')->delete($image->path);
            }
            $image->delete();
        }
    }
}