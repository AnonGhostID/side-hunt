@extends('Dewa.Base.Basic-page')

@section('css')
<style>
.profile-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 32px rgba(0,0,0,0.1);
    padding: 2rem;
}
</style>
@endsection

@section('content')
<div class="container py-5">
    <div class="profile-card">
        <div class="text-center mb-4">
            <h3 class="fw-bold">Profil Pelamar</h3>
        </div>
        
        <div class="row">
            <div class="col-md-4 text-center">
                <div class="mb-3">
                    <svg width="100" height="100" viewBox="0 0 30 29" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect width="30" height="29" rx="14.5" fill="#e0e0e0" />
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M11.375 9.5C11.375 8.57174 11.7437 7.6815 12.4001 7.02513C13.0565 6.36875 13.9467 6 14.875 6C15.8033 6 16.6935 6.36875 17.3499 7.02513C18.0063 7.6815 18.375 8.57174 18.375 9.5C18.375 10.4283 18.0063 11.3185 17.3499 11.9749C16.6935 12.6313 15.8033 13 14.875 13C13.9467 13 13.0565 12.6313 12.4001 11.9749C11.7437 11.3185 11.375 10.4283 11.375 9.5ZM11.375 14.75C10.2147 14.75 9.10188 15.2109 8.28141 16.0314C7.46094 16.8519 7 17.9647 7 19.125C7 19.8212 7.27656 20.4889 7.76884 20.9812C8.26113 21.4734 8.92881 21.75 9.625 21.75H20.125C20.8212 21.75 21.4889 21.4734 21.9812 20.9812C22.4734 20.4889 22.75 19.8212 22.75 19.125C22.75 17.9647 22.2891 16.8519 21.4686 16.0314C20.6481 15.2109 19.5353 14.75 18.375 14.75H11.375Z" fill="#666"/>
                    </svg>
                </div>
                <h5 class="fw-bold">{{ isset($pelamar->nama) ? $pelamar->nama : 'Nama Pelamar' }}</h5>
                <p class="text-muted">{{ isset($pelamar->email) ? $pelamar->email : 'email@example.com' }}</p>
            </div>
            
            <div class="col-md-8">
                <div class="row mb-3">
                    <div class="col-sm-4"><strong>Nomor Telepon:</strong></div>
                    <div class="col-sm-8">{{ isset($pelamar->telpon) ? $pelamar->telpon : 'Tidak tersedia' }}</div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-sm-4"><strong>Alamat:</strong></div>
                    <div class="col-sm-8">{{ isset($pelamar->alamat) ? $pelamar->alamat : 'Tidak tersedia' }}</div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-sm-4"><strong>Status Lamaran:</strong></div>
                    <div class="col-sm-8">
                        <span class="badge bg-info">{{ isset($pelamar->status) ? $pelamar->status : 'Pending' }}</span>
                    </div>
                </div>
                
                @if(isset($pelamar->preferensi_user))
                <div class="row mb-3">
                    <div class="col-sm-4"><strong>Keahlian:</strong></div>
                    <div class="col-sm-8">
                        <div class="d-flex flex-wrap gap-1">
                            @php
                                $skills = is_string($pelamar->preferensi_user) ? json_decode($pelamar->preferensi_user, true) : $pelamar->preferensi_user;
                                $criteria = isset($skills['kriteria']) ? $skills['kriteria'] : [];
                            @endphp
                            @if(is_array($criteria))
                                @foreach($criteria as $skill)
                                <span class="badge bg-secondary">{{ $skill }}</span>
                                @endforeach
                            @else
                                <span class="text-muted">Belum diisi</span>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
                
                @if(isset($pelamar->deskripsi))
                <div class="row mb-3">
                    <div class="col-sm-4"><strong>Deskripsi:</strong></div>
                    <div class="col-sm-8">
                        @php
                            $preferensi = is_string($pelamar->preferensi_user) ? json_decode($pelamar->preferensi_user, true) : $pelamar->preferensi_user;
                            $deskripsi = isset($preferensi['deskripsi']) ? $preferensi['deskripsi'] : 'Belum diisi';
                        @endphp
                        {{ $deskripsi }}
                    </div>
                </div>
                @endif
                
                <div class="mt-4">
                    <a href="javascript:history.back()" class="btn btn-secondary">Kembali</a>
                    @if(isset($pelamar->id))
                    <a href="/chat/{{ $pelamar->id }}" class="btn btn-primary">Chat Pelamar</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection