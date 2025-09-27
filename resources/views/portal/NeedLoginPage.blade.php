@extends('layouts.portal')

@section('title', 'Butuh Login')
@section('page-title', 'Butuh Login')
@section('page-subtitle')
    Masuk terlebih dahulu untuk mengakses halaman yang diminta.
@endsection

@section('content')
    <div class="mx-auto max-w-2xl">
        <div class="flex flex-col items-center justify-center gap-6 rounded-2xl border border-gray-200 bg-white p-10 text-center shadow-sm">
            <div class="flex h-16 w-16 items-center justify-center rounded-full bg-blue-100 text-blue-600">
                <i class="fas fa-lock text-2xl"></i>
            </div>
            <div class="space-y-2">
                <h2 class="text-xl font-semibold text-gray-900">Akses terbatas</h2>
                <p class="text-sm text-gray-600">Silakan masuk atau daftar untuk melanjutkan ke halaman ini.</p>
            </div>
            <div class="flex flex-col gap-3 sm:flex-row">
                <a href="/Login" class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-700">
                    <i class="fas fa-sign-in-alt mr-2"></i>Masuk Sekarang
                </a>
                <a href="/Register" class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm hover:border-blue-500 hover:text-blue-600">
                    <i class="fas fa-user-plus mr-2"></i>Buat Akun Baru
                </a>
            </div>
        </div>
    </div>
@endsection
