@extends('layouts.portal')

@section('title', 'Akses Ditolak')
@section('page-title', 'Akses ditolak')
@section('page-subtitle')
    Status akun Anda belum memiliki izin untuk membuka halaman ini.
@endsection

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mx-auto max-w-2xl">
        <div class="flex flex-col items-center justify-center gap-6 rounded-2xl border border-red-200 bg-white p-10 text-center shadow-sm">
            <div class="flex h-16 w-16 items-center justify-center rounded-full bg-red-100 text-red-600">
                <i class="fas fa-ban text-2xl"></i>
            </div>
            <div class="space-y-2">
                <h2 class="text-xl font-semibold text-gray-900">Maaf, akses ditolak</h2>
                <p class="text-sm text-gray-600">Hubungi tim dukungan atau administrator untuk mendapatkan akses yang sesuai.</p>
            </div>
            <a href="/Index" class="inline-flex items-center justify-center rounded-lg bg-red-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-red-700">
                <i class="fas fa-arrow-left mr-2"></i>Kembali ke beranda
            </a>
        </div>
    </div>
</div>
@endsection
