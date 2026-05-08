<?php
namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;
use App\Http\Requests\StoreProductRequest;

class ProductController extends Controller
{
    // Listar produtos ativos
    public function index()
    {
        $user = auth()->user();
        $query = $user->role === 'admin'
            ? Product::query()
            : $user->products();
        return ProductResource::collection($query->get());
    }

    // Listar produtos eliminados (só admin ou dono)
    public function trashed()
    {
        $user = auth()->user();
        $query = $user->role === 'admin'
            ? Product::onlyTrashed()
            : Product::onlyTrashed()->where('user_id', $user->id);
        return ProductResource::collection($query->get());
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

    // Soft delete
    public function destroy(Product $product)
    {
        $user = auth()->user();
        if ($user->role !== 'admin' && $product->user_id !== $user->id) {
            return response()->json(['message' => 'Acesso negado'], 403);
        }
        $product->delete();
        return response()->json(['message' => 'Produto movido para a reciclagem'], 200);
    }

    // Restaurar produto eliminado
    public function restore($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        $user = auth()->user();
        if ($user->role !== 'admin' && $product->user_id !== $user->id) {
            return response()->json(['message' => 'Acesso negado'], 403);
        }
        $product->restore();
        return response()->json(['message' => 'Produto restaurado com sucesso'], 200);
    }

    // Eliminar permanentemente
    public function forceDelete($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        $user = auth()->user();
        if ($user->role !== 'admin' && $product->user_id !== $user->id) {
            return response()->json(['message' => 'Acesso negado'], 403);
        }
        $product->forceDelete();
        return response()->json(['message' => 'Produto eliminado permanentemente'], 200);
    }
}