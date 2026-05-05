<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;
use App\Http\Requests\StoreProductRequest;

class ProductController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $query = ($user->role === 'admin')
            ? Product::query()
            : $user->products();

        $products = $query->select(['id', 'name', 'price', 'user_id'])
                        ->latest()
                        ->get();

        return ProductResource::collection($products);
    }

    public function store(StoreProductRequest $request)
    {
        $product = auth()->user()->products()->create($request->validated());
        return new ProductResource($product);
    }

    public function show(Product $product)
    {
        $user = auth()->user();

        if ($user->role !== 'admin' && $product->user_id !== $user->id) {
            return response()->json(['message' => 'Acesso negado'], 403);
        }

        return new ProductResource($product);
    }

    public function update(StoreProductRequest $request, Product $product)
    {
        $user = auth()->user();

        if ($user->role !== 'admin' && $product->user_id !== $user->id) {
            return response()->json(['message' => 'Acesso negado'], 403);
        }

        $product->update($request->validated());
        return new ProductResource($product);
    }

    public function destroy(Product $product)
    {
        $user = auth()->user();

        if ($user->role !== 'admin' && $product->user_id !== $user->id) {
            return response()->json(['message' => 'Acesso negado'], 403);
        }

        $product->delete();
        return response()->json(['message' => 'Produto removido com sucesso'], 200);
    }
}
