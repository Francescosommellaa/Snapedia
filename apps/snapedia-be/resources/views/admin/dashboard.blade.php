@extends('layouts.admin')

@section('content')
<h1 class="text-xl font-bold mb-4">Dashboard Admin</h1>

<div class="grid grid-cols-3 gap-4">
    <div class="bg-white p-4 shadow rounded">
        <p class="text-gray-600">Utenti totali</p>
        <h2 class="text-2xl">{{ $totalUsers }}</h2>
    </div>
    <div class="bg-white p-4 shadow rounded">
        <p class="text-gray-600">Attivi</p>
        <h2 class="text-2xl">{{ $activeUsers }}</h2>
    </div>
    <div class="bg-white p-4 shadow rounded">
        <p class="text-gray-600">SnapReaders</p>
        <h2 class="text-2xl">{{ $snapReaders }}</h2>
    </div>
    <div class="bg-white p-4 shadow rounded">
        <p class="text-gray-600">SnapWriters</p>
        <h2 class="text-2xl">{{ $snapWriters }}</h2>
    </div>
    <div class="bg-white p-4 shadow rounded">
        <p class="text-gray-600">Articoli SnapWriters</p>
        <h2 class="text-2xl">{{ $writerArticles }}</h2>
    </div>
    <div class="bg-white p-4 shadow rounded">
        <p class="text-gray-600">Wikipedia salvati</p>
        <h2 class="text-2xl">{{ $storedWikipedia }}</h2>
    </div>
</div>

{{-- Writers idonei al payout --}}
<h2 class="text-xl font-semibold mt-8 mb-2">SnapWriters idonei al pagamento</h2>
<ul class="list-disc pl-6">
    @foreach ($writersReadyForPayout as $writer)
        <li>{{ $writer->name }} (ID: {{ $writer->id }})</li>
    @endforeach
</ul>

{{-- Test SnapWriter --}}
<h2 class="text-xl font-semibold mt-8 mb-2">Test SnapWriter</h2>
<table class="w-full">
    <thead>
        <tr>
            <th>Utente</th><th>Stato</th><th>Data</th><th>Azioni</th>
        </tr>
    </thead>
    <tbody>
    @foreach ($tests as $test)
        <tr>
            <td>{{ $test->user->name }}</td>
            <td>{{ $test->status }}</td>
            <td>{{ $test->submitted_at }}</td>
            <td>
                <form action="{{ route('admin.approve', $test->id) }}" method="POST">
                    @csrf <button class="bg-green-500 text-white px-2">Approva</button>
                </form>
                <form action="{{ route('admin.reject', $test->id) }}" method="POST">
                    @csrf <button class="bg-red-500 text-white px-2">Rifiuta</button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
@endsection
