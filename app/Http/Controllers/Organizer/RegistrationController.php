<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Tournament;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RegistrationController extends Controller
{
    public function accept(Request $request, Tournament $tournament, Registration $registration)
    {
        if (!$this->canManageTournament($tournament) || $registration->tournament_id !== $tournament->id) {
            abort(403, 'Action non autorisée.');
        }

        if ($registration->status === 'Confirmé') {
            return redirect()->back()->with('success', 'Participant déjà confirmé.');
        }

        if ($tournament->is_full) {
            return redirect()->back()->with('error', 'Capacité atteinte : impossible de confirmer plus de joueurs.');
        }

        if (
            $registration->team_id &&
            !DB::table('team_members')
                ->where('team_id', $registration->team_id)
                ->where('user_id', $registration->user_id)
                ->exists()
        ) {
            return redirect()->back()->with('error', 'Ce joueur doit accepter son invitation avant validation organisateur.');
        }

        $registration->update(['status' => 'Confirmé']);

        return redirect()->back()->with('success', 'Participant accepté avec succès.');
    }

    public function reject(Request $request, Tournament $tournament, Registration $registration)
    {
        if (!$this->canManageTournament($tournament) || $registration->tournament_id !== $tournament->id) {
            abort(403, 'Action non autorisée.');
        }

        DB::transaction(function () use ($registration) {
            if ($registration->team_id) {
                DB::table('team_members')
                    ->where('team_id', $registration->team_id)
                    ->where('user_id', $registration->user_id)
                    ->delete();
            }

            $registration->update(['status' => 'Refusé']);
        });

        return redirect()->back()->with('success', 'Participant refusé.');
    }

    private function canManageTournament(Tournament $tournament): bool
    {
        return auth()->user()->hasRole('Admin') || $tournament->organizer_id === Auth::id();
    }
}
