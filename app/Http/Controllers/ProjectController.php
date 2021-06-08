<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUpdateProjectRequest;
use App\Models\Group;
use App\Models\Project;
use App\Models\ProjectType;
use App\Models\Supervisor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
        if ($theme = $request->get('theme')) {
            $projects = $projects->where('theme', 'like', "%{$theme}%");
        }
        if ($yearFrom = $request->get('year_from')) {
            $projects = $projects->whereYear('registered_at', '>=', $yearFrom);
        }
        if ($yearTo = $request->get('year_to')) {
            $projects = $projects->whereYear('registered_at', '<=', $yearTo);
        }
        if (($supervisorId = $request->get('supervisor_id')) != null && $supervisorId != 'null') {
            $projects = $projects->where('supervisor_id', $supervisorId);
        }
        if (($groupId = $request->get('group_id')) != null && $groupId != 'null') {
            $projects = $projects->where('group_id', $groupId);
        }
        if (($projectTypeId = $request->get('project_type_id')) != null && $projectTypeId != 'null') {
            $projects = $projects->where('project_type_id', $projectTypeId);
        }
        
        $projects = $projects->get();
        $projectTypes = ProjectType::all();
        $supervisors = Supervisor::all();
        $groups = Group::all();
        
        return view('projects.index', compact('projects',
            'projectTypes', 'groups', 'supervisors'));
    }
    
    public function create()
    {
        $types = ProjectType::all();
        $groups = Group::all();
        $supervisors = Supervisor::all();
        
        return view('projects.create', compact('types', 'groups', 'supervisors'));
    }
    
    public function store(CreateUpdateProjectRequest $request): RedirectResponse
    {
        Project::query()->create([
            'registration_number' => $request->input('registration_number'),
            'student' => $request->input('student'),
            'supervisor_id' => Supervisor::query()
                ->where('name', $request->input('supervisor'))->first()->id,
            'group_id' => Group::query()
                ->where('name', $request->input('group'))->first()->id,
            'project_type_id' => ProjectType::query()
                ->where('name', $request->input('project_type'))->first()->id,
            'theme' => $request->input('theme'),
            'registered_at' => $request->input('registered_at'),
            'defended_at' => $request->input('defended_at'),
            'grade' => $request->input('grade'),
        ]);
        
        return back()->with('message', 'Проект зареєстровано.');
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
            'Студент', 'Керівник', 'Тема', 'Група', 'Дата реєстрації', 'Тип проекту',
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
    
    // todo:
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
        if (!is_array($projects) || count($projects) === 0) {
            return json_encode([
                'status' => 'failure',
                'message' => 'Ви відіслали 0 проектів.',
            ]);
        }
        
        $projects = array_map(function ($project) {
            return json_decode(json_encode($project), true);
        }, $projects);
        
        $existingRegistrationNumbers = Project::all(['id'])->map(fn($project) => $project->registration_number)->toArray();
        $registrationNumber = 0;
        foreach ($projects as $project) {
            $registrationNumber++;
            while (in_array($registrationNumber, $existingRegistrationNumbers)) {
                $registrationNumber++;
            }
            Project::query()->create([
                'registration_number' => $registrationNumber,
                'student' => $project['student'],
                'group_id' => Group::query()->firstOrCreate(['name' => $project['group']])->id,
                'supervisor_id' => Supervisor::query()->firstOrCreate(['name' => $project['supervisor']])->id,
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
    
    public function update(CreateUpdateProjectRequest $request, Project $project): RedirectResponse
    {
        $project->update([
            'registration_number' => $request->input('registration_number'),
            'student' => $request->input('student'),
            'supervisor_id' => Supervisor::query()
                ->where('name', $request->input('supervisor'))->first()->id,
            'group_id' => Group::query()
                ->where('name', $request->input('group'))->first()->id,
            'project_type_id' => ProjectType::query()
                ->where('name', $request->input('project_type'))->first()->id,
            'theme' => $request->input('theme'),
            'registered_at' => $request->input('registered_at'),
            'defended_at' => $request->input('defended_at'),
            'grade' => $request->input('grade'),
        ]);
        
        return back()->with('message', 'Проект оновлено.');
    }
}
