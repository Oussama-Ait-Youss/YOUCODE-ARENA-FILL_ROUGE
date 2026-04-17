@extends('admin.layout')

@section('title', 'Créer un tournoi')
@section('eyebrow', 'Administration')
@section('page-title', 'Créer un tournoi')
@section('page-description', 'Création centralisée avec assignation obligatoire d’un organisateur.')
@section('active-tab', 'tournaments')

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.tournaments.index') }}" class="text-sm font-bold text-cyan hover:text-white transition">← Retour aux tournois</a>
    </div>

    <section class="glass-card p-6">
        <form action="{{ route('admin.tournaments.store') }}" method="POST">
            @csrf
            @include('admin.tournaments._form')
        </form>
    </section>
@endsection
