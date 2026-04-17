<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Tournament;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegistrationController extends Controller
{
    public function accept(Request $request, Tournament $tournament, Registration $registration)
    {
        if (!$this->canManageTournament($tournament) || $registration->tournament_id !== $tournament->id) {
            abort(403, 'Action non autorisée.');
        }

        $registration->update(['status' => 'Confirmé']);

        return redirect()->back()->with('success', 'Participant accepté avec succès.');
    }

    public function reject(Request $request, Tournament $tournament, Registration $registration)
    {
        if (!$this->canManageTournament($tournament) || $registration->tournament_id !== $tournament->id) {
            abort(403, 'Action non autorisée.');
        }

        $registration->update(['status' => 'Refusé']);

        return redirect()->back()->with('success', 'Participant refusé.');
    }

    private function canManageTournament(Tournament $tournament): bool
    {
        return auth()->user()->hasRole('Admin') || $tournament->organizer_id === Auth::id();
    }
}
