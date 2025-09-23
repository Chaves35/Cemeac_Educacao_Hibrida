<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\StudentActivityResource;
use App\Models\StudentActivity;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StudentActivityController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('viewAny', StudentActivity::class);

        $user = Auth::user();
        $query = StudentActivity::with(['user', 'activity']);

        // Filtros por role
        if ($user->role === 'aluno') {
            $query->where('user_id', $user->id);
        } elseif ($user->role === 'professor') {
            $query->whereHas('activity', function($q) use ($user) {
                $q->where('school_id', $user->school_id);
            });
        } elseif ($user->role === 'gestor') {
            $query->whereHas('user', function($q) use ($user) {
                $q->where('school_id', $user->school_id);
            });
        }

        // Filtros adicionais
        if ($request->has('activity_id')) {
            $query->where('activity_id', $request->input('activity_id'));
        }

        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->has('user_id') && $user->role !== 'aluno') {
            $query->where('user_id', $request->input('user_id'));
        }

        $studentActivities = $query->orderBy('created_at', 'desc')->paginate(10);
        return StudentActivityResource::collection($studentActivities);
    }

    public function store(Request $request)
    {
        Gate::authorize('create', StudentActivity::class);

        $user = Auth::user();

        $validated = $request->validate([
            'activity_id' => 'required|exists:activities,id',
        ]);

        // Verificar se o usuário já tem um registro para esta atividade
        $existingRecord = StudentActivity::where('user_id', $user->id)
            ->where('activity_id', $validated['activity_id'])
            ->first();

        if ($existingRecord) {
            return response()->json([
                'message' => 'Você já iniciou esta atividade'
            ], 422);
        }

        // Verificar se a atividade pertence à escola do usuário
        $activity = Activity::findOrFail($validated['activity_id']);
        if ($user->role === 'aluno' && $activity->school_id !== $user->school_id) {
            return response()->json([
                'message' => 'Você não tem permissão para acessar esta atividade'
            ], 403);
        }

        $studentActivity = StudentActivity::create([
            'user_id' => $user->id,
            'activity_id' => $validated['activity_id'],
            'status' => 'em_progresso',
            'attempts' => 1,
            'started_at' => Carbon::now(),
        ]);

        return new StudentActivityResource($studentActivity->load(['user', 'activity']));
    }

    public function show(StudentActivity $studentActivity)
    {
        Gate::authorize('view', $studentActivity);
        
        return new StudentActivityResource(
            $studentActivity->load(['user', 'activity'])
        );
    }

    public function update(Request $request, StudentActivity $studentActivity)
    {
        Gate::authorize('update', $studentActivity);

        $validated = $request->validate([
            'score' => 'nullable|numeric|min:0|max:100',
            'status' => 'sometimes|in:nao_iniciado,em_progresso,concluido,reprovado',
            'attempts' => 'sometimes|integer|min:1|max:10'
        ]);

        // Se está sendo concluída, adicionar completed_at
        if (isset($validated['status']) && $validated['status'] === 'concluido') {
            $validated['completed_at'] = Carbon::now();
        }

        $studentActivity->update($validated);
        return new StudentActivityResource($studentActivity->refresh()->load(['user', 'activity']));
    }

    public function destroy(StudentActivity $studentActivity)
    {
        Gate::authorize('delete', $studentActivity);
        
        $studentActivity->delete();
        return response()->json(['message' => 'Registro de atividade removido com sucesso']);
    }

    // Método personalizado para submeter resposta
    public function submit(Request $request, StudentActivity $studentActivity)
    {
        Gate::authorize('update', $studentActivity);

        $validated = $request->validate([
            'answers' => 'required|array',
            'score' => 'nullable|numeric|min:0|max:100'
        ]);

        $studentActivity->update([
            'status' => 'concluido',
            'score' => $validated['score'] ?? 0,
            'completed_at' => Carbon::now(),
            'attempts' => $studentActivity->attempts + 1
        ]);

        return new StudentActivityResource($studentActivity->refresh());
    }

    // Método personalizado para resetar tentativas
    public function resetAttempts(StudentActivity $studentActivity)
    {
        Gate::authorize('resetAttempts', $studentActivity);

        $studentActivity->update([
            'status' => 'nao_iniciado',
            'score' => null,
            'attempts' => 0,
            'started_at' => null,
            'completed_at' => null
        ]);

        return new StudentActivityResource($studentActivity->refresh());
    }
}