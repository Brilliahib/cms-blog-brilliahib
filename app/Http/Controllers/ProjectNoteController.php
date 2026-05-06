<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectNote;
use Illuminate\Http\Request;

class ProjectNoteController extends Controller
{
    /**
     * 1. Get all notes by project (with pagination)
     */
    public function index(Project $project)
    {
        $notes = ProjectNote::where('project_id', $project->id)
            ->latest()
            ->paginate(10);

        return response()->json([
            'meta' => [
                'status' => 'success',
                'message' => 'Project notes retrieved successfully',
            ],
            'data' => $notes->items(),
            'pagination' => [
                'current_page' => $notes->currentPage(),
                'last_page' => $notes->lastPage(),
                'per_page' => $notes->perPage(),
                'total' => $notes->total(),
            ],
        ]);
    }

    /**
     * 2. Create note
     */
    public function store(Request $request, Project $project)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'note' => ['required', 'string'],
        ]);

        $note = ProjectNote::create([
            ...$validated,
            'project_id' => $project->id,
        ]);

        return response()->json([
            'meta' => [
                'status' => 'success',
                'message' => 'Project note created successfully',
            ],
            'data' => $note,
        ], 201);
    }

    /**
     * 3. Get detail note
     */
    public function show(Project $project, ProjectNote $note)
    {
        // Optional safety check
        if ($note->project_id !== $project->id) {
            return response()->json([
                'meta' => [
                    'status' => 'error',
                    'message' => 'Note does not belong to this project',
                ],
                'data' => null,
            ], 404);
        }

        return response()->json([
            'meta' => [
                'status' => 'success',
                'message' => 'Project note retrieved successfully',
            ],
            'data' => $note,
        ]);
    }

    /**
     * 4. Update note
     */
    public function update(Request $request, Project $project, ProjectNote $note)
    {
        if ($note->project_id !== $project->id) {
            return response()->json([
                'meta' => [
                    'status' => 'error',
                    'message' => 'Note does not belong to this project',
                ],
                'data' => null,
            ], 404);
        }

        $validated = $request->validate([
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'note' => ['sometimes', 'required', 'string'],
        ]);

        $note->update($validated);

        return response()->json([
            'meta' => [
                'status' => 'success',
                'message' => 'Project note updated successfully',
            ],
            'data' => $note->fresh(),
        ]);
    }

    /**
     * 5. Delete note
     */
    public function destroy(Project $project, ProjectNote $note)
    {
        if ($note->project_id !== $project->id) {
            return response()->json([
                'meta' => [
                    'status' => 'error',
                    'message' => 'Note does not belong to this project',
                ],
                'data' => null,
            ], 404);
        }

        $note->delete();

        return response()->json([
            'meta' => [
                'status' => 'success',
                'message' => 'Project note deleted successfully',
            ],
            'data' => null,
        ]);
    }
}
