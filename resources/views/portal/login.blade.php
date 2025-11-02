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

        <!-- Demo Credentials Section -->
        <div class="mt-8 rounded-2xl border border-blue-100 bg-blue-50 p-6">
            <div class="mb-4 text-center">
                <h3 class="text-lg font-semibold text-gray-800">Demo Login Credentials</h3>
                <p class="text-sm text-gray-600">Klik tombol di bawah untuk mengisi otomatis (Portfolio Demo)</p>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Mitra Credentials (Left) -->
                <div class="rounded-xl border border-blue-200 bg-white p-4 shadow-sm">
                    <div class="mb-3 flex items-center gap-2 border-b border-gray-200 pb-2">
                        <i class="fas fa-briefcase text-blue-600"></i>
                        <h4 class="font-semibold text-gray-800">Akun Mitra (Owner)</h4>
                    </div>
                    <div class="space-y-2">
                        <button type="button" onclick="fillLogin('owner1@example.com', 'owner1234')" 
                                class="w-full rounded-lg border border-blue-300 bg-blue-50 px-4 py-2.5 text-left text-sm font-medium text-blue-700 transition hover:bg-blue-100">
                            <i class="fas fa-user-tie mr-2"></i>Mitra 1
                            <div class="mt-1 text-xs text-blue-600">owner1@example.com</div>
                        </button>
                        <button type="button" onclick="fillLogin('owner2@example.com', 'owner1234')" 
                                class="w-full rounded-lg border border-blue-300 bg-blue-50 px-4 py-2.5 text-left text-sm font-medium text-blue-700 transition hover:bg-blue-100">
                            <i class="fas fa-user-tie mr-2"></i>Mitra 2
                            <div class="mt-1 text-xs text-blue-600">owner2@example.com</div>
                        </button>
                        <button type="button" onclick="fillLogin('owner3@example.com', 'owner1234')" 
                                class="w-full rounded-lg border border-blue-300 bg-blue-50 px-4 py-2.5 text-left text-sm font-medium text-blue-700 transition hover:bg-blue-100">
                            <i class="fas fa-user-tie mr-2"></i>Mitra 3
                            <div class="mt-1 text-xs text-blue-600">owner3@example.com</div>
                        </button>
                    </div>
                </div>

                <!-- User Credentials (Right) -->
                <div class="rounded-xl border border-green-200 bg-white p-4 shadow-sm">
                    <div class="mb-3 flex items-center gap-2 border-b border-gray-200 pb-2">
                        <i class="fas fa-users text-green-600"></i>
                        <h4 class="font-semibold text-gray-800">Akun User</h4>
                    </div>
                    <div class="space-y-2">
                        <button type="button" onclick="fillLogin('user1@example.com', 'user1234')" 
                                class="w-full rounded-lg border border-green-300 bg-green-50 px-4 py-2.5 text-left text-sm font-medium text-green-700 transition hover:bg-green-100">
                            <i class="fas fa-user mr-2"></i>User 1
                            <div class="mt-1 text-xs text-green-600">user1@example.com</div>
                        </button>
                        <button type="button" onclick="fillLogin('user2@example.com', 'user1234')" 
                                class="w-full rounded-lg border border-green-300 bg-green-50 px-4 py-2.5 text-left text-sm font-medium text-green-700 transition hover:bg-green-100">
                            <i class="fas fa-user mr-2"></i>User 2
                            <div class="mt-1 text-xs text-green-600">user2@example.com</div>
                        </button>
                        <button type="button" onclick="fillLogin('user3@example.com', 'user1234')" 
                                class="w-full rounded-lg border border-green-300 bg-green-50 px-4 py-2.5 text-left text-sm font-medium text-green-700 transition hover:bg-green-100">
                            <i class="fas fa-user mr-2"></i>User 3
                            <div class="mt-1 text-xs text-green-600">user3@example.com</div>
                        </button>
                    </div>
                </div>
            </div>
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

        function fillLogin(email, password) {
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            
            emailInput.value = email;
            passwordInput.value = password;
            
            // Scroll to top to show the filled form
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
            
            // Add highlight effect
            emailInput.classList.add('!border-green-500', '!ring-2', '!ring-green-200');
            passwordInput.classList.add('!border-green-500', '!ring-2', '!ring-green-200');
            
            setTimeout(() => {
                emailInput.classList.remove('!border-green-500', '!ring-2', '!ring-green-200');
                passwordInput.classList.remove('!border-green-500', '!ring-2', '!ring-green-200');
            }, 1500);
        }
    </script>
@endsection
