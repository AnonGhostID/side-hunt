@extends('layouts.management')

@section('title', 'Panel Bantuan dan Laporan Penipuan')
@section('page-title', 'Panel Bantuan dan Laporan Penipuan')

@section('content')
<div class="container mx-auto px-4 py-8" x-data="{ 
    activeTab: @if($user->isAdmin()) 'all' @else 'history_all' @endif,
    selectedTicket: null,
    messages: [],
    newMessage: '',
    isLoadingMessages: false,
    refreshInterval: null
}">

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-100 text-red-800 rounded">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-100 text-red-800 rounded">
            <strong class="font-bold">Terdapat kesalahan:</strong>
            <ul class="mt-2 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($user->isAdmin())
        <!-- Admin View -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Ticket List Panel -->
            <div class="lg:col-span-1 bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-semibold mb-4">Daftar Tiket</h2>
                
                <!-- Filter Tabs -->
                <div class="mb-4 border-b border-gray-200">
                    <nav class="-mb-px flex space-x-4">
                        <button @click="activeTab = 'all'" 
                                :class="activeTab === 'all' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                                class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-xs">
                            Semua
                        </button>
                        <button @click="activeTab = 'bantuan'" 
                                :class="activeTab === 'bantuan' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                                class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-xs">
                            Bantuan
                        </button>
                        <button @click="activeTab = 'penipuan'" 
                                :class="activeTab === 'penipuan' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                                class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-xs">
                            Penipuan
                        </button>
                    </nav>
                </div>

                <!-- Ticket List -->
                <div class="space-y-3 max-h-96 overflow-y-auto">
                    @foreach($tickets as $ticket)
                        <div x-show="activeTab === 'all' || activeTab === '{{ $ticket->type }}'" 
                             @click="selectedTicket = {{ $ticket->id }}; loadMessages({{ $ticket->id }}, $data)"
                             :class="selectedTicket === {{ $ticket->id }} ? 'bg-blue-50 border-blue-200' : 'bg-gray-50 border-gray-200'"
                             class="p-3 border rounded-lg cursor-pointer hover:bg-blue-50 transition-colors">
                            
                            <div class="flex items-center justify-between mb-2">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                    {{ $ticket->type === 'penipuan' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ $ticket->type === 'penipuan' ? 'Penipuan' : 'Bantuan' }}
                                </span>
                                
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                    {{ $ticket->status === 'open' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($ticket->status) }}
                                </span>
                            </div>
                            
                            <h4 class="font-medium text-sm text-gray-900 mb-1">{{ Str::limit($ticket->subject, 30) }}</h4>
                            <p class="text-xs text-gray-600 mb-2">{{ $ticket->user->nama }}</p>
                            
                            @if($ticket->latestMessage)
                                <p class="text-xs text-gray-500">
                                    <strong>{{ $ticket->latestMessage->sender->nama }}:</strong> 
                                    {{ Str::limit($ticket->latestMessage->message, 40) }}
                                </p>
                                <p class="text-xs text-gray-400 mt-1">{{ $ticket->latestMessage->created_at->diffForHumans() }}</p>
                            @else
                                <p class="text-xs text-gray-500 italic">Belum ada percakapan</p>
                            @endif

                            @if($ticket->hasUnreadForAdmin())
                                <div class="inline-block w-2 h-2 bg-red-500 rounded-full mt-1"></div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Conversation Panel -->
            <div class="lg:col-span-2 bg-white rounded-lg shadow">
                <div x-show="!selectedTicket" class="p-6 text-center text-gray-500">
                    <i class="fas fa-comments fa-3x mb-4"></i>
                    <p>Pilih tiket untuk memulai percakapan</p>
                </div>

                <div x-show="selectedTicket" class="flex flex-col h-96">
                    <!-- Conversation Header -->
                    <div class="p-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-semibold text-gray-900" x-text="getSelectedTicketSubject(selectedTicket)"></h3>
                                <p class="text-sm text-gray-600" x-text="getSelectedTicketUser(selectedTicket)"></p>
                            </div>
                            <div class="flex space-x-2">
                                <button @click="closeTicket(selectedTicket)" 
                                        x-show="getSelectedTicketStatus(selectedTicket) === 'open'"
                                        class="px-3 py-1 bg-red-500 text-white text-sm rounded hover:bg-red-600">
                                    Tutup Tiket
                                </button>
                                <button @click="reopenTicket(selectedTicket)" 
                                        x-show="getSelectedTicketStatus(selectedTicket) === 'closed'"
                                        class="px-3 py-1 bg-green-500 text-white text-sm rounded hover:bg-green-600">
                                    Buka Kembali
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Messages Area -->
                    <div class="flex-1 p-4 overflow-y-auto" id="messagesContainer">
                        <div x-show="isLoadingMessages" class="text-center py-4">
                            <i class="fas fa-spinner fa-spin"></i> Memuat percakapan...
                        </div>
                        
                        <template x-for="message in messages" :key="message.id">
                            <div class="mb-4" :class="message.sender_type === 'admin' ? 'text-right' : 'text-left'">
                                <div class="inline-block max-w-xs lg:max-w-md px-4 py-2 rounded-lg"
                                     :class="message.sender_type === 'admin' 
                                        ? 'bg-blue-500 text-white' 
                                        : 'bg-gray-200 text-gray-800'">
                                    <p class="text-sm" x-text="message.message"></p>
                                    <p class="text-xs mt-1 opacity-75" 
                                       x-text="new Date(message.created_at).toLocaleString('id-ID')"></p>
                                </div>
                                <p class="text-xs text-gray-500 mt-1" x-text="message.sender.nama"></p>
                            </div>
                        </template>
                    </div>

                    <!-- Message Input -->
                    <div class="p-4 border-t border-gray-200" x-show="getSelectedTicketStatus(selectedTicket) === 'open'">
                        <div class="flex space-x-2">
                            <input type="text" 
                                   x-model="newMessage" 
                                   @keydown.enter="sendMessage(newMessage, selectedTicket, $data)"
                                   placeholder="Ketik pesan..."
                                   class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm">
                            <button @click="sendMessage(newMessage, selectedTicket, $data)" 
                                    :disabled="!newMessage.trim()"
                                    class="px-4 py-2 bg-blue-500 text-white text-sm rounded-lg hover:bg-blue-600 disabled:opacity-50">
                                Kirim
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- User View -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Create New Ticket Panel -->
            <div class="lg:col-span-1 bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-semibold mb-4">Buat Tiket Baru</h2>
                
                <!-- Tab Navigation -->
                <div class="mb-4 border-b border-gray-200">
                    <nav class="-mb-px flex space-x-4">
                        <button @click="activeTab = 'bantuan'" 
                                :class="activeTab === 'bantuan' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500'"
                                class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-xs">
                            Bantuan
                        </button>
                        <button @click="activeTab = 'penipuan'" 
                                :class="activeTab === 'penipuan' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500'"
                                class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-xs">
                            Penipuan
                        </button>
                    </nav>
                </div>

                <!-- Bantuan Form -->
                <div x-show="activeTab === 'bantuan'">
                    <form method="POST" action="{{ route('manajemen.bantuan.store') }}">
                        @csrf
                        <input type="hidden" name="type" value="bantuan">
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                            <input name="subject" type="text" required class="w-full border border-gray-300 rounded-lg p-2 text-sm">
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                            <textarea name="description" rows="3" required class="w-full border border-gray-300 rounded-lg p-2 text-sm"></textarea>
                        </div>
                        <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm">
                            Buat Tiket
                        </button>
                    </form>
                </div>

                <!-- Penipuan Form -->
                <div x-show="activeTab === 'penipuan'">
                    <form method="POST" action="{{ route('manajemen.bantuan.store') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="type" value="penipuan">
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Judul Laporan</label>
                            <input name="subject" type="text" required class="w-full border border-gray-300 rounded-lg p-2 text-sm">
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pihak Terlapor</label>
                            <input name="pihak_terlapor" type="text" required class="w-full border border-gray-300 rounded-lg p-2 text-sm">
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                            <textarea name="description" rows="3" required class="w-full border border-gray-300 rounded-lg p-2 text-sm"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Kejadian</label>
                            <input name="tanggal_kejadian" type="date" required class="w-full border border-gray-300 rounded-lg p-2 text-sm">
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Bukti (Opsional)</label>
                            <input name="bukti_pendukung[]" type="file" multiple class="w-full text-sm">
                        </div>
                        <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm">
                            Buat Laporan
                        </button>
                    </form>
                </div>
            </div>

            <!-- My Tickets & Conversation Panel -->
            <div class="lg:col-span-2">
                <!-- Ticket History Tabs -->
                <div class="bg-white p-4 rounded-lg shadow mb-4">
                    <div class="border-b border-gray-200 mb-4">
                        <nav class="-mb-px flex space-x-8">
                            <button @click="activeTab = 'history_all'" 
                                    :class="activeTab === 'history_all' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500'"
                                    class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                                Semua Tiket Saya
                            </button>
                        </nav>
                    </div>

                    <!-- My Tickets List -->
                    <div class="grid grid-cols-1 gap-3 max-h-64 overflow-y-auto">
                        @foreach($tickets as $ticket)
                            <div @click="selectedTicket = {{ $ticket->id }}; loadMessages({{ $ticket->id }}, $data)"
                                 :class="selectedTicket === {{ $ticket->id }} ? 'bg-blue-50 border-blue-200' : 'bg-gray-50 border-gray-200'"
                                 class="p-3 border rounded-lg cursor-pointer hover:bg-blue-50 transition-colors">
                                
                                <div class="flex items-center justify-between mb-2">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                        {{ $ticket->type === 'penipuan' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }}">
                                        {{ $ticket->type === 'penipuan' ? 'Penipuan' : 'Bantuan' }}
                                    </span>
                                    
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                        {{ $ticket->status === 'open' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($ticket->status) }}
                                    </span>
                                </div>
                                
                                <h4 class="font-medium text-sm text-gray-900 mb-1">{{ $ticket->subject }}</h4>
                                
                                @if($ticket->latestMessage)
                                    <p class="text-xs text-gray-500">
                                        <strong>{{ $ticket->latestMessage->sender->nama }}:</strong> 
                                        {{ Str::limit($ticket->latestMessage->message, 50) }}
                                    </p>
                                    <p class="text-xs text-gray-400 mt-1">{{ $ticket->latestMessage->created_at->diffForHumans() }}</p>
                                @endif

                                @if($ticket->hasUnreadForUser())
                                    <div class="inline-block w-2 h-2 bg-red-500 rounded-full mt-1"></div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Conversation Panel (Same as Admin) -->
                <div class="bg-white rounded-lg shadow">
                    <div x-show="!selectedTicket" class="p-6 text-center text-gray-500">
                        <i class="fas fa-comments fa-3x mb-4"></i>
                        <p>Pilih tiket untuk melihat percakapan</p>
                    </div>

                    <div x-show="selectedTicket" class="flex flex-col h-96">
                        <!-- Conversation Header -->
                        <div class="p-4 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="font-semibold text-gray-900" x-text="getSelectedTicketSubject(selectedTicket)"></h3>
                                </div>
                                <div>
                                    <button @click="reopenTicket(selectedTicket)" 
                                            x-show="getSelectedTicketStatus(selectedTicket) === 'closed'"
                                            class="px-3 py-1 bg-green-500 text-white text-sm rounded hover:bg-green-600">
                                        Buka Kembali
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Messages Area (Same as Admin) -->
                        <div class="flex-1 p-4 overflow-y-auto" id="messagesContainer">
                            <div x-show="isLoadingMessages" class="text-center py-4">
                                <i class="fas fa-spinner fa-spin"></i> Memuat percakapan...
                            </div>
                            
                            <template x-for="message in messages" :key="message.id">
                                <div class="mb-4" :class="message.sender_type === 'user' ? 'text-right' : 'text-left'">
                                    <div class="inline-block max-w-xs lg:max-w-md px-4 py-2 rounded-lg"
                                         :class="message.sender_type === 'user' 
                                            ? 'bg-blue-500 text-white' 
                                            : 'bg-gray-200 text-gray-800'">
                                        <p class="text-sm" x-text="message.message"></p>
                                        <p class="text-xs mt-1 opacity-75" 
                                           x-text="new Date(message.created_at).toLocaleString('id-ID')"></p>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1" x-text="message.sender.nama"></p>
                                </div>
                            </template>
                        </div>

                        <!-- Message Input -->
                        <div class="p-4 border-t border-gray-200" x-show="getSelectedTicketStatus(selectedTicket) === 'open'">
                            <div class="flex space-x-2">
                                <input type="text" 
                                       x-model="newMessage" 
                                       @keydown.enter="sendMessage(newMessage, selectedTicket, $data)"
                                       placeholder="Ketik pesan..."
                                       class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm">
                                <button @click="sendMessage(newMessage, selectedTicket, $data)" 
                                        :disabled="!newMessage.trim()"
                                        class="px-4 py-2 bg-blue-500 text-white text-sm rounded-lg hover:bg-blue-600 disabled:opacity-50">
                                    Kirim
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Store ticket data globally
    window.ticketsData = @json($tickets);
    
    console.log('Tickets data loaded:', window.ticketsData);
});

