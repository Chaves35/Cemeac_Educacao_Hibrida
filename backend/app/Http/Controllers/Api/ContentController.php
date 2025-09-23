<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ContentResource;
use App\Models\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class ContentController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Content::class);

        $user = Auth::user();
        $query = Content::with('school')->withCount('activities');

        // Filtro por escola (se não for admin)
        if ($user && $user->role !== 'admin') {
            $query->where(function($q) use ($user) {
                $q->where('school_id', $user->school_id)
                  ->orWhereNull('school_id'); // Conteúdos globais
            });
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

        if ($request->has('school_id')) {
            $query->where('school_id', $request->input('school_id'));
        }

        $contents = $query->paginate(10);
        return ContentResource::collection($contents);
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Content::class);

        $user = Auth::user();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'type' => 'required|in:video,documento,link_externo,texto',
            'url' => 'nullable|url',
            'file_path' => 'nullable|string',
            'school_id' => 'nullable|exists:schools,id'
        ]);

        // Validações específicas por tipo
        if ($validated['type'] === 'link_externo' && empty($validated['url'])) {
            return response()->json([
                'message' => 'URL é obrigatória para conteúdo do tipo link externo'
            ], 422);
        }

        if (in_array($validated['type'], ['video', 'documento']) && empty($validated['file_path'])) {
            return response()->json([
                'message' => 'Arquivo é obrigatório para este tipo de conteúdo'
            ], 422);
        }

        // Se não for admin, força school_id do usuário (exceto para conteúdos globais)
        if ($user && $user->role !== 'admin' && isset($validated['school_id'])) {
            $validated['school_id'] = $user->school_id;
        }

        $content = Content::create($validated);
        return new ContentResource($content->load('school'));
    }

    public function show(Content $content)
    {
        Gate::authorize('view', $content);
        
        return new ContentResource(
            $content->load('school')->loadCount('activities')
        );
    }

    public function update(Request $request, Content $content)
    {
        Gate::authorize('update', $content);

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string|max:1000',
            'type' => 'sometimes|in:video,documento,link_externo,texto',
            'url' => 'nullable|url',
            'file_path' => 'nullable|string'
        ]);

        // Validações específicas por tipo (se type foi alterado)
        if (isset($validated['type'])) {
            if ($validated['type'] === 'link_externo' && empty($validated['url']) && empty($content->url)) {
                return response()->json([
                    'message' => 'URL é obrigatória para conteúdo do tipo link externo'
                ], 422);
            }

            if (in_array($validated['type'], ['video', 'documento']) && 
                empty($validated['file_path']) && empty($content->file_path)) {
                return response()->json([
                    'message' => 'Arquivo é obrigatório para este tipo de conteúdo'
                ], 422);
            }
        }

        $content->update($validated);
        return new ContentResource($content->refresh()->load('school'));
    }

    public function destroy(Content $content)
    {
        Gate::authorize('delete', $content);
        
        $content->delete();
        return response()->json(['message' => 'Conteúdo removido com sucesso']);
    }
}