@extends('layouts.portal')

@section('title', 'Masuk ke SideHunt')
@section('page-title', 'Masuk ke Akun Anda')
@section('page-subtitle')
    Gunakan email dan kata sandi yang terdaftar untuk mengelola pekerjaan, lamaran, dan transaksi.
@endsection

@section('content')
    <div class="mx-auto max-w-xl">
        <div class="rounded-2xl border border-gray-200 bg-white p-8 shadow-sm">
            <form action="/Login_account" method="POST" class="space-y-6">
                @csrf

                <div class="space-y-1">
                    <label for="email" class="text-sm font-semibold text-gray-700">Alamat Email<span class="text-red-500">*</span></label>
                    <input id="email" type="email" name="email" maxlength="40" value="{{ old('email') }}" placeholder="Masukkan email anda"
                           class="w-full rounded-lg border border-gray-200 px-4 py-3 text-sm text-gray-700 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                    @error('email')
                        <p class="text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-1">
                    <label for="password" class="text-sm font-semibold text-gray-700">Password<span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input id="password" type="password" name="password" maxlength="64" value="{{ old('password') }}" placeholder="Masukkan password"
                            class="w-full rounded-lg border border-gray-200 px-4 py-3 text-sm text-gray-700 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                        <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-300">
                            <i class="fas fa-key"></i>
                        </span>
                    </div>
                    @error('password')
                        <p class="text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between text-sm">
                    <label class="inline-flex items-center gap-2 text-gray-600">
                        <input type="checkbox" id="toggle-password" class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span>Lihat kata sandi</span>
                    </label>
                    @if(Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="font-medium text-blue-600 hover:text-blue-700">Lupa kata sandi?</a>
                    @endif
                </div>

                <button type="submit" class="inline-flex w-full items-center justify-center rounded-lg bg-blue-600 px-4 py-3 text-sm font-semibold text-white shadow-sm hover:bg-blue-700">
                    Masuk
                </button>

                <p class="text-center text-sm text-gray-600">
                    Belum punya akun?
                    <a href="/Register" class="font-semibold text-blue-600 hover:text-blue-700">Daftar Sekarang</a>
                </p>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script>
        document.getElementById('toggle-password')?.addEventListener('change', (event) => {
            const input = document.getElementById('password');
            if (!input) return;
            input.setAttribute('type', event.target.checked ? 'text' : 'password');
        });
    </script>
@endsection
