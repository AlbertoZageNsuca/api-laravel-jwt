<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\ProductResource;


class UserController extends Controller
{
    // Apenas Admin: Listar todos os utilizadores do sistema
    public function index()
    {
        return User::all();
    }

    // Apenas Admin: Ver produtos de um user específico
    public function products($id)
    {
        $user = User::findOrFail($id);
        return ProductResource::collection($user->products()->get());
    }

    // Apenas Admin: Editar role do user
    public function updateRole(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return response()->json(['message' => 'Não podes alterar o teu próprio role'], 403);
        }

        $request->validate([
            'role' => 'required|in:admin,user'
        ]);

        $user->update(['role' => $request->role]);

        return response()->json(['message' => 'Role atualizado', 'user' => $user]);
    }

    // Atualizar o perfil do próprio utilizador logado
    public function update(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'name'  => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
        ]);
        $user->update($request->only(['name', 'email']));
        return response()->json(['message' => 'Perfil atualizado', 'user' => $user]);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return response()->json(['message' => 'Não podes apagar a tua própria conta'], 403);
        }

        $user->delete();
        return response()->json(['message' => 'Utilizador removido com sucesso'], 200);
    }
}
