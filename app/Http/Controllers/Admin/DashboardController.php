<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Tournament;
use App\Models\Matchh; 
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalCompetitors = User::whereHas('roles', function($q) {
            $q->where('name', 'Compétiteur');
        })->count();

        $totalTournaments = Tournament::count();
        $activeTournaments = Tournament::where('status', 'Ouvert')->count();

        $totalMatches = Matchh::count();

        return view('admin.dashboard', compact(
            'totalUsers', 
            'totalCompetitors', 
            'totalTournaments', 
            'activeTournaments', 
            'totalMatches'
        ));
    }
}