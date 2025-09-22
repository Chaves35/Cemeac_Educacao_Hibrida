// backend/app/Policies/StudentActivityPolicy.php
<?php

namespace App\Policies;

use App\Models\User;
use App\Models\StudentActivity;
use Illuminate\Auth\Access\HandlesAuthorization;

class StudentActivityPolicy
{
    use HandlesAuthorization;

    // Permissão para visualizar qualquer registro de atividade
    public function viewAny(User $user)
    {
        return in_array($user->role, ['admin', 'gestor', 'professor']);
    }

    // Permissão para visualizar registro específico
    public function view(User $user, StudentActivity $studentActivity)
    {
        // Admin pode ver qualquer registro
        if ($user->role === 'admin') return true;
        
        // Gestor pode ver registros de usuários da sua escola
        if ($user->role === 'gestor') {
            $student = $studentActivity->user;
            return $user->school_id === $student->school_id;
        }
        
        // Professor pode ver registros de alunos da sua escola
        if ($user->role === 'professor') {
            $student = $studentActivity->user;
            return $user->school_id === $student->school_id && $student->role === 'aluno';
        }
        
        // Aluno pode ver próprios registros
        if ($user->role === 'aluno') {
            return $user->id === $studentActivity->user_id;
        }
        
        return false;
    }

    // Permissão para criar registro (iniciar atividade)
    public function create(User $user)
    {
        // Alunos podem criar registros (iniciar atividades)
        // Professores e gestores também podem criar para demonstração
        return in_array($user->role, ['aluno', 'professor', 'gestor']);
    }

    // Permissão para atualizar registro (responder atividade)
    public function update(User $user, StudentActivity $studentActivity)
    {
        // Admin pode atualizar qualquer registro
        if ($user->role === 'admin') return true;
        
        // Professor pode atualizar registros para correção manual
        if ($user->role === 'professor') {
            $student = $studentActivity->user;
            $activity = $studentActivity->activity;
            return $user->school_id === $student->school_id && 
                   $user->school_id === $activity->school_id;
        }
        
        // Aluno pode atualizar próprios registros (responder)
        if ($user->role === 'aluno') {
            return $user->id === $studentActivity->user_id;
        }
        
        return false;
    }

    // Permissão para deletar registro
    public function delete(User $user, StudentActivity $studentActivity)
    {
        // Admin pode deletar qualquer registro
        if ($user->role === 'admin') return true;
        
        // Gestor pode deletar registros de usuários da sua escola
        if ($user->role === 'gestor') {
            $student = $studentActivity->user;
            return $user->school_id === $student->school_id;
        }
        
        return false;
    }

    // Permissão para restaurar registro
    public function restore(User $user, StudentActivity $studentActivity)
    {
        return in_array($user->role, ['admin', 'gestor']);
    }

    // Permissão para deletar permanentemente
    public function forceDelete(User $user, StudentActivity $studentActivity)
    {
        return $user->role === 'admin';
    }

    // Permissão para resetar tentativas
    public function resetAttempts(User $user, StudentActivity $studentActivity)
    {
        return in_array($user->role, ['admin', 'gestor', 'professor']);
    }

    // Permissão para corrigir atividade subjetiva
    public function grade(User $user, StudentActivity $studentActivity)
    {
        // Apenas professores e gestores podem corrigir
        if (in_array($user->role, ['admin', 'gestor'])) return true;
        
        if ($user->role === 'professor') {
            $student = $studentActivity->user;
            $activity = $studentActivity->activity;
            return $user->school_id === $student->school_id && 
                   $user->school_id === $activity->school_id;
        }
        
        return false;
    }
}