<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    public function user()
    {
        $users = User::all(); // ini nama variabel HARUS sesuai dengan compact()
        return view('dashboard_it_manager.dashboard_user.view-user', compact('users'));
    }
    public function create()
    {
        return view('dashboard_it_manager.dashboard_user.create-user');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:user,username',
            'password' => 'required|string|min:6',
            'role' => 'required|string'
        ]);

        \App\Models\User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role']
        ]);

        return redirect()->route('dashboard_it_manager.view-user')->with('success', 'User berhasil ditambahkan!');
    }
    public function edit($user_id)
    {
        $user = User::findOrFail($user_id);
        return view('dashboard_it_manager.dashboard_user.edit-user', compact('user'));
    }
    
    public function update(Request $request, $user_id)
    {
        $user = User::findOrFail($user_id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:user,username,' . $user->user_id. ',user_id',
            'role' => 'required|string',
            'password' => 'nullable|string|min:6',
        ]);

        $user->name = $validated['name'];
        $user->username = $validated['username'];
        $user->role = $validated['role'];

        if (!empty($validated['password'])) {
            $user->password = bcrypt($validated['password']);
        }

        $user->save();

        return redirect()->route('dashboard_it_manager.view-user')->with('success', 'User berhasil diperbarui!');
    }
    public function destroy($user_id)
    {
        $user = User::findOrFail($user_id);
        $user->delete();
        return redirect()->back()->with('success', 'User berhasil dihapus!');
    }

}
