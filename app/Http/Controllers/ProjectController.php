<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $projects = Project::query();
        if ($student = $request->get('student')) {
            $projects = $projects->where('student', 'like', "%{$student}%");
        }
        if ($supervisor = $request->get('supervisor')) {
            $projects = $projects->where('supervisor', 'like', "%{$supervisor}%");
        }
        if ($theme = $request->get('theme')) {
            $projects = $projects->where('theme', 'like', "%{$theme}%");
        }
        if ($group = $request->get('group')) {
            $projects = $projects->where('group', 'like', "%{$group}%");
        }
        if ($project_type_id = $request->get('project_type_id')) {
            $projects = $projects->where('project_type_id', 'like', "%{$project_type_id}%");
        }

        $projects = $projects->paginate(10)->withQueryString();
        $types = ProjectType::all();

        return view('projects.index', compact('projects', 'types'));
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
