@extends('layouts.app')

@section('title', 'Users List')

@section('content')
<div class="mb-8 flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
    <div>
        <p class="mb-2 text-xs font-bold uppercase tracking-[0.2em] text-indigo-300">People directory</p>
        <h1 class="text-3xl font-semibold tracking-tight text-white sm:text-4xl">Users and hobbies</h1>
        <p class="mt-2 max-w-xl text-sm text-slate-400">Keep your community organized and every personal interest within reach.</p>
    </div>
    <a href="{{ route('users.create') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-500 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-500/25 transition hover:-translate-y-0.5 hover:bg-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:ring-offset-2 focus:ring-offset-slate-950">
        <span class="text-lg leading-none">+</span> Add user
    </a>
</div>

<div class="overflow-hidden rounded-3xl border border-white/10 bg-white/[0.06] shadow-2xl shadow-black/20 backdrop-blur-xl">
    <div class="flex items-center justify-between border-b border-white/10 px-5 py-4 sm:px-6">
        <div>
            <h2 class="font-semibold text-white">All users</h2>
            <p class="mt-1 text-xs text-slate-400">{{ $users->count() }} {{ Str::plural('profile', $users->count()) }} in your directory</p>
        </div>
        <span class="rounded-full bg-indigo-400/10 px-3 py-1 text-xs font-semibold text-indigo-200">Live list</span>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-white/10">
            <thead class="bg-white/[0.03]">
                <tr>
                    <th class="w-16 px-5 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 sm:px-6">#</th>
                    <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Name</th>
                    <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Email</th>
                    <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Hobbies</th>
                    <th class="px-5 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-500 sm:px-6">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/10">
            @forelse ($users as $user)
                <tr class="transition hover:bg-white/[0.04]">
                    <td class="px-5 py-5 text-sm text-slate-500 sm:px-6">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</td>
                    <td class="whitespace-nowrap px-5 py-5 sm:px-6">
                        <div class="flex items-center gap-3">
                            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-400/15 text-sm font-bold text-indigo-200">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                            <span class="font-semibold text-white">{{ $user->name }}</span>
                        </div>
                    </td>
                    <td class="whitespace-nowrap px-5 py-5 text-sm text-slate-400 sm:px-6">{{ $user->email }}</td>
                    <td class="min-w-48 px-5 py-5 sm:px-6">
                        <div class="flex flex-wrap gap-2">
                        @forelse ($user->hobbies as $hobby)
                            <span class="rounded-lg border border-indigo-300/15 bg-indigo-300/10 px-2.5 py-1 text-xs font-medium text-indigo-200">{{ $hobby->name }}</span>
                        @empty
                            <span class="text-sm italic text-slate-500">No hobbies added</span>
                        @endforelse
                        </div>
                    </td>
                    <td class="whitespace-nowrap px-5 py-5 text-right sm:px-6">
                        <a href="{{ route('users.edit', $user) }}" class="mr-3 text-sm font-semibold text-indigo-300 transition hover:text-indigo-200">Edit</a>
                        <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline"
                              onsubmit="return confirm('Hapus user ini beserta hobinya?')">
                            @csrf
                            @method('DELETE')
                            <button class="text-sm font-semibold text-rose-300 transition hover:text-rose-200">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-6 py-16 text-center text-sm text-slate-400">No users found yet. Add your first profile to get started.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection 