<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller
{
    public function index()
    {
        return view('settings');
    }
    
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'between:3,255', 'string'],
            'password' => ['required', 'between:3,255', 'string']
        ]);
        
        auth()->user()->update([
            'name' => $request->input('name'),
            'password' => Hash::make($request->input('password')),
        ]);
        
        return back()->with('message', 'Дані успішно оновлені.');
    }
}
