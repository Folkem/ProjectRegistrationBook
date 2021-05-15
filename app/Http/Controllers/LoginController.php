<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'password' => 'required',
        ]);

        if (!auth()->attempt($validated)) {
            return back()->withErrors([
                'message' => 'Користувача зі вказаними даними не було знайдено',
            ]);
        }

        return redirect('/');
    }

    public function logout()
    {
        auth()->logout();

        return redirect('/');
    }
}
