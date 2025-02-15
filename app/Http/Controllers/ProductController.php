<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use League\Csv\Writer;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with(['category:id,name', 'department:id,name', 'manufacturer:id,name'])->get();

        return response()->json($products, 200);
    }

    public function productsByCategory($category_id)
    {
        $products = Product::with(['category:id,name', 'department:id,name', 'manufacturer:id,name'])
        ->where('category_id', $category_id)
        ->get();

        if ($products->isEmpty()) {
            return response()->json(['message' => 'No products found for this category'], 404);
        }

        return response()->json($products, 200);
    }

    public function exportProductsToCSV($category_id)
    {
        $products = Product::with(['category:id,name', 'department:id,name', 'manufacturer:id,name'])
            ->where('category_id', $category_id)
            ->get();

        if ($products->isEmpty()) {
            return response()->json(['message' => 'No products found for this category'], 404);
        }

        $categoryName = preg_replace('/[^a-zA-Z0-9]+/', '_', strtolower($products->first()->category->name));
        $timestamp = now()->setTimezone(config('app.timezone'))->format('Y_m_d-H_i');
        $fileName = "{$categoryName}_{$timestamp}.csv";

        $csv = Writer::createFromString('');
        $csv->insertOne(['product_number', 'category_name', 'deparment_name', 'manufacturer_name', 'upc', 'sku', 'regular_price', 'sale_price', 'description']);

        foreach ($products as $product) {
            $csv->insertOne([
                $product->product_number,
                $product->category->name,
                $product->department->name,
                $product->manufacturer->name,
                $product->upc,
                $product->sku,
                $product->regular_price,
                $product->sale_price,
                $product->description
            ]);
        }

        Storage::put("exports/{$fileName}", $csv->toString());
        $fullPath = Storage::path("exports/{$fileName}");

        return response()->download($fullPath, $fileName, ['Content-Type' => 'text/csv']);
    }

    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $product = Product::with(['category:id,name', 'department:id,name', 'manufacturer:id,name'])->find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $product->update($request->all());

        return response()->json(['message' => 'Product updated successfully', 'product' => $product], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted successfully'], 200);
    }
}
