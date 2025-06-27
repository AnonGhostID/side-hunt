@extends('layouts.management')

@section('title', 'Beri Rating Pekerja')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-xl font-semibold text-gray-800">Beri Rating Pekerja</h3>
        </div>
        <div class="p-6">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <form action="{{ route('manajemen.rating.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="pekerja_id" class="block text-sm font-medium text-gray-700 mb-2">Pilih Pekerja</label>
                        <select id="pekerja_id" name="pekerja_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Pilih Pekerja --</option>
                            <option value="1">John Doe - Pekerjaan Web Development</option>
                            <option value="2">Jane Smith - Pekerjaan Graphic Design</option>
                            <option value="3">Bob Wilson - Pekerjaan Data Entry</option>
                        </select>
                    </div>
                    <div>
                        <label for="rating" class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                        <div class="rating-stars">
                            <input type="radio" name="rating" value="5" id="star5">
                            <label for="star5" title="5 stars">★</label>
                            <input type="radio" name="rating" value="4" id="star4">
                            <label for="star4" title="4 stars">★</label>
                            <input type="radio" name="rating" value="3" id="star3">
                            <label for="star3" title="3 stars">★</label>
                            <input type="radio" name="rating" value="2" id="star2">
                            <label for="star2" title="2 stars">★</label>
                            <input type="radio" name="rating" value="1" id="star1">
                            <label for="star1" title="1 star">★</label>
                        </div>
                    </div>
                </div>
                
                <div class="mt-6">
                    <label for="komentar" class="block text-sm font-medium text-gray-700 mb-2">Komentar (Opsional)</label>
                    <textarea id="komentar" name="komentar" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Berikan komentar tentang kinerja pekerja..."></textarea>
                </div>

                <div class="mt-8 flex justify-between">
                    <a href="{{ route('manajemen.dashboard') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-star mr-2"></i> Berikan Rating
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.rating-stars {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
}

.rating-stars input[type="radio"] {
    display: none;
}

.rating-stars label {
    font-size: 2rem;
    color: #ddd;
    cursor: pointer;
    transition: color 0.2s;
}

.rating-stars label:hover,
.rating-stars label:hover ~ label,
.rating-stars input[type="radio"]:checked ~ label {
    color: #ffc107;
}

.rating-stars input[type="radio"]:checked ~ label {
    color: #ffc107;
}
</style>
@endsection