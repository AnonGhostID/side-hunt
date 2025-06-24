@extends('layouts.management')

@section('title', 'Panel Bantuan')
@section('page-title', 'Panel Bantuan')

@section('content')
<div class="container mx-auto px-4 py-8">

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if($user->isAdmin())
        <div class="bg-white p-6 rounded-lg shadow mb-8">
            <h2 class="text-xl font-semibold mb-4">Daftar Tiket Bantuan</h2>
            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="px-4 py-2 border">Subject</th>
                        <th class="px-4 py-2 border">Pengguna</th>
                        <th class="px-4 py-2 border">Deskripsi</th>
                        <th class="px-4 py-2 border">Status</th>
                        <th class="px-4 py-2 border">Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tickets as $ticket)
                        <tr>
                            <td class="px-4 py-2 border">{{ $ticket->subject }}</td>
                            <td class="px-4 py-2 border">{{ $ticket->user->nama }}</td>
                            <td class="px-4 py-2 border">{{ $ticket->description }}</td>
                            <td class="px-4 py-2 border">{{ ucfirst($ticket->status) }}</td>
                            <td class="px-4 py-2 border">
                                @if($ticket->status === 'open')
                                    <form method="POST" action="{{ route('manajemen.bantuan.respond', $ticket->id) }}">
                                        @csrf
                                        <textarea name="admin_response" required class="w-full border rounded p-2" placeholder="Masukkan respon admin..."></textarea>
                                        <button type="submit" class="mt-2 bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
                                            Tutup Tiket
                                        </button>
                                    </form>
                                @else
                                    <p class="text-gray-700">{{ $ticket->admin_response }}</p>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="bg-white p-6 rounded-lg shadow mb-8">
            <h2 class="text-xl font-semibold mb-4">Buat Tiket Bantuan</h2>
            <form method="POST" action="{{ route('manajemen.bantuan.store') }}">
                @csrf
                <div class="mb-4">
                    <label for="subject" class="block text-gray-700">Subject</label>
                    <input id="subject" name="subject" type="text" required class="w-full border rounded p-2" value="{{ old('subject') }}">
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-gray-700">Deskripsi</label>
                    <textarea id="description" name="description" rows="4" required class="w-full border rounded p-2">{{ old('description') }}</textarea>
                </div>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                    Kirim Tiket
                </button>
            </form>
        </div>

        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-xl font-semibold mb-4">Riwayat Tiket Anda</h2>
            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="px-4 py-2 border">Subject</th>
                        <th class="px-4 py-2 border">Deskripsi</th>
                        <th class="px-4 py-2 border">Status</th>
                        <th class="px-4 py-2 border">Respon Admin</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tickets as $ticket)
                        <tr>
                            <td class="px-4 py-2 border">{{ $ticket->subject }}</td>
                            <td class="px-4 py-2 border">{{ $ticket->description }}</td>
                            <td class="px-4 py-2 border">{{ ucfirst($ticket->status) }}</td>
                            <td class="px-4 py-2 border">
                                @if($ticket->status === 'closed')
                                    {{ $ticket->admin_response }}
                                @else
                                    <span class="text-gray-500">Menunggu respon</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

</div>
@endsection
