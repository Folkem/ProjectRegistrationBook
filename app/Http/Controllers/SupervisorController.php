<?php

namespace App\Http\Controllers;

use App\Models\Supervisor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SupervisorController extends Controller
{
    public function index()
    {
        $supervisors = Supervisor::query()->withCount('projects')->paginate(10);
        
        return view('supervisors.index', compact('supervisors'));
    }
    
    public function create()
    {
        return view('supervisors.create');
    }
    
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'between:3,255',
                Rule::unique('supervisors', 'name'),
            ]
        ]);
        
        Supervisor::query()->create(['name' => $request->input('name')]);
        
        return back()->with('message', 'Керівник успішно доданий.');
    }
    
    public function edit(Supervisor $supervisor)
    {
        return view('supervisors.edit', compact('supervisor'));
    }
    
    public function update(Request $request, Supervisor $supervisor): RedirectResponse
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'between:3,255',
                Rule::unique('groups', 'name')
                    ->ignore($supervisor->name, 'name'),
            ],
        ]);
        
        $supervisor->update(['name' => $request->input('name')]);
        
        return back()->with('message', 'Керівник успішно оновлений.');
    }
    
    public function destroy(Supervisor $supervisor): RedirectResponse
    {
        if ($supervisor->projects()->count() < 0) {
            return back()->with([
                'message' => 'Спочатку видаліть чи відредагуйте усі проекти з даним керівником',
            ]);
        }
        
        $supervisor->delete();
        
        return back();
    }
}
