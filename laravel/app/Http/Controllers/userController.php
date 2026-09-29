<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller {

    public function index(Request $request) {

        $totalUser = User::count();
        $userAktif = $totalUser;
        $userNonAktif = 0;

        $query = User::query();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%") 
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->has('role') && $request->role != '') {
            $query->where('role', $request->role);
        }

        $users = $query->orderByRaw("FIELD(role, 'admin', 'petugas') ASC")
               ->latest()
               ->paginate(10);

        return view('user', compact('users', 'totalUser', 'userAktif', 'userNonAktif'));
    }

    public function store(Request $request) {
        $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role'     => 'required|string',
        ]);


        User::create([
            'name'     => $request->name,
            'username' => $request->username,
            'email'    => $request->username . '@parkir.com',
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);

        return redirect()->route('user.index')->with('success', 'User baru berhasil ditambahkan!');
    }

    public function update(Request $request, $id) {

        $user = User::findOrFail($id);

        $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $id,
            'role'     => 'required|string',
        ]);

        $data = [
            'name'     => $request->name,
            'username' => $request->username,
            'role'     => $request->role,
        ];

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:6']);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('user.index')->with('success', 'Data user berhasil diperbarui!');
    }

    public function destroy($id) {

        $user = User::findOrFail($id);

        if (auth()->user()->id == $user->id) {
            return redirect()->route('user.index')->with('error', 'Anda tidak dapat menghapus data akun anda sendiri yang sedang digunakan!');
        }

        $user->delete();

        return redirect()->route('user.index')->with('success', 'User berhasil dihapus!');
    }
}
