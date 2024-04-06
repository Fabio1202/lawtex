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

    public function store()
    {
        Gate::authorize('create', Project::class);

        // Validate the request
        request()->validate([
            'name' => 'required',
        ]);

        // Create a new project
        $project = new Project();
        $project->name = request('name');
        request()->user()->projects()->save($project);

        return redirect(route('projects.index'));
    }

    public function rename(Project $project)
    {
        Gate::authorize('update', $project);

        // Validate the request
        request()->validate([
            'name' => 'required',
        ]);

        $project->name = request('name');
        $project->save();

        return redirect(route('projects.index'));
    }

    public function show(Project $project)
    {
        Gate::authorize('view', $project);

        return view('projects.show', [
            'project' => $project,
        ]);
    }

    public function latex(Project $project)
    {
        return response($project->toLatex(), 200, [
            'Content-Type' => 'text/plain',
            'Content-Disposition' => 'attachment; filename="' . $project->name . '.tex"',
        ]);
    }
}
