<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProjectController extends Controller
{
    /**
     * 1. Get all projects with pagination
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $projects = Project::select([
            'id',
            'title',
            'description',
            'priority',
            'status'
        ])
            ->when($search, function ($query, $search) {
                $query->where('title', 'like', '%' . $search . '%');
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return response()->json([
            'meta' => [
                'status' => 'success',
                'message' => 'Project list retrieved successfully',
            ],
            'data' => $projects->items(),
            'pagination' => [
                'current_page' => $projects->currentPage(),
                'last_page' => $projects->lastPage(),
                'per_page' => $projects->perPage(),
                'total' => $projects->total(),
            ],
        ]);
    }
    /**
     * 2. Create project
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'priority' => ['required', Rule::in(['low', 'medium', 'high'])],
            'budget' => ['nullable', 'numeric', 'min:0'],
            'project_url' => ['nullable', 'url', 'max:255'],
            'status' => ['required', Rule::in(['pending', 'in_progress', 'completed', 'cancelled'])],
            'client_name' => ['required', 'string', 'max:255'],
            'client_phone' => ['required', 'string', 'max:30'],
        ]);

        $project = Project::create($validated);

        return response()->json([
            'meta' => [
                'status' => 'success',
                'message' => 'Project created successfully',
            ],
            'data' => $project,
        ], 201);
    }

    /**
     * 3. Get project detail
     */
    public function show(Project $project)
    {
        return response()->json([
            'meta' => [
                'status' => 'success',
                'message' => 'Project details retrieved successfully',
            ],
            'data' => $project,
        ]);
    }

    /**
     * 4. Update project
     */
    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['sometimes', 'required', 'string'],
            'priority' => ['sometimes', 'required', Rule::in(['low', 'medium', 'high'])],
            'budget' => ['nullable', 'numeric', 'min:0'],
            'project_url' => ['nullable', 'url', 'max:255'],
            'status' => ['sometimes', 'required', Rule::in(['pending', 'in_progress', 'completed', 'cancelled'])],
            'client_name' => ['sometimes', 'required', 'string', 'max:255'],
            'client_phone' => ['sometimes', 'required', 'string', 'max:30'],
        ]);

        $project->update($validated);

        return response()->json([
            'meta' => [
                'status' => 'success',
                'message' => 'Project updated successfully',
            ],
            'data' => $project->fresh(),
        ]);
    }

    /**
     * 5. Delete project
     */
    public function destroy(Project $project)
    {
        $project->delete();

        return response()->json([
            'meta' => [
                'status' => 'success',
                'message' => 'Project deleted successfully',
            ],
            'data' => null,
        ]);
    }
}
