<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ForumPostResource;
use App\Models\ForumPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class ForumPostController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('viewAny', ForumPost::class);

        $user = Auth::user();
        $query = ForumPost::with(['user', 'activity'])
            ->withCount('replies')
            ->whereNull('parent_id'); // Apenas posts principais

        // Filtro por escola (através da atividade)
        if ($user && $user->role !== 'admin') {
            $query->whereHas('activity', function($q) use ($user) {
                $q->where('school_id', $user->school_id);
            })->orWhereNull('activity_id'); // Posts gerais
        }

        // Filtros
        if ($request->has('activity_id')) {
            $query->where('activity_id', $request->input('activity_id'));
        }

        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $posts = $query->orderBy('created_at', 'desc')->paginate(10);
        return ForumPostResource::collection($posts);
    }

    public function store(Request $request)
    {
        Gate::authorize('create', ForumPost::class);

        $user = Auth::user();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|min:10|max:5000',
            'activity_id' => 'nullable|exists:activities,id',
            'parent_id' => 'nullable|exists:forum_posts,id',
            'status' => 'nullable|in:ativo,fixado,arquivado'
        ]);

        $validated['user_id'] = $user->id;
        $validated['status'] = $validated['status'] ?? 'ativo';

        // Se é uma resposta, não precisa de título
        if ($validated['parent_id']) {
            $validated['title'] = null;
        }

        $post = ForumPost::create($validated);
        return new ForumPostResource($post->load(['user', 'activity']));
    }

    public function show(ForumPost $forumPost)
    {
        Gate::authorize('view', $forumPost);
        
        return new ForumPostResource(
            $forumPost->load(['user', 'activity', 'replies.user'])
                     ->loadCount('replies')
        );
    }

    public function update(Request $request, ForumPost $forumPost)
    {
        Gate::authorize('update', $forumPost);

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'content' => 'sometimes|string|min:10|max:5000',
            'status' => 'sometimes|in:ativo,fixado,arquivado'
        ]);

        $forumPost->update($validated);
        return new ForumPostResource($forumPost->refresh()->load(['user', 'activity']));
    }

    public function destroy(ForumPost $forumPost)
    {
        Gate::authorize('delete', $forumPost);
        
        $forumPost->delete();
        return response()->json(['message' => 'Post removido com sucesso']);
    }

    // Método personalizado para listar respostas de um post
    public function replies(ForumPost $forumPost)
    {
        Gate::authorize('view', $forumPost);

        $replies = $forumPost->replies()
            ->with(['user', 'activity'])
            ->orderBy('created_at', 'asc')
            ->paginate(10);

        return ForumPostResource::collection($replies);
    }

    // Método personalizado para fixar/desfixar post
    public function pin(ForumPost $forumPost)
    {
        Gate::authorize('pin', $forumPost);

        $status = $forumPost->status === 'fixado' ? 'ativo' : 'fixado';
        $forumPost->update(['status' => $status]);

        return new ForumPostResource($forumPost->refresh());
    }
}