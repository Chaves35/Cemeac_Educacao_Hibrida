<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ActivityResource;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Activity::class);

        $user = Auth::user();
        $query = Activity::with(['school', 'content'])
            ->withCount(['studentActivities', 'forumPosts']);

        // Filtro por escola (se não for admin)
        if ($user && $user->role !== 'admin') {
            $query->where('school_id', $user->school_id);
        }

        // Filtros de busca
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->has('type')) {
            $query->where('type', $request->input('type'));
        }

        if ($request->has('difficulty')) {
            $query->where('difficulty', $request->input('difficulty'));
        }

        $activities = $query->paginate(10);
        return ActivityResource::collection($activities);
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Activity::class);

        $user = Auth::user();
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'type' => 'required|in:verdadeiro_falso,multipla_escolha,drag_drop,subjetiva',
            'difficulty' => 'required|in:facil,medio,dificil',
            'max_score' => 'required|numeric|min:0|max:100',
            'school_id' => 'required|exists:schools,id',
            'content_id' => 'nullable|exists:contents,id'
        ]);

        // Se não for admin, força school_id do usuário
        if ($user && $user->role !== 'admin') {
            $validated['school_id'] = $user->school_id;
        }

        $activity = Activity::create($validated);
        return new ActivityResource($activity->load(['school', 'content']));
    }

    public function show(Activity $activity)
    {
        Gate::authorize('view', $activity);
        
        return new ActivityResource(
            $activity->load(['school', 'content'])
                    ->loadCount(['studentActivities', 'forumPosts'])
        );
    }

    public function update(Request $request, Activity $activity)
    {
        Gate::authorize('update', $activity);

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string|max:1000',
            'type' => 'sometimes|in:verdadeiro_falso,multipla_escolha,drag_drop,subjetiva',
            'difficulty' => 'sometimes|in:facil,medio,dificil',
            'max_score' => 'sometimes|numeric|min:0|max:100',
            'content_id' => 'nullable|exists:contents,id'
        ]);

        $activity->update($validated);
        return new ActivityResource($activity->refresh()->load(['school', 'content']));
    }

    public function destroy(Activity $activity)
    {
        Gate::authorize('delete', $activity);
        
        $activity->delete();
        return response()->json(['message' => 'Atividade removida com sucesso']);
    }
}