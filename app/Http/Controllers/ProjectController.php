<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Support\Facades\Gate;

class ProjectController extends Controller
{
    public function index()
    {
        Gate::authorize('viewAny', Project::class);

        return view('projects.index', [
            'projects' => auth()->user()->projects,
        ]);
    }

    public function destroy(Project $project)
    {
        Gate::authorize('delete', $project);

        $project->delete();

        return redirect(route('projects.index'));
    }
}
