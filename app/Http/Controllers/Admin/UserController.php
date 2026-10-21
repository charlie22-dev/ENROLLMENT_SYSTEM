<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('student')->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $programs = Program::all();
        $advisers = User::where('role', 'adviser')->get();
        return view('admin.users.create', compact('programs', 'advisers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users',
            'password'   => 'required|string|min:8|confirmed',
            'role'       => 'required|in:student,adviser,admission,registrar,cashier,admin',
            'student_no' => 'required_if:role,student|nullable|string|unique:students',
            'program_id' => 'required_if:role,student|nullable|exists:programs,id',
            'year_level' => 'required_if:role,student|nullable|integer|between:1,5',
            'adviser_id' => 'nullable|exists:users,id',
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => $data['role'],
        ]);

        if ($data['role'] === 'student') {
            Student::create([
                'user_id'    => $user->id,
                'student_no' => $data['student_no'],
                'program_id' => $data['program_id'],
                'year_level' => $data['year_level'],
                'adviser_id' => $data['adviser_id'] ?? null,
            ]);
        }

        return redirect()->route('admin.users.index')->with('success', 'User created.');
    }

    public function edit(User $user)
    {
        $user->load('student');
        $programs = Program::all();
        $advisers = User::where('role', 'adviser')->get();
        return view('admin.users.edit', compact('user', 'programs', 'advisers'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email,' . $user->id,
            'password'   => 'nullable|string|min:8|confirmed',
            'role'       => 'required|in:student,adviser,admission,registrar,cashier,admin',
            'student_no' => 'required_if:role,student|nullable|string|unique:students,student_no,' . optional($user->student)->id,
            'program_id' => 'required_if:role,student|nullable|exists:programs,id',
            'year_level' => 'required_if:role,student|nullable|integer|between:1,5',
            'adviser_id' => 'nullable|exists:users,id',
        ]);

        $user->update([
            'name'  => $data['name'],
            'email' => $data['email'],
            'role'  => $data['role'],
            ...(filled($data['password'] ?? null) ? ['password' => Hash::make($data['password'])] : []),
        ]);

        if ($data['role'] === 'student') {
            Student::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'student_no' => $data['student_no'],
                    'program_id' => $data['program_id'],
                    'year_level' => $data['year_level'],
                    'adviser_id' => $data['adviser_id'] ?? null,
                ]
            );
        }

        return redirect()->route('admin.users.index')->with('success', 'User updated.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted.');
    }
}
