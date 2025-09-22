// backend/app/Policies/ForumPostPolicy.php
<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ForumPost;
use Illuminate\Auth\Access\HandlesAuthorization;

class ForumPostPolicy
{
    use HandlesAuthorization;

    // Permissão para visualizar qualquer post do fórum
    public function viewAny(User $user)
    {
        // Todos os usuários autenticados podem ver posts
        return true;
    }

    // Permissão para visualizar post específico
    public function view(User $user, ForumPost $forumPost)
    {
        // Admin pode ver qualquer post
        if ($user->role === 'admin') return true;
        
        // Se o post está relacionado a uma atividade
        if ($forumPost->activity_id) {
            $activity = $forumPost->activity;
            // Usuário deve estar na mesma escola da atividade
            return $user->school_id === $activity->school_id;
        }
        
        // Posts gerais podem ser vistos por todos
        return true;
    }

    // Permissão para criar post
    public function create(User $user)
    {
        // Todos os usuários autenticados podem criar posts
        return true;
    }

    // Permissão para atualizar post
    public function update(User $user, ForumPost $forumPost)
    {
        // Admin pode atualizar qualquer post
        if ($user->role === 'admin') return true;
        
        // Gestor pode atualizar posts de usuários da sua escola
        if ($user->role === 'gestor') {
            $postAuthor = $forumPost->user;
            return $user->school_id === $postAuthor->school_id;
        }
        
        // Autor pode atualizar próprio post
        return $user->id === $forumPost->user_id;
    }

    // Permissão para deletar post
    public function delete(User $user, ForumPost $forumPost)
    {
        // Admin pode deletar qualquer post
        if ($user->role === 'admin') return true;
        
        // Gestor pode deletar posts de usuários da sua escola
        if ($user->role === 'gestor') {
            $postAuthor = $forumPost->user;
            return $user->school_id === $postAuthor->school_id;
        }
        
        // Autor pode deletar próprio post
        return $user->id === $forumPost->user_id;
    }

    // Permissão para restaurar post
    public function restore(User $user, ForumPost $forumPost)
    {
        return in_array($user->role, ['admin', 'gestor']);
    }

    // Permissão para deletar permanentemente
    public function forceDelete(User $user, ForumPost $forumPost)
    {
        return $user->role === 'admin';
    }

    // Permissão para fixar post
    public function pin(User $user, ForumPost $forumPost)
    {
        return in_array($user->role, ['admin', 'gestor', 'professor']);
    }

    // Permissão para responder post
    public function reply(User $user, ForumPost $forumPost)
    {
        // Todos podem responder se podem ver o post
        return $this->view($user, $forumPost);
    }
}