<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('viewAny', User::class);

        $users = User::with('school')->paginate(10);
        return UserResource::collection($users);
    }

    public function show(User $user)
    {
        Gate::authorize('view', $user);
        return new UserResource($user->load('school'));
    }

    public function update(Request $request, User $user)
    {
        Gate::authorize('update', $user);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'role' => 'sometimes|in:admin,gestor,professor,aluno',
            'school_id' => 'nullable|exists:schools,id'
        ]);

        $user->update($validated);
        return new UserResource($user->refresh());
    }

    public function destroy(User $user)
    {
        Gate::authorize('delete', $user);
        $user->delete();
        return response()->json(['message' => 'Usuário removido com sucesso']);
    }
}