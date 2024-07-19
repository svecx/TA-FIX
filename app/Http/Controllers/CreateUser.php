<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class CreateUser extends Controller
{
    public function create()
    {
        return view('create_user');
    }

    public function listUser()
    {
        $users = User::all();
        return view('list_user', ['users' => $users]);
    }

    public function index()
    {
        // Periksa apakah pengguna telah terautentikasi
        if (auth()->check()) {
            $users = User::all();
            $currentUser = auth()->user();
            
            return view('list_user', compact('users', 'currentUser'));
        } else {
            // Redirect atau tangani jika pengguna belum terautentikasi
            return redirect()->route('login')->with('error', 'You need to login to access this page.');
        }
    }
    

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'jabatan' => 'required|string|max:255',
        ]);

        Log::info('Data received for storing:', $request->all());

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'jabatan' => $request->jabatan,
        ]);

        Log::info('User successfully created.');

        return redirect()->route('input-user')->with('success', 'User created successfully.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('edit_user', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'jabatan' => 'required|string|max:255',
        ]);

        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->jabatan = $request->jabatan;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('list-user')->with('success', 'User updated successfully.');
    }

    public function approveUser($id)
    {
        $user = User::find($id);
        if ($user) {
            // Toggle the approved status
            $user->approved = !$user->approved;
            $user->save();
            return redirect()->route('list-user')->with('success', 'User status updated successfully');
        } else {
            return redirect()->route('list-user')->with('error', 'User not found');
        }
    }

    public function approveAll()
{
    if (auth()->user()->jabatan === 'Kaprodi') {
        // Approve all students
        User::where('jabatan', 'Mahasiswa')->where('approved', false)->update(['approved' => true]);
    }
    
    return redirect()->route('list-user')->with('success', 'All students approved successfully.');
}

public function cancelAll()
{
    if (auth()->user()->jabatan === 'Kaprodi') {
        // Cancel approval for all students
        User::where('jabatan', 'Mahasiswa')->where('approved', true)->update(['approved' => false]);
    }
    
    return redirect()->route('list-user')->with('success', 'All students approval cancelled successfully.');
}


}