<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::paginate(10)->withQueryString();

        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        $types = ProjectType::all();

        return view('projects.create', compact('types'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student' => ['between:3,255', 'required', 'string'],
            'supervisor' => ['between:3,255', 'required', 'string'],
            'theme' => ['between:3,255', 'required', 'string'],
            'group' => ['between:3,255', 'required', 'string'],
            'project_type_id' => [
                'required',
                Rule::in(ProjectType::query()->get('id')->modelKeys()),
            ],
        ]);

        Project::query()->create($validated);

        return back()->with('message', 'Проект зареєстровано');
    }

    public function show(Project $project)
    {
        //
    }

    public function edit(Project $project)
    {
        //
    }

    public function update(Request $request, Project $project)
    {
        //
    }

    public function destroy(Project $project)
    {
        //
    }
}
