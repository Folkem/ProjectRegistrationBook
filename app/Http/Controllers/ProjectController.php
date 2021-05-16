<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use SimpleXLSX;
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
        
        /** @noinspection PhpUndefinedMethodInspection */
        $projects = $projects->paginate(10)->withQueryString();
        $types = ProjectType::all();
        
        return view('projects.index', compact('projects', 'types'));
    }
    
    public function create()
    {
        $types = ProjectType::all();
        
        return view('projects.create', compact('types'));
    }
    
    public function store(Request $request): RedirectResponse
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
    
    public function upload()
    {
        $types = ProjectType::all();
        
        return view('projects.upload', compact('types'));
    }
    
    public function uploadPreview(Request $request)
    {
        if (!(
            ($hasFile = $request->hasFile('excel-file')) &&
            ($isValid = $request->file('excel-file')->isValid()) &&
            ($request->file('excel-file')->extension() === 'xlsx')
        )) {
            $message = 'Виникла помилка. ';
            if (!$hasFile) $message .= 'Файл не був вказаний.';
            elseif (!$isValid) $message .= 'Файл не валідний.';
            else $message .= 'Розширення вказаного файлу не є xlsx.';
            
            return json_encode([
                'status' => 'failure',
                'message' => $message,
            ]);
        }
        
        $data = [];
        
        $simpleXlsx = SimpleXLSX::parse($request->file('excel-file')->getPathname());
        
        if (!$simpleXlsx || !$simpleXlsx->success()) {
            return json_encode([
                'status' => 'failure',
                'message' => 'Помилка обробки файлу. ',
            ]);
        }
        
        foreach (array_slice($simpleXlsx->rows(), 1) as $row) {
            $data[] = [
                'student' => $row[0],
                'theme' => $row[1],
                'group' => $row[2],
                'supervisor' => $row[3],
            ];
        }
        
        return json_encode([
            'status' => 'success',
            'data' => $data,
        ]);
    }
    
    public function uploadStore(Request $request)
    {
        $projectTypeId = $request->input('project-type');
        
        if (is_null($projectTypeId) ||
            is_null(ProjectType::query()->find($projectTypeId))) {
            return json_encode([
                'status' => 'failure',
                'message' => 'Тип проекту не вказаний або не валідний.',
            ]);
        }
        
        $projects = $request->input('projects');
        
        if (is_null($projects)) {
            return json_encode([
                'status' => 'failure',
                'message' => 'Параметр "проекти" не був вказаний.',
            ]);
        }
        
        $projects = json_decode($projects);
        
        // something seems wrong...
        // todo: refactor
        if (!is_array($projects) || count($projects) === 0) {
            return json_encode([
                'status' => 'failure',
                'message' => 'Ви відіслали 0 проектів.',
            ]);
        }
        
        $projects = array_map(function ($project) {
            return json_decode(json_encode($project), true);
        }, $projects);
        
        foreach ($projects as $project) {
            Project::query()->create([
                'student' => $project['student'],
                'group' => $project['group'],
                'supervisor' => $project['supervisor'],
                'theme' => $project['theme'],
                'project_type_id' => $projectTypeId,
            ]);
        }
        
        return json_encode([
            'status' => 'success',
            'message' => 'Проекти були успішно зареєстровані',
        ]);
    }
    
    public function destroy(Project $project): RedirectResponse
    {
        $project->delete();
        
        return back();
    }
    
    public function edit(Project $project)
    {
        $types = ProjectType::all();
        
        return view('projects.edit', compact('project', 'types'));
    }
    
    public function update(Request $request, Project $project): RedirectResponse
    {
        $projectTypeIds = ProjectType::query()
            ->get('id')
            ->map(function ($projectType) {
                return $projectType->id;
            })->toArray();
        
        $validated = $request->validate([
            'student' => 'required|string|between:3,255',
            'group' => 'required|string|between:3,255',
            'supervisor' => 'required|string|between:3,255',
            'theme' => 'required|string|between:3,255',
            'project_type_id' => [
                'required',
                Rule::in($projectTypeIds),
            ],
        ]);
        
        $project->update($validated);
        
        return back()->with('message', 'Проект оновлено.');
    }
}
