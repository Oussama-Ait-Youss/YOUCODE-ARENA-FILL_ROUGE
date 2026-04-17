@extends('admin.layout')

@section('title', 'Modifier le tournoi')
@section('eyebrow', 'Administration')
@section('page-title', 'Modifier le tournoi')
@section('page-description', 'Modération complète du tournoi, de l’organisateur, du statut et de la capacité.')
@section('active-tab', 'tournaments')

@section('content')
    <div class="mb-6 flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
        <a href="{{ route('admin.tournaments.index') }}" class="text-sm font-bold text-cyan hover:text-white transition">← Retour aux tournois</a>
        <div class="text-sm text-gray-500">
            Tournoi #{{ $tournament->id }} • créé le {{ $tournament->created_at->format('d/m/Y H:i') }}
        </div>
    </div>

    <section class="glass-card p-6">
        <form action="{{ route('admin.tournaments.update', $tournament) }}" method="POST">
            @csrf
            @method('PUT')
            @include('admin.tournaments._form')
        </form>
    </section>
@endsection
