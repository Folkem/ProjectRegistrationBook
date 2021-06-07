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
        $supervisors = Supervisor::all();
        
        return view('supervisors.index', compact('supervisors'));
    }
    
    public function create()
    {
        return view('supervisors.index');
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
        
        return back()->with('message', 'Керівник успішно створений.');
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
                'between:3,10',
                Rule::unique('groups', 'name')
                    ->ignore($supervisor->name, 'name'),
            ],
        ]);
        
        $supervisor->update(['name' => $request->input('name')]);
        
        return back()->with('message', 'Керівник успішно оновлений.');
    }
    
    public function destroy(Supervisor $supervisor): RedirectResponse
    {
        $supervisor->delete();
        
        return back();
    }
}
