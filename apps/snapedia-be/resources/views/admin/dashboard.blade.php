
@extends('layouts.admin')

@section('content')
    <div class="p-8">
        <h1 class="text-2xl font-bold mb-4">👋 Benvenuto nella Dashboard Admin</h1>
        <p class="mb-6">Hai effettuato l'accesso come <strong>{{ auth()->user()->username }}</strong>.</p>

        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700" type="submit">
                Logout
            </button>
        </form>
    </div>
@endsection