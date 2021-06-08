<?php

namespace App\Http\Controllers;

use App\Models\ProjectType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProjectTypeController extends Controller
{
    public function index()
    {
        $projectTypes = ProjectType::query()->withCount('projects')->get();
        
        return view('project-types.index', compact('projectTypes'));
    }
    
    public function create()
    {
        return view('project-types.create');
    }
    
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => [
                'required', 'string', 'between:2,255',
                Rule::unique('project_types', 'name'),
            ],
        ]);
        
        ProjectType::query()->create($request->only('name'));
        
        return back()->with('message', 'Вид проекту було додано.');
    }
    
    public function edit(ProjectType $projectType)
    {
        return view('project-types.edit', compact('projectType'));
    }
    
    public function update(Request $request, ProjectType $projectType): RedirectResponse
    {
        $request->validate([
            'name' => [
                'required', 'string', 'between:2,255',
                Rule::unique('project_types', 'name')->ignore($projectType->name, 'name'),
            ],
        ]);
        
        $projectType->update($request->only('name'));
        
        return back()->with('message', 'Вид проекту було оновлено.');
    }
    
    public function destroy(ProjectType $projectType)
    {
        if ($projectType->projects()->count() > 0) {
            return back()->withErrors([
                'delete' => 'Спочатку видаліть чи відредагуйте усі проекти з даним керівником',
            ]);
        }
        
        $projectType->delete();
        
        return back();
    }
}
