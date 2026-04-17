@php
    $editing = isset($tournament);
    $submitLabel = $editing ? 'Enregistrer les modifications' : 'Créer le tournoi';
@endphp

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div>
        <label class="block text-xs uppercase tracking-[0.25em] text-gray-500 mb-2">Titre</label>
        <input
            type="text"
            name="title"
            value="{{ old('title', $tournament->title ?? '') }}"
            required
            class="w-full rounded-xl bg-black/40 border border-white/10 px-4 py-3 text-white"
            placeholder="Ex: YouCode Spring Cup"
        >
    </div>

    <div>
        <label class="block text-xs uppercase tracking-[0.25em] text-gray-500 mb-2">Organisateur assigné</label>
        <select name="organizer_id" required class="w-full rounded-xl bg-black/40 border border-white/10 px-4 py-3 text-white">
            <option value="">Sélectionner un organisateur</option>
            @foreach($organizers as $organizer)
                <option value="{{ $organizer->id }}" @selected((int) old('organizer_id', $tournament->organizer_id ?? 0) === $organizer->id)>
                    {{ $organizer->username }} • {{ $organizer->primaryRoleName() }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-xs uppercase tracking-[0.25em] text-gray-500 mb-2">Jeu</label>
        <select name="game_id" required class="w-full rounded-xl bg-black/40 border border-white/10 px-4 py-3 text-white">
            <option value="">Sélectionner</option>
            @foreach($games as $game)
                <option value="{{ $game->id }}" @selected((int) old('game_id', $tournament->game_id ?? 0) === $game->id)>{{ $game->name }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-xs uppercase tracking-[0.25em] text-gray-500 mb-2">Catégorie</label>
        <select name="category_id" required class="w-full rounded-xl bg-black/40 border border-white/10 px-4 py-3 text-white">
            <option value="">Sélectionner</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" @selected((int) old('category_id', $tournament->category_id ?? 0) === $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-xs uppercase tracking-[0.25em] text-gray-500 mb-2">Statut</label>
        <select name="status" required class="w-full rounded-xl bg-black/40 border border-white/10 px-4 py-3 text-white">
            @foreach($statusOptions as $option)
                <option value="{{ $option }}" @selected(old('status', $tournament->status ?? 'À venir') === $option)>{{ $option }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-xs uppercase tracking-[0.25em] text-gray-500 mb-2">Capacité maximale</label>
        <input
            type="number"
            name="max_capacity"
            min="2"
            value="{{ old('max_capacity', $tournament->max_capacity ?? 2) }}"
            required
            class="w-full rounded-xl bg-black/40 border border-white/10 px-4 py-3 text-white"
        >
    </div>

    <div class="lg:col-span-2">
        <label class="block text-xs uppercase tracking-[0.25em] text-gray-500 mb-2">Date et heure</label>
        <input
            type="datetime-local"
            name="event_date"
            value="{{ old('event_date', isset($tournament) ? $tournament->event_date->format('Y-m-d\\TH:i') : '') }}"
            required
            class="w-full rounded-xl bg-black/40 border border-white/10 px-4 py-3 text-white"
        >
    </div>
</div>

<div class="mt-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <p class="text-sm text-gray-500">
        Les tournois ouverts sont limités à un seul par organisateur pour respecter les règles métier de la plateforme.
    </p>
    <button type="submit" class="rounded-xl bg-cyan hover:bg-[#00d7e6] text-black px-6 py-3 font-bold transition">
        {{ $submitLabel }}
    </button>
</div>
