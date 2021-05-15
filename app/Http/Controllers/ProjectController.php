<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use SimpleXLSXGen;

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

    public function export(Request $request)
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

        $projectsJsonArray = $projects
            ->with('projectType')
            ->get(['student', 'supervisor', 'theme', 'group', 'project_type_id', 'created_at'])
            ->map(function ($project) {
                return [
                    'student' => $project->student,
                    'supervisor' => $project->supervisor,
                    'theme' => $project->theme,
                    'group' => $project->group,
                    'created_at' => $project->created_at->format('Y.m.d H:i:s'),
                    'project_type' => $project->projectType->name,
                ];
            })
            ->all();

        $xlsx = SimpleXLSXGen::fromArray(array_merge([[
            'Студент', 'Керівник', 'Тема', 'Група', 'Тип проекту', 'Дата реєстрації',
        ]], $projectsJsonArray), 'Sheet 1');
        $xlsx->downloadAs('Експортовані проекти.xlsx');
    }
}
