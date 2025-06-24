@extends('layouts.management')

@section('title', 'Panel Bantuan')
@section('page-title', 'Panel Bantuan')

@section('content')
<div class="container mx-auto px-4 py-8">
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(!$user->isAdmin())
        <div class="bg-white p-6 rounded-lg shadow-lg mb-8">
            <h2 class="text-xl font-semibold mb-4">Buat Tiket Bantuan</h2>
            <form method="POST" action="{{ route('manajemen.bantuan.submit') }}">
                @csrf
                <div class="mb-4">
                    <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Subjek</label>
                    <input type="text" id="subject" name="subject" class="w-full border-gray-300 rounded-lg shadow-sm" required>
                </div>
                <div class="mb-4">
                    <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Pesan</label>
                    <textarea id="message" name="message" rows="4" class="w-full border-gray-300 rounded-lg shadow-sm" required></textarea>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-lg shadow">Kirim Tiket</button>
                </div>
            </form>
        </div>
    @endif

    <div class="bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-xl font-semibold mb-4">{{ $user->isAdmin() ? 'Daftar Tiket' : 'Tiket Anda' }}</h2>
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subjek</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi / Respon</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
            @forelse($tickets as $ticket)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $ticket->id }}</td>
                    <td class="px-6 py-4">{{ $ticket->subject }}</td>
                    <td class="px-6 py-4">{{ ucfirst($ticket->status) }}</td>
                    <td class="px-6 py-4">
                        @if($user->isAdmin())
                            @if($ticket->status === 'open')
                                <form method="POST" action="{{ route('manajemen.bantuan.respond', $ticket->id) }}">
                                    @csrf
                                    <textarea name="response" rows="2" class="w-full border-gray-300 rounded-lg shadow-sm mb-2" required></textarea>
                                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white py-1 px-3 rounded">Mark Done</button>
                                </form>
                            @else
                                <div class="text-gray-600">{{ $ticket->response }}</div>
                            @endif
                        @else
                            @if($ticket->response)
                                <div class="text-gray-600">{{ $ticket->response }}</div>
                            @else
                                <span class="text-sm text-gray-400">Belum ada respon</span>
                            @endif
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada tiket.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
