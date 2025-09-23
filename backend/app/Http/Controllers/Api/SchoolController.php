<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SchoolResource;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SchoolController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('viewAny', School::class);

        $schools = School::withCount('users')
            ->when($request->input('search'), function($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('city', 'like', "%{$search}%")
                      ->orWhere('state', 'like', "%{$search}%");
            })
            ->paginate(10);

        return SchoolResource::collection($schools);
    }

    public function store(Request $request)
    {
        Gate::authorize('create', School::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:schools,name',
            'inep_code' => 'required|string|size:8|unique:schools,inep_code',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|size:2'
        ]);

        $school = School::create($validated);
        return new SchoolResource($school);
    }

    public function show(School $school)
    {
        Gate::authorize('view', $school);
        return new SchoolResource($school->loadCount('users')->load('users'));
    }

    public function update(Request $request, School $school)
    {
        Gate::authorize('update', $school);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255|unique:schools,name,' . $school->id,
            'inep_code' => 'sometimes|string|size:8|unique:schools,inep_code,' . $school->id,
            'address' => 'sometimes|string|max:255',
            'city' => 'sometimes|string|max:100',
            'state' => 'sometimes|string|size:2'
        ]);

        $school->update($validated);
        return new SchoolResource($school);
    }

    public function destroy(School $school)
    {
        Gate::authorize('delete', $school);
        $school->delete();
        return response()->json(['message' => 'Escola removida com sucesso']);
    }
}