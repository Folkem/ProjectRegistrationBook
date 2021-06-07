<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GroupController extends Controller
{
    public function index()
    {
        $groups = Group::query()->paginate(10);
        
        return view('groups.index', compact('groups'));
    }
    
    public function create()
    {
        return view('groups.create');
    }
    
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'between:3,10',
                Rule::unique('groups', 'name'),
            ],
        ]);
        
        Group::query()->create(['name' => $request->input('name')]);
        
        return back()->with('message', 'Група успішно створена.');
    }
    
    public function edit(Group $group)
    {
        return view('groups.edit', compact('group'));
    }
    
    public function update(Request $request, Group $group): RedirectResponse
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'between:3,10',
                Rule::unique('groups', 'name')
                    ->ignore($group->name, 'name'),
            ],
        ]);
        
        $group->update(['name' => $request->input('name')]);
        
        return back()->with('message', 'Група успішно оновлена.');
    }
    
    public function destroy(Group $group): RedirectResponse
    {
        $group->delete();
        
        return back();
    }
}
