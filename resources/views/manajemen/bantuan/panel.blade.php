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
    refreshInterval: null,
    lastMessageCount: 0,
    // Modal states
    isProcessModalOpen: false,
    isCloseModalOpen: false,
    processMessage: '',
    closeMessage: ''
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
                             @click="selectTicket({{ $ticket->id }}, $data)"
                             :class="selectedTicket === {{ $ticket->id }} ? 'bg-blue-50 border-blue-200' : 'bg-gray-50 border-gray-200'"
                             class="p-3 border rounded-lg cursor-pointer hover:bg-blue-50 transition-colors">
                            
                            <div class="flex items-center justify-between mb-2">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                    {{ $ticket->type === 'penipuan' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ $ticket->type === 'penipuan' ? 'Penipuan' : 'Bantuan' }}
                                </span>
                                
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                    @if($ticket->status === 'open') bg-green-100 text-green-800
                                    @elseif($ticket->status === 'diproses') bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    @if($ticket->status === 'open') Aktif
                                    @elseif($ticket->status === 'diproses') Diproses
                                    @else Ditutup @endif
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

                <div x-show="selectedTicket" class="flex flex-col" style="height: 600px;">
                    <!-- Conversation Header -->
                    <div class="bg-white border-b border-gray-200">
                        <!-- Title Row -->
                        <div class="px-6 py-4 flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3">
                                    <h3 class="text-lg font-semibold text-gray-900" x-text="getSelectedTicketSubject(selectedTicket)"></h3>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" 
                                          :class="getSelectedTicketType(selectedTicket) === 'penipuan' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800'"
                                          x-text="getSelectedTicketType(selectedTicket) === 'penipuan' ? 'Laporan Penipuan' : 'Bantuan'"></span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" 
                                          :class="getSelectedTicketStatus(selectedTicket) === 'open' ? 'bg-green-100 text-green-800' : (getSelectedTicketStatus(selectedTicket) === 'diproses' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800')"
                                          x-text="getSelectedTicketStatus(selectedTicket) === 'open' ? 'Aktif' : (getSelectedTicketStatus(selectedTicket) === 'diproses' ? 'Diproses' : 'Ditutup')"></span>
                                </div>
                                <p class="text-sm text-gray-600 mt-1">
                                    <i class="fas fa-user mr-1"></i>
                                    <span x-text="getSelectedTicketUser(selectedTicket)"></span>
                                </p>
                            </div>
                            <div class="flex space-x-2">
                                <button @click="isProcessModalOpen = true" 
                                        x-show="getSelectedTicketStatus(selectedTicket) === 'open'"
                                        class="inline-flex items-center px-3 py-2 bg-yellow-500 text-white text-sm font-medium rounded-md hover:bg-yellow-600 transition-colors">
                                    <i class="fas fa-cog mr-2"></i>
                                    Proses Tiket
                                </button>
                                <button @click="isCloseModalOpen = true" 
                                        x-show="getSelectedTicketStatus(selectedTicket) === 'open' || getSelectedTicketStatus(selectedTicket) === 'diproses'"
                                        class="inline-flex items-center px-3 py-2 bg-red-500 text-white text-sm font-medium rounded-md hover:bg-red-600 transition-colors">
                                    <i class="fas fa-times mr-2"></i>
                                    Tutup Tiket
                                </button>
                                <button @click="reopenTicket(selectedTicket)" 
                                        x-show="getSelectedTicketStatus(selectedTicket) === 'closed'"
                                        class="inline-flex items-center px-3 py-2 bg-green-500 text-white text-sm font-medium rounded-md hover:bg-green-600 transition-colors">
                                    <i class="fas fa-redo mr-2"></i>
                                    Buka Kembali
                                </button>
                            </div>
                        </div>
                        
                        <!-- Ticket Details Section -->
                        <div class="px-6 pb-4">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <!-- Description -->
                                <div class="mb-3">
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-file-text mr-1"></i>
                                        Deskripsi Tiket
                                    </h4>
                                    <p class="text-sm text-gray-600 leading-relaxed" x-text="getSelectedTicketDescription(selectedTicket)"></p>
                                </div>
                                
                                <!-- Fraud Report Details -->
                                <div x-show="getSelectedTicketType(selectedTicket) === 'penipuan'" class="border-t border-gray-200 pt-3">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div x-show="getSelectedTicketTerlapor(selectedTicket)">
                                            <h5 class="text-sm font-medium text-gray-700 mb-1">
                                                <i class="fas fa-exclamation-triangle mr-1 text-red-500"></i>
                                                Pihak Terlapor
                                            </h5>
                                            <p class="text-sm text-gray-600" x-text="getSelectedTicketTerlapor(selectedTicket)"></p>
                                        </div>
                                        <div x-show="getSelectedTicketTanggalKejadian(selectedTicket)">
                                            <h5 class="text-sm font-medium text-gray-700 mb-1">
                                                <i class="fas fa-calendar mr-1 text-blue-500"></i>
                                                Tanggal Kejadian
                                            </h5>
                                            <p class="text-sm text-gray-600" x-text="getSelectedTicketTanggalKejadian(selectedTicket)"></p>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Documents Section -->
                                <div x-show="getSelectedTicketDocuments(selectedTicket).length > 0" class="border-t border-gray-200 pt-3 mt-3">
                                    <h5 class="text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-paperclip mr-1 text-green-500"></i>
                                        Dokumen Lampiran
                                    </h5>
                                    <div class="flex flex-wrap gap-2">
                                        <template x-for="(doc, index) in getSelectedTicketDocuments(selectedTicket)" :key="index">
                                            <a :href="'/storage/' + doc" target="_blank" 
                                               class="inline-flex items-center px-3 py-2 bg-blue-50 text-blue-700 text-sm rounded-md hover:bg-blue-100 transition-colors border border-blue-200">
                                                <i class="fas fa-download mr-2"></i>
                                                <span x-text="'Dokumen ' + (index + 1)"></span>
                                            </a>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Messages Area -->
                    <div class="flex-1 p-6 overflow-y-auto bg-gray-50" id="messagesContainer">
                        <div x-show="isLoadingMessages" class="text-center py-8">
                            <div class="inline-flex items-center px-4 py-2 bg-white rounded-lg shadow-sm">
                                <i class="fas fa-spinner fa-spin mr-2 text-blue-500"></i>
                                <span class="text-gray-600">Memuat percakapan...</span>
                            </div>
                        </div>
                        
                        <template x-for="message in messages" :key="message.id">
                            <div class="mb-6" :class="message.sender_type === 'admin' ? 'flex justify-end' : 'flex justify-start'">
                                <div class="max-w-xs lg:max-w-md">
                                    <!-- Message bubble -->
                                    <div class="px-4 py-3 rounded-2xl shadow-sm"
                                         :class="message.sender_type === 'admin' 
                                            ? 'bg-blue-500 text-white rounded-br-md' 
                                            : 'bg-white text-gray-800 rounded-bl-md border border-gray-200'">
                                        <p class="text-sm leading-relaxed" x-text="message.message"></p>
                                    </div>
                                    <!-- Message info -->
                                    <div class="mt-1 px-2" :class="message.sender_type === 'admin' ? 'text-right' : 'text-left'">
                                        <p class="text-xs text-gray-500">
                                            <span x-text="message.sender.nama"></span>
                                            <span class="mx-1">•</span>
                                            <span x-text="new Date(message.created_at).toLocaleString('id-ID')"></span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </template>
                        
                        <!-- Empty state -->
                        <div x-show="!isLoadingMessages && messages.length === 0" class="text-center py-12">
                            <div class="inline-flex flex-col items-center">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-comments text-2xl text-gray-400"></i>
                                </div>
                                <p class="text-gray-500 font-medium">Belum ada percakapan</p>
                                <p class="text-sm text-gray-400 mt-1">Mulai percakapan dengan mengirim pesan</p>
                            </div>
                        </div>
                    </div>

                    <!-- Message Input -->
                    <div class="p-4 bg-white border-t border-gray-200" x-show="getSelectedTicketStatus(selectedTicket) === 'open' || getSelectedTicketStatus(selectedTicket) === 'diproses'">
                        <div class="flex space-x-3">
                            <div class="flex-1">
                                <input type="text" 
                                       x-model="newMessage" 
                                       @keydown.enter="sendMessage(newMessage, selectedTicket, $data)"
                                       placeholder="Ketik balasan untuk user..."
                                       class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            </div>
                            <button @click="sendMessage(newMessage, selectedTicket, $data)" 
                                    :disabled="!newMessage.trim()"
                                    class="inline-flex items-center px-4 py-3 bg-blue-500 text-white text-sm font-medium rounded-lg hover:bg-blue-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                                <i class="fas fa-paper-plane mr-2"></i>
                                Kirim
                            </button>
                        </div>
                    </div>
                    
                    <!-- Closed ticket message -->
                    <div class="p-4 bg-gray-50 border-t border-gray-200 text-center" x-show="getSelectedTicketStatus(selectedTicket) === 'closed'">
                        <p class="text-sm text-gray-500">
                            <i class="fas fa-lock mr-1"></i>
                            Tiket ini telah ditutup. Buka kembali untuk melanjutkan percakapan.
                        </p>
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
                    <form method="POST" action="{{ route('manajemen.bantuan.store') }}" enctype="multipart/form-data">
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
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Dokumen Pendukung (Opsional)</label>
                            <input name="bukti_pendukung[]" type="file" multiple 
                                   accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.gif,.txt,.rtf"
                                   class="w-full border border-gray-300 rounded-lg p-2 text-sm"
                                   onchange="validateFileCount(this, 5, 'file-count-bantuan')">
                            <p class="text-xs text-gray-500 mt-1">Format yang didukung: PDF, DOC, DOCX, JPG, PNG, GIF, TXT, RTF. Maksimal 5 file.</p>
                            <p id="file-count-bantuan" class="text-xs text-red-500 mt-1 hidden">Maksimal 5 file yang dapat diunggah.</p>
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
                            <input name="tanggal_kejadian" type="date" required 
                                   max="{{ date('Y-m-d') }}"
                                   class="w-full border border-gray-300 rounded-lg p-2 text-sm">
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Dokumen Pendukung (Opsional)</label>
                            <input name="bukti_pendukung[]" type="file" multiple 
                                   accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.gif,.txt,.rtf"
                                   class="w-full border border-gray-300 rounded-lg p-2 text-sm"
                                   onchange="validateFileCount(this, 5, 'file-count-penipuan')">
                            <p class="text-xs text-gray-500 mt-1">Format yang didukung: PDF, DOC, DOCX, JPG, PNG, GIF, TXT, RTF. Maksimal 5 file.</p>
                            <p id="file-count-penipuan" class="text-xs text-red-500 mt-1 hidden">Maksimal 5 file yang dapat diunggah.</p>
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
                            <div @click="selectTicket({{ $ticket->id }}, $data)"
                                 :class="selectedTicket === {{ $ticket->id }} ? 'bg-blue-50 border-blue-200' : 'bg-gray-50 border-gray-200'"
                                 class="p-3 border rounded-lg cursor-pointer hover:bg-blue-50 transition-colors">
                                
                                <div class="flex items-center justify-between mb-2">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                        {{ $ticket->type === 'penipuan' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }}">
                                        {{ $ticket->type === 'penipuan' ? 'Penipuan' : 'Bantuan' }}
                                    </span>
                                    
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                        @if($ticket->status === 'open') bg-green-100 text-green-800
                                        @elseif($ticket->status === 'diproses') bg-yellow-100 text-yellow-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        @if($ticket->status === 'open') Aktif
                                        @elseif($ticket->status === 'diproses') Diproses
                                        @else Ditutup @endif
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

                    <div x-show="selectedTicket" class="flex flex-col" style="height: 600px;">
                        <!-- Conversation Header -->
                        <div class="p-4 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="font-semibold text-gray-900" x-text="getSelectedTicketSubject(selectedTicket)"></h3>
                                    
                                    <!-- Display ticket details -->
                                    <div class="mt-2 p-2 bg-gray-50 rounded text-xs">
                                        <!-- Show description for all tickets -->
                                        <div class="mb-2">
                                            <span class="font-medium text-gray-700">Deskripsi:</span>
                                            <p class="text-gray-600 mt-1" x-text="getSelectedTicketDescription(selectedTicket)"></p>
                                        </div>
                                        
                                        <!-- Show additional details for penipuan tickets -->
                                        <div x-show="getSelectedTicketType(selectedTicket) === 'penipuan'">
                                            <div class="mb-2" x-show="getSelectedTicketTerlapor(selectedTicket)">
                                                <span class="font-medium text-gray-700">Pihak Terlapor:</span>
                                                <p class="text-gray-600 mt-1" x-text="getSelectedTicketTerlapor(selectedTicket)"></p>
                                            </div>
                                            <div x-show="getSelectedTicketTanggalKejadian(selectedTicket)">
                                                <span class="font-medium text-gray-700">Tanggal Kejadian:</span>
                                                <p class="text-gray-600 mt-1" x-text="getSelectedTicketTanggalKejadian(selectedTicket)"></p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Display uploaded documents -->
                                    <div x-show="getSelectedTicketDocuments(selectedTicket).length > 0" class="mt-2">
                                        <p class="text-xs text-gray-500 mb-1">Dokumen Lampiran:</p>
                                        <div class="flex flex-wrap gap-1">
                                            <template x-for="(doc, index) in getSelectedTicketDocuments(selectedTicket)" :key="index">
                                                <a :href="'/storage/' + doc" target="_blank" 
                                                   class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded hover:bg-blue-200">
                                                    <i class="fas fa-file mr-1"></i>
                                                    <span x-text="'Dokumen ' + (index + 1)"></span>
                                                </a>
                                            </template>
                                        </div>
                                    </div>
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
                        <div class="p-4 border-t border-gray-200" x-show="getSelectedTicketStatus(selectedTicket) === 'open' || getSelectedTicketStatus(selectedTicket) === 'diproses'">
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

    <!-- Process Ticket Modal -->
    <div x-show="isProcessModalOpen" 
         x-transition:enter="ease-out duration-300" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100" 
         x-transition:leave="ease-in duration-200" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div x-show="isProcessModalOpen" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0" 
                 x-transition:enter-end="opacity-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100" 
                 x-transition:leave-end="opacity-0"
                 @click="isProcessModalOpen = false; processMessage = ''"
                 class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

            <!-- This element is to trick the browser into centering the modal contents. -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal panel -->
            <div x-show="isProcessModalOpen" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="fas fa-cog text-yellow-600"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left flex-1">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                            Proses Tiket
                        </h3>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Pesan Pemrosesan <span class="text-red-500">*</span>
                            </label>
                            <textarea x-model="processMessage" 
                                      rows="4" 
                                      placeholder="Masukkan pesan pemrosesan untuk user..."
                                      class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 resize-none"
                                      @keydown.escape="isProcessModalOpen = false; processMessage = ''"></textarea>
                            <p class="text-xs text-gray-500 mt-1">
                                Pesan ini akan dikirim kepada user sebagai notifikasi bahwa tiket sedang diproses.
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                    <button @click.stop="
                            if (!selectedTicket || !processMessage.trim()) { 
                                alert('Silakan pilih tiket dan masukkan pesan pemrosesan.'); 
                                return; 
                            }
                            fetch('/management/ticket/' + selectedTicket + '/process', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                                },
                                body: JSON.stringify({
                                    processing_message: processMessage
                                })
                            })
                            .then(response => response.ok ? (isProcessModalOpen = false, processMessage = '', location.reload()) : alert('Gagal memproses tiket'))
                            .catch(() => alert('Gagal memproses tiket'))
                            " 
                            :disabled="!processMessage.trim()"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-yellow-500 text-base font-medium text-white hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed transition-colors relative z-10">
                        <i class="fas fa-cog mr-2"></i>
                        Proses Tiket
                    </button>
                    <button @click.stop="isProcessModalOpen = false; processMessage = ''" 
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:w-auto sm:text-sm transition-colors relative z-10">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Close Ticket Modal -->
    <div x-show="isCloseModalOpen" 
         x-transition:enter="ease-out duration-300" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100" 
         x-transition:leave="ease-in duration-200" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div x-show="isCloseModalOpen" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0" 
                 x-transition:enter-end="opacity-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100" 
                 x-transition:leave-end="opacity-0"
                 @click="isCloseModalOpen = false; closeMessage = ''"
                 class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

            <!-- This element is to trick the browser into centering the modal contents. -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal panel -->
            <div x-show="isCloseModalOpen" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="fas fa-times text-red-600"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left flex-1">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                            Tutup Tiket
                        </h3>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Pesan Penutupan <span class="text-red-500">*</span>
                            </label>
                            <textarea x-model="closeMessage" 
                                      rows="4" 
                                      placeholder="Masukkan pesan penutupan untuk user..."
                                      class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 resize-none"
                                      @keydown.escape="isCloseModalOpen = false; closeMessage = ''"></textarea>
                            <p class="text-xs text-gray-500 mt-1">
                                Pesan ini akan dikirim kepada user sebagai notifikasi penutupan tiket.
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                    <button @click.stop="
                            if (!selectedTicket || !closeMessage.trim()) { 
                                alert('Silakan pilih tiket dan masukkan pesan penutupan.'); 
                                return; 
                            }
                            fetch('/management/ticket/' + selectedTicket + '/close', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                                },
                                body: JSON.stringify({
                                    closing_message: closeMessage
                                })
                            })
                            .then(response => response.ok ? (isCloseModalOpen = false, closeMessage = '', location.reload()) : alert('Gagal menutup tiket'))
                            .catch(() => alert('Gagal menutup tiket'))
                            " 
                            :disabled="!closeMessage.trim()"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-500 text-base font-medium text-white hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed transition-colors relative z-10">
                        <i class="fas fa-times mr-2"></i>
                        Tutup Tiket
                    </button>
                    <button @click.stop="isCloseModalOpen = false; closeMessage = ''" 
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:w-auto sm:text-sm transition-colors relative z-10">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
// Function to validate file count
function validateFileCount(input, maxFiles, errorElementId) {
    const files = input.files;
    const errorElement = document.getElementById(errorElementId);
    
    if (files.length > maxFiles) {
        // Show error message
        errorElement.classList.remove('hidden');
        
        // Clear the input
        input.value = '';
        
        // Show alert
        alert(`Maksimal ${maxFiles} file yang dapat diunggah. Silakan pilih ulang file Anda.`);
    } else {
        // Hide error message
        errorElement.classList.add('hidden');
    }
}

// Initialize tickets data immediately
window.ticketsData = @json($tickets) || [];

document.addEventListener('DOMContentLoaded', function() {
    console.log('Tickets data loaded:', window.ticketsData);
    
    // Ensure ticketsData is available
    if (!window.ticketsData || !Array.isArray(window.ticketsData)) {
        console.error('Tickets data is not properly loaded');
        window.ticketsData = [];
    }
});

function getSelectedTicketSubject(selectedTicket) {
    if (!window.ticketsData || !Array.isArray(window.ticketsData)) return '';
    const ticket = window.ticketsData.find(t => t.id === selectedTicket);
    return ticket ? ticket.subject : '';
}

function getSelectedTicketUser(selectedTicket) {
    if (!window.ticketsData || !Array.isArray(window.ticketsData)) return '';
    const ticket = window.ticketsData.find(t => t.id === selectedTicket);
    return ticket && ticket.user ? ticket.user.nama : '';
}

function getSelectedTicketStatus(selectedTicket) {
    if (!window.ticketsData || !Array.isArray(window.ticketsData)) return '';
    const ticket = window.ticketsData.find(t => t.id === selectedTicket);
    return ticket ? ticket.status : '';
}

function getSelectedTicketDocuments(selectedTicket) {
    if (!window.ticketsData || !Array.isArray(window.ticketsData)) return [];
    const ticket = window.ticketsData.find(t => t.id === selectedTicket);
    return ticket && ticket.bukti_pendukung ? ticket.bukti_pendukung : [];
}

function getSelectedTicketDescription(selectedTicket) {
    if (!window.ticketsData || !Array.isArray(window.ticketsData)) return '';
    const ticket = window.ticketsData.find(t => t.id === selectedTicket);
    return ticket ? ticket.description : '';
}

function getSelectedTicketType(selectedTicket) {
    if (!window.ticketsData || !Array.isArray(window.ticketsData)) return '';
    const ticket = window.ticketsData.find(t => t.id === selectedTicket);
    return ticket ? ticket.type : '';
}

function getSelectedTicketTerlapor(selectedTicket) {
    if (!window.ticketsData || !Array.isArray(window.ticketsData)) return '';
    const ticket = window.ticketsData.find(t => t.id === selectedTicket);
    return ticket ? ticket.pihak_terlapor : '';
}

function getSelectedTicketTanggalKejadian(selectedTicket) {
    if (!window.ticketsData || !Array.isArray(window.ticketsData)) return '';
    const ticket = window.ticketsData.find(t => t.id === selectedTicket);
    return ticket && ticket.tanggal_kejadian ? new Date(ticket.tanggal_kejadian).toLocaleDateString('id-ID') : '';
}

async function loadMessages(ticketId, alpineInstance, isPolling = false) {
    if (!isPolling) {
        console.log('Loading messages for ticket:', ticketId);
        alpineInstance.isLoadingMessages = true;
    }
    
    try {
        const response = await fetch(`/management/ticket/${ticketId}/messages`);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        
        // Only update if there are new messages (for polling)
        if (isPolling && data.messages.length === alpineInstance.lastMessageCount) {
            return;
        }
        
        const shouldScroll = !isPolling || data.messages.length > alpineInstance.lastMessageCount;
        alpineInstance.messages = data.messages;
        alpineInstance.lastMessageCount = data.messages.length;
        
        // Scroll to bottom after messages load
        if (shouldScroll) {
            setTimeout(() => {
                const container = document.getElementById('messagesContainer');
                if (container) {
                    container.scrollTop = container.scrollHeight;
                }
            }, 100);
        }
        
    } catch (error) {
        if (!isPolling) {
            console.error('Error loading messages:', error);
            alert('Gagal memuat percakapan: ' + error.message);
        }
    } finally {
        if (!isPolling) {
            alpineInstance.isLoadingMessages = false;
        }
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

// New modal-based functions - using Alpine's $data magic property
window.confirmProcessTicket = function() {
    return {
        execute() {
            const alpineData = this.$data || this;
            
            console.log('Process ticket - Alpine data:', alpineData);
            
            if (!alpineData.selectedTicket || !alpineData.processMessage.trim()) {
                console.log('Missing data:', { 
                    selectedTicket: alpineData.selectedTicket, 
                    processMessage: alpineData.processMessage 
                });
                alert('Silakan pilih tiket dan masukkan pesan pemrosesan.');
                return;
            }
            
            fetch(`/management/ticket/${alpineData.selectedTicket}/process`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    processing_message: alpineData.processMessage
                })
            })
            .then(response => {
                if (response.ok) {
                    // Close modal and reset
                    alpineData.isProcessModalOpen = false;
                    alpineData.processMessage = '';
                    location.reload();
                } else {
                    console.error('Server error:', response.status);
                    alert('Gagal memproses tiket');
                }
            })
            .catch(error => {
                console.error('Error processing ticket:', error);
                alert('Gagal memproses tiket');
            });
        }
    };
};

window.confirmCloseTicket = function() {
    return {
        execute() {
            const alpineData = this.$data || this;
            
            console.log('Close ticket - Alpine data:', alpineData);
            
            if (!alpineData.selectedTicket || !alpineData.closeMessage.trim()) {
                console.log('Missing data:', { 
                    selectedTicket: alpineData.selectedTicket, 
                    closeMessage: alpineData.closeMessage 
                });
                alert('Silakan pilih tiket dan masukkan pesan penutupan.');
                return;
            }
            
            fetch(`/management/ticket/${alpineData.selectedTicket}/close`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    closing_message: alpineData.closeMessage
                })
            })
            .then(response => {
                if (response.ok) {
                    // Close modal and reset
                    alpineData.isCloseModalOpen = false;
                    alpineData.closeMessage = '';
                    location.reload();
                } else {
                    console.error('Server error:', response.status);
                    alert('Gagal menutup tiket');
                }
            })
            .catch(error => {
                console.error('Error closing ticket:', error);
                alert('Gagal menutup tiket');
            });
        }
    };
};

// Legacy functions kept for backward compatibility (not used anymore)
async function closeTicket(selectedTicket) {
    // This function is deprecated - now using modal-based approach
    console.warn('closeTicket function is deprecated, using modal instead');
}

async function processTicket(selectedTicket) {
    // This function is deprecated - now using modal-based approach
    console.warn('processTicket function is deprecated, using modal instead');
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

function selectTicket(ticketId, alpineInstance) {
    // Stop existing polling
    if (alpineInstance.refreshInterval) {
        clearInterval(alpineInstance.refreshInterval);
        alpineInstance.refreshInterval = null;
    }
    
    // Set new ticket
    alpineInstance.selectedTicket = ticketId;
    alpineInstance.lastMessageCount = 0;
    
    // Load messages initially
    loadMessages(ticketId, alpineInstance);
    
    // Start polling for new messages every 1 second
    alpineInstance.refreshInterval = setInterval(() => {
        if (alpineInstance.selectedTicket === ticketId) {
            loadMessages(ticketId, alpineInstance, true);
        }
    }, 1000);
}

function stopPolling(alpineInstance) {
    if (alpineInstance.refreshInterval) {
        clearInterval(alpineInstance.refreshInterval);
        alpineInstance.refreshInterval = null;
    }
}

// Clean up polling when page unloads
window.addEventListener('beforeunload', function() {
    // Alpine data is not easily accessible here, but intervals will be cleared anyway
});
</script>
@endpush
