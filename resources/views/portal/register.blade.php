@extends('layouts.portal')

@section('title', 'Buat Akun Baru')
@section('page-title', 'Mulai bergabung dengan SideHunt')
@section('page-subtitle')
    Daftar sebagai mitra atau pencari kerja dan kelola semua aktivitas Anda dalam satu portal.
@endsection

@section('content')
    <div class="mx-auto max-w-3xl">
        <div class="rounded-2xl border border-gray-200 bg-white p-8 shadow-sm">
            <form action="/Register_account" method="POST" class="space-y-8">
                @csrf

                <div class="grid gap-6 md:grid-cols-2">
                    <div class="space-y-1 md:col-span-2">
                        <label for="role" class="text-sm font-semibold text-gray-700">Daftar sebagai<span class="text-red-500">*</span></label>
                        <select id="role" name="role" class="w-full rounded-lg border border-gray-200 px-4 py-3 text-sm text-gray-700 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                            <option value="mitra" {{ old('role') === 'mitra' ? 'selected' : '' }}>Mitra (Pemberi Kerja)</option>
                            <option value="user" {{ old('role') === 'user' || old('role') === null ? 'selected' : '' }}>User (Pencari Kerja)</option>
                        </select>
                        @error('role')
                            <p class="text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1">
                        <label for="nama-depan" class="text-sm font-semibold text-gray-700">Nama Depan<span class="text-red-500">*</span></label>
                        <input id="nama-depan" type="text" name="nama-depan" maxlength="60" placeholder="John"
                               class="w-full rounded-lg border border-gray-200 px-4 py-3 text-sm text-gray-700 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                               value="{{ old('nama-depan') }}">
                        @error('nama-depan')
                            <p class="text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1">
                        <label for="nama-belakang" class="text-sm font-semibold text-gray-700">Nama Belakang<span class="text-red-500">*</span></label>
                        <input id="nama-belakang" type="text" name="nama-belakang" maxlength="60" placeholder="Doe"
                               class="w-full rounded-lg border border-gray-200 px-4 py-3 text-sm text-gray-700 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                               value="{{ old('nama-belakang') }}">
                        @error('nama-belakang')
                            <p class="text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="space-y-1">
                    <label for="email" class="text-sm font-semibold text-gray-700">Alamat Email<span class="text-red-500">*</span></label>
                    <input id="email" type="email" name="email" maxlength="100" placeholder="nama@contoh.com"
                           class="w-full rounded-lg border border-gray-200 px-4 py-3 text-sm text-gray-700 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                           value="{{ old('email') }}">
                    @error('email')
                        <p class="text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid gap-6 md:grid-cols-2">
                    <div class="space-y-1">
                        <label for="password" class="text-sm font-semibold text-gray-700">Password<span class="text-red-500">*</span></label>
                        <input id="password" type="password" name="password" maxlength="64" placeholder="Minimal 8 karakter"
                               class="w-full rounded-lg border border-gray-200 px-4 py-3 text-sm text-gray-700 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                        @error('password')
                            <p class="text-xs text-red-500">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500">Gunakan kombinasi huruf besar, huruf kecil, dan angka.</p>
                    </div>

                    <div class="space-y-1">
                        <label for="password-retype" class="text-sm font-semibold text-gray-700">Konfirmasi Password<span class="text-red-500">*</span></label>
                        <input id="password-retype" type="password" name="password-retype" maxlength="64" placeholder="Ulangi password"
                               class="w-full rounded-lg border border-gray-200 px-4 py-3 text-sm text-gray-700 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                        @error('password-retype')
                            <p class="text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <label class="inline-flex items-center gap-2 text-sm text-gray-600">
                    <input type="checkbox" id="toggle-register-password" class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span>Tampilkan password</span>
                </label>

                <button type="submit" class="inline-flex w-full items-center justify-center rounded-lg bg-blue-600 px-4 py-3 text-sm font-semibold text-white shadow-sm hover:bg-blue-700">
                    Daftar
                </button>

                <p class="text-center text-sm text-gray-600">
                    Sudah punya akun?
                    <a href="/Login" class="font-semibold text-blue-600 hover:text-blue-700">Masuk Sekarang</a>
                </p>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script>
        const toggleRegisterPassword = document.getElementById('toggle-register-password');
        toggleRegisterPassword?.addEventListener('change', (event) => {
            const fields = [document.getElementById('password'), document.getElementById('password-retype')];
            fields.forEach((field) => {
                if (!field) return;
                field.setAttribute('type', event.target.checked ? 'text' : 'password');
            });
        });
    </script>
@endsection