function getSelectedTicketSubject(selectedTicket) {
    const ticket = window.ticketsData.find(t => t.id === selectedTicket);
    return ticket ? ticket.subject : '';
}

function getSelectedTicketUser(selectedTicket) {
    const ticket = window.ticketsData.find(t => t.id === selectedTicket);
    return ticket ? ticket.user.nama : '';
}

function getSelectedTicketStatus(selectedTicket) {
    const ticket = window.ticketsData.find(t => t.id === selectedTicket);
    return ticket ? ticket.status : '';
}

async function loadMessages(ticketId, alpineInstance) {
    console.log('Loading messages for ticket:', ticketId);
    alpineInstance.isLoadingMessages = true;
    
    try {
        const response = await fetch(`/management/ticket/${ticketId}/messages`);
        console.log('Response status:', response.status);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        console.log('Messages loaded:', data);
        
        alpineInstance.messages = data.messages;
        
        // Scroll to bottom after messages load
        setTimeout(() => {
            const container = document.getElementById('messagesContainer');
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        }, 100);
        
    } catch (error) {
        console.error('Error loading messages:', error);
        alert('Gagal memuat percakapan: ' + error.message);
    } finally {
        alpineInstance.isLoadingMessages = false;
    }
}

async function sendMessage(message, selectedTicket, alpineInstance) {
    if (!message.trim() || !selectedTicket) return;
    
    try {
        const response = await fetch(`/management/ticket/${selectedTicket}/message`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                message: message
            })
        });
        
        if (response.ok) {
            alpineInstance.newMessage = '';
            await loadMessages(selectedTicket, alpineInstance);
        } else {
            const errorData = await response.text();
            console.error('Send message error:', errorData);
            alert('Gagal mengirim pesan');
        }
    } catch (error) {
        console.error('Error sending message:', error);
        alert('Gagal mengirim pesan');
    }
}

async function closeTicket(selectedTicket) {
    if (!selectedTicket) return;
    
    const closingMessage = prompt('Pesan penutupan (opsional):');
    if (closingMessage === null) return; // User cancelled
    
    try {
        const response = await fetch(`/management/ticket/${selectedTicket}/close`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                closing_message: closingMessage
            })
        });
        
        if (response.ok) {
            location.reload();
        } else {
            alert('Gagal menutup tiket');
        }
    } catch (error) {
        console.error('Error closing ticket:', error);
        alert('Gagal menutup tiket');
    }
}

async function reopenTicket(selectedTicket) {
    if (!selectedTicket) return;
    
    try {
        const response = await fetch(`/management/ticket/${selectedTicket}/reopen`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        if (response.ok) {
            location.reload();
        } else {
            alert('Gagal membuka kembali tiket');
        }
    } catch (error) {
        console.error('Error reopening ticket:', error);
        alert('Gagal membuka kembali tiket');
    }
}
</script>
@endpush