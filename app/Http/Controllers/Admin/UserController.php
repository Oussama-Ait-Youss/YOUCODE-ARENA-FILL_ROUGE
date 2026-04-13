<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        
        $users = User::with('roles')->where('id', '!=', auth()->id())->get();
        return view('admin.users.index', compact('users'));
    }

    public function changeRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|string|in:Compétiteur,Organisateur,Jury'
        ]);

        $user->syncRoles([$request->role]); 
        
        
        return redirect()->back()->with('success', "Le rôle de {$user->username} a été mis à jour avec succès !");
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->back()->with('success', "L'utilisateur a été banni et supprimé de la plateforme.");
    }
}