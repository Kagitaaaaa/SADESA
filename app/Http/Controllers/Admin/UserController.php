<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Warga;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('warga')->latest()->paginate(15);
        return view('admin.kependudukan.user.index', compact('users'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.kependudukan.user.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        // Update user role / status
    }

    public function resetPassword(Request $request, $id)
    {
        // Reset password user
    }

    public function destroy($id)
    {
        // Hapus akun user
    }
}
