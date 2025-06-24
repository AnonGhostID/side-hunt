@extends('layouts.management')

@section('title', 'Panel Bantuan')
@section('page-title', 'Panel Bantuan')

@section('content')
<div class="container mx-auto space-y-6">
    @if(auth()->user()->isAdmin())
        <h2 class="text-lg font-semibold">Daftar Ticket Bantuan</h2>
        <div class="bg-white rounded shadow">
            <ul>
                @forelse($tickets as $ticket)
                    <li class="p-4 border-b border-gray-200">
                        <div class="font-semibold">{{ $ticket->subject }} <span class="text-sm text-gray-500">({{ $ticket->status }})</span></div>
                        <p class="text-sm text-gray-700">{{ $ticket->message }}</p>
                        <p class="text-xs text-gray-500 mt-1">oleh {{ $ticket->user->nama }} - {{ $ticket->created_at->format('d/m/Y H:i') }}</p>
                        @if($ticket->response)
                            <div class="mt-2 p-2 bg-green-50 rounded">
                                <p class="font-medium">Respon:</p>
                                <p class="text-sm">{{ $ticket->response }}</p>
                            </div>
                        @endif
                        @if($ticket->status === 'open')
                            <form action="{{ route('support-tickets.respond', $ticket->id) }}" method="POST" class="mt-2 space-y-2">
                                @csrf
                                <textarea name="response" rows="2" class="w-full border rounded p-2" placeholder="Tulis respon..."></textarea>
                                <button type="submit" class="px-3 py-1 bg-blue-600 text-white rounded">Tandai Selesai</button>
                            </form>
                        @endif
                    </li>
                @empty
                    <li class="p-4">Tidak ada tiket.</li>
                @endforelse
            </ul>
        </div>
    @else
        <div class="bg-white p-4 rounded shadow">
            <form action="{{ route('support-tickets.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium mb-1">Subjek</label>
                    <input type="text" name="subject" class="w-full border rounded p-2" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Pesan</label>
                    <textarea name="message" rows="4" class="w-full border rounded p-2" required></textarea>
                </div>
                <button type="submit" class="px-3 py-1 bg-blue-600 text-white rounded">Kirim Ticket</button>
            </form>
        </div>

        <h2 class="text-lg font-semibold mt-6">Tiket Saya</h2>
        <div class="bg-white rounded shadow">
            <ul>
                @forelse($tickets as $ticket)
                    <li class="p-4 border-b border-gray-200">
                        <div class="font-semibold">{{ $ticket->subject }} <span class="text-sm text-gray-500">({{ $ticket->status }})</span></div>
                        <p class="text-sm text-gray-700">{{ $ticket->message }}</p>
                        @if($ticket->response)
                            <div class="mt-2 p-2 bg-green-50 rounded">
                                <p class="font-medium">Respon Admin:</p>
                                <p class="text-sm">{{ $ticket->response }}</p>
                            </div>
                        @endif
                    </li>
                @empty
                    <li class="p-4">Belum ada tiket.</li>
                @endforelse
            </ul>
        </div>
    @endif
</div>
@endsection
