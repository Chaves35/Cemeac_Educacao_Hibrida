// backend/app/Policies/ContentPolicy.php
<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Content;
use Illuminate\Auth\Access\HandlesAuthorization;

class ContentPolicy
{
    use HandlesAuthorization;

    // Permissão para visualizar qualquer conteúdo
    public function viewAny(User $user)
    {
        return in_array($user->role, ['admin', 'gestor', 'professor']);
    }

    // Permissão para visualizar conteúdo específico
    public function view(User $user, Content $content)
    {
        // Admin pode ver qualquer conteúdo
        if ($user->role === 'admin') return true;
        
        // Se conteúdo não tem escola específica (global)
        if (is_null($content->school_id)) return true;
        
        // Gestor e Professor podem ver conteúdos da sua escola
        if (in_array($user->role, ['gestor', 'professor']) && $user->school_id === $content->school_id) return true;
        
        // Aluno pode ver conteúdos da sua escola
        if ($user->role === 'aluno' && $user->school_id === $content->school_id) return true;
        
        return false;
    }

    // Permissão para criar conteúdo
    public function create(User $user)
    {
        return in_array($user->role, ['admin', 'gestor', 'professor']);
    }

    // Permissão para atualizar conteúdo
    public function update(User $user, Content $content)
    {
        // Admin pode atualizar qualquer conteúdo
        if ($user->role === 'admin') return true;
        
        // Se conteúdo é global, apenas admin pode atualizar
        if (is_null($content->school_id)) return false;
        
        // Gestor pode atualizar conteúdos da sua escola
        if ($user->role === 'gestor' && $user->school_id === $content->school_id) return true;
        
        // Professor pode atualizar conteúdos da sua escola
        if ($user->role === 'professor' && $user->school_id === $content->school_id) return true;
        
        return false;
    }

    // Permissão para deletar conteúdo
    public function delete(User $user, Content $content)
    {
        // Admin pode deletar qualquer conteúdo
        if ($user->role === 'admin') return true;
        
        // Se conteúdo é global, apenas admin pode deletar
        if (is_null($content->school_id)) return false;
        
        // Gestor pode deletar conteúdos da sua escola
        if ($user->role === 'gestor' && $user->school_id === $content->school_id) return true;
        
        return false;
    }

    // Permissão para restaurar conteúdo
    public function restore(User $user, Content $content)
    {
        return in_array($user->role, ['admin', 'gestor']);
    }

    // Permissão para deletar permanentemente
    public function forceDelete(User $user, Content $content)
    {
        return $user->role === 'admin';
    }

    // Permissão para fazer download de conteúdo
    public function download(User $user, Content $content)
    {
        return $this->view($user, $content);
    }
}