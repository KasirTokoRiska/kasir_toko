<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $users = User::orderBy('id')
            ->when($search, function ($q, $search) {
                return $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%"); // Changed 'orwhere' to 'orWhere'
            })
            ->paginate();
        if ($search)
            $users->appends(['search' => $search]);
        return view('user.index', [
            'users' => $users
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('user.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => ['required', 'max:100'],
            'username' => ['required', 'max:100', 'unique:users'],
            'role' => ['required', 'in:admin,petugas'],
            'password' => ['required', 'max:100', 'confirmed']
        ]);

        $user = new User(); // Create a new instance of the User model
        $user->nama = $request->nama; // Assign the 'nama' field from the request
        $user->username = $request->username;
        $user->role = $request->role;
        $user->password = bcrypt($request->password);

        $user->save(); // Save the user to the database

        return redirect()->route('user.index')->with('store', 'success');
    }


    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('user.edit', [
            'user' => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'nama' => ['required', 'max:100'],
            'username' => ['required', 'max:100', 'unique:users,username,' . $user->id], // Removed extra space after 'unique:users'
            'role' => ['required', 'in:admin,petugas'],
            'password_baru' => ['nullable', 'max:100', 'confirmed']
        ]);
        if ($request->password_baru) { // Fixed if condition, changed '>' to '->'
            $request->merge([
                "password" => bcrypt($request->password_baru)
            ]);
            $user->update($request->all()); // Changed '> all()' to '->all()'
        } else {
            $user->update($request->only('nama', 'username', 'role'));
        }
        return redirect()->route('user.index')->with('update', 'success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete(); // Changed '- delete()' to '->delete()'
        return back()->with('destroy', 'success');
    }
}
