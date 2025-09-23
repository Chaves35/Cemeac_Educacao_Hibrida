<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    // Permissão para visualizar qualquer usuário
    public function viewAny(User $user)
    {
        return in_array($user->role, ['admin', 'gestor']);
    }

    // Permissão para visualizar um usuário específico
    public function view(User $user, User $model)
    {
        // Admin pode ver qualquer usuário
        if ($user->role === 'admin') return true;
        
        // Gestor pode ver usuários da sua escola
        if ($user->role === 'gestor' && $user->school_id === $model->school_id) return true;
        
        // Professor pode ver alunos da sua escola
        if ($user->role === 'professor' && $model->role === 'aluno' && $user->school_id === $model->school_id) return true;
        
        // Usuário pode ver próprio perfil
        return $user->id === $model->id;
    }

    // Permissão para criar usuário
    public function create(User $user)
    {
        return in_array($user->role, ['admin', 'gestor']);
    }

    // Permissão para atualizar usuário
    public function update(User $user, User $model)
    {
        // Admin pode atualizar qualquer usuário
        if ($user->role === 'admin') return true;
        
        // Gestor pode atualizar usuários da sua escola
        if ($user->role === 'gestor' && $user->school_id === $model->school_id) return true;
        
        // Usuário pode atualizar próprio perfil (exceto role)
        return $user->id === $model->id;
    }

    // Permissão para deletar usuário
    public function delete(User $user, User $model)
    {
        // Admin pode deletar qualquer usuário
        if ($user->role === 'admin') return true;
        
        // Gestor pode deletar usuários da sua escola (exceto outros gestores)
        return $user->role === 'gestor' && 
               $user->school_id === $model->school_id && 
               $model->role !== 'gestor';
    }

    // Permissão para restaurar usuário
    public function restore(User $user, User $model)
    {
        return $user->role === 'admin';
    }

    // Permissão para deletar permanentemente
    public function forceDelete(User $user, User $model)
    {
        return $user->role === 'admin';
    }
}