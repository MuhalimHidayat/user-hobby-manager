@extends('layouts.app')

@php
    $isEdit = isset($user);
    $hobbies = old('hobbies', $isEdit ? $user->hobbies->pluck('name')->all() : ['']);
    
    if (empty($hobbies)) { 
        $hobbies = ['']; 
    }
@endphp

@section('title', $isEdit ? 'Edit User' : 'Add User')

@section('content')
<div class="mx-auto max-w-3xl">
    <div class="mb-8">
        <a href="{{ route('users.index') }}" class="mb-5 inline-flex items-center gap-2 text-sm font-medium text-slate-400 transition hover:text-white">&larr; Back to directory</a>
        <p class="mb-2 text-xs font-bold uppercase tracking-[0.2em] text-indigo-300">{{ $isEdit ? 'Profile settings' : 'New profile' }}</p>
        <h1 class="text-3xl font-semibold tracking-tight text-white">{{ $isEdit ? 'Edit user details' : 'Create a user' }}</h1>
        <p class="mt-2 text-sm text-slate-400">{{ $isEdit ? 'Keep this profile current and capture what they enjoy.' : 'Add a person and the hobbies that make them unique.' }}</p>
    </div>

    <div class="rounded-3xl border border-white/10 bg-white/6 p-5 shadow-2xl shadow-black/20 backdrop-blur-xl sm:p-8">

        @if ($errors->any())
            <div class="mb-7 rounded-2xl border border-rose-400/20 bg-rose-400/10 p-4 text-sm text-rose-200">
                <p class="mb-2 font-semibold text-rose-100">Please check the following:</p>
                <ul class="list-disc space-y-1 pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ $isEdit ? route('users.update', $user) : route('users.store') }}" method="POST">
            @csrf
            @if ($isEdit) @method('PUT') @endif

            <div class="mb-6">
                <label for="name" class="mb-2 block text-sm font-semibold text-slate-200">Name</label>
                <input id="name" type="text" name="name" class="block w-full rounded-xl border border-white/10 bg-slate-950/50 px-4 py-3 text-sm text-white outline-none transition placeholder:text-slate-600 focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400/20"
                       value="{{ old('name', $user->name ?? '') }}" required>
            </div>

            <div class="mb-6">
                <label for="email" class="mb-2 block text-sm font-semibold text-slate-200">Email address</label>
                <input id="email" type="email" name="email" class="block w-full rounded-xl border border-white/10 bg-slate-950/50 px-4 py-3 text-sm text-white outline-none transition placeholder:text-slate-600 focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400/20"
                       value="{{ old('email', $user->email ?? '') }}" required>
            </div>

            <div class="mb-6">
                <label for="password" class="mb-2 block text-sm font-semibold text-slate-200">
                    Password @if ($isEdit)<span class="font-normal text-slate-500">(leave blank to keep current)</span>@endif
                </label>
                <input id="password" type="password" name="password" class="block w-full rounded-xl border border-white/10 bg-slate-950/50 px-4 py-3 text-sm text-white outline-none transition placeholder:text-slate-600 focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400/20" {{ $isEdit ? '' : 'required' }}>
            </div>

            <div class="mb-8">
                <div class="mb-3 flex items-end justify-between gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-200">Hobbies</label>
                        <p class="mt-1 text-xs text-slate-500">Add one or more interests.</p>
                    </div>
                    <button type="button" id="add-hobby" class="rounded-lg border border-indigo-300/20 bg-indigo-300/10 px-3 py-2 text-xs font-semibold text-indigo-200 transition hover:bg-indigo-300/20">+ Add hobby</button>
                </div>
                <div id="hobby-list">
                    @foreach ($hobbies as $hobby)
                        <div class="hobby-row mb-3 flex gap-2">
                            <input type="text" name="hobbies[]" class="block min-w-0 flex-1 rounded-xl border border-white/10 bg-slate-950/50 px-4 py-3 text-sm text-white outline-none transition placeholder:text-slate-600 focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400/20"
                                   placeholder="e.g. Photography" value="{{ $hobby }}">
                            <button type="button" class="remove-hobby rounded-xl border border-rose-300/15 px-3 text-xs font-semibold text-rose-300 transition hover:bg-rose-300/10">Remove</button>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="flex flex-col-reverse gap-3 border-t border-white/10 pt-6 sm:flex-row sm:justify-end">
                <a href="{{ route('users.index') }}" class="inline-flex items-center justify-center rounded-xl border border-white/10 px-5 py-3 text-sm font-semibold text-slate-300 transition hover:bg-white/5 hover:text-white">Cancel</a>
                <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-indigo-500 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-500/25 transition hover:-translate-y-0.5 hover:bg-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:ring-offset-2 focus:ring-offset-slate-950">{{ $isEdit ? 'Save changes' : 'Create profile' }}</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const list = document.getElementById('hobby-list');

    function rowTemplate() {
        const div = document.createElement('div');
        div.className = 'hobby-row mb-3 flex gap-2';
        div.innerHTML = `
            <input type="text" name="hobbies[]" class="block min-w-0 flex-1 rounded-xl border border-white/10 bg-slate-950/50 px-4 py-3 text-sm text-white outline-none transition placeholder:text-slate-600 focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400/20" placeholder="e.g. Photography">
            <button type="button" class="remove-hobby rounded-xl border border-rose-300/15 px-3 text-xs font-semibold text-rose-300 transition hover:bg-rose-300/10">Remove</button>`;
        return div;
    }

    document.getElementById('add-hobby').addEventListener('click', () => {
        list.appendChild(rowTemplate());
    });

    list.addEventListener('click', (e) => {
        if (!e.target.classList.contains('remove-hobby')) return;
        e.target.closest('.hobby-row').remove();
        if (list.children.length === 0) list.appendChild(rowTemplate());
    });
</script>
@endpush