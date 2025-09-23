<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Activity;
use Illuminate\Auth\Access\HandlesAuthorization;

class ActivityPolicy
{
    use HandlesAuthorization;

    // Permissão para visualizar qualquer atividade
    public function viewAny(User $user)
    {
        return in_array($user->role, ['admin', 'gestor', 'professor']);
    }

    // Permissão para visualizar atividade específica
    public function view(User $user, Activity $activity)
    {
        // Admin pode ver qualquer atividade
        if ($user->role === 'admin') return true;
        
        // Gestor e Professor podem ver atividades da sua escola
        if (in_array($user->role, ['gestor', 'professor']) && $user->school_id === $activity->school_id) return true;
        
        // Aluno pode ver atividades da sua escola
        if ($user->role === 'aluno' && $user->school_id === $activity->school_id) return true;
        
        return false;
    }

    // Permissão para criar atividade
    public function create(User $user)
    {
        return in_array($user->role, ['admin', 'gestor', 'professor']);
    }

    // Permissão para atualizar atividade
    public function update(User $user, Activity $activity)
    {
        // Admin pode atualizar qualquer atividade
        if ($user->role === 'admin') return true;
        
        // Gestor pode atualizar atividades da sua escola
        if ($user->role === 'gestor' && $user->school_id === $activity->school_id) return true;
        
        // Professor pode atualizar atividades da sua escola
        if ($user->role === 'professor' && $user->school_id === $activity->school_id) return true;
        
        return false;
    }

    // Permissão para deletar atividade
    public function delete(User $user, Activity $activity)
    {
        // Admin pode deletar qualquer atividade
        if ($user->role === 'admin') return true;
        
        // Gestor pode deletar atividades da sua escola
        if ($user->role === 'gestor' && $user->school_id === $activity->school_id) return true;
        
        return false;
    }

    // Permissão para restaurar atividade
    public function restore(User $user, Activity $activity)
    {
        return in_array($user->role, ['admin', 'gestor']);
    }

    // Permissão para deletar permanentemente
    public function forceDelete(User $user, Activity $activity)
    {
        return $user->role === 'admin';
    }

    // Permissão específica para responder atividade
    public function answer(User $user, Activity $activity)
    {
        return $user->role === 'aluno' && $user->school_id === $activity->school_id;
    }
}