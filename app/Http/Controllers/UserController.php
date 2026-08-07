<?php

namespace App\Http\Controllers;

use App\Http\Requests\SignupRequest;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function signup(SignupRequest $request)
    {
        try {
            User::create($request->validated());

            return redirect()->route('users')->with('success', 'Account created successfully!');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Failed to create user. Please try again.');
        }
    }

    public function get(): Response
    {
        $users = User::get()->all();

        return Inertia::render('users/Index', [
            'data' => $users,
        ]);
    }
}