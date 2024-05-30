
@extends('layouts.app')

@section('content')
<div class="container mt-5 mb-5">
    <div class="row">
        <div class="col">
            <div class="card border-0 shadow-sm rounded">
                <div class="card-body">
                    <h1 class="display-2">{{ $sidejob->nama }}</h1>
                    <h3 class="display-4">Rp{{$sidejob->min_gaji}} - Rp{{$sidejob->max_gaji}}</h1>
                        <hr>
                        <h4>Deskripsi</h4>
                        <p>{{ $sidejob->deskripsi }}</p>
                        <h4>Lokasi Pekerjaan</h4>
                        <p>{{ $sidejob->alamat}}</p>

                        {{-- Untuk yang log in adalah pembuat --}}
                        @if(auth()->check())
                        @php
                        $userApplied = app('App\Models\Pelamar')->where('job_id', $sidejob->id)->where('user_id', auth()->id())->exists();
                        @endphp
                        @if($sidejob->pembuat == auth()->id())
                        <div class="btn-group">
                            <a href="{{ route('sidejob.edit', $sidejob) }}" class="btn btn-primary">Edit</a>
                            <form action="{{ route('sidejob.destroy', $sidejob) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </div>
                        {{-- Untuk yang login yang bukan pembuat dan belum daftar ke pekerjaan tersebut --}}
                        @elseif(!$userApplied)
                            <form action="{{ route('sidejob.buatPermintaan', $sidejob) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary">Daftar Pekerjaan</button>
                            </form>
                        @else
                        {{-- Untuk yang membuat lamaran menunggu status--}}
                        @php
                        $userPelamar = $pelamar->where('user_id', auth()->id())->first();
                        @endphp
                        @if($userPelamar->status == 'tunda')
                            <p>Anda sudah melamar, tunggu pembuat membuat pilihan untuk terima atau tolak.</p>
                        @elseif($userPelamar->status == 'diterima')
                            <p>Anda sudah diterima oleh pembuat.</p>
                        @elseif($userPelamar->status == 'ditolak')
                            <p>Anda sudah ditolak oleh pembuat.</p>
                        @endif
                        @endif
                        {{-- Guest --}}
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary">Login untuk Melamar</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container mt-5 mb-5">
    <div class="row">
        <div class="col">
            @if($sidejob->pembuat == auth()->id())
            <div class="card border-0 shadow-sm rounded">
                <div class="card-body">
                    <h4>Daftar Pelamar:</h4>
                    <ul>
                        @foreach($pelamar as $pelamaritem)
                        <div class="container">
                            <div class="row">
                                <div class="col">
                                    <p>{{ $pelamaritem->user->nama }}</p>
                                </div>
                                <div class="col">
                                    <div class="btn-group justify-content-end">
                                        @if($pelamaritem->status == 'tunda')
                                                <form action="{{ route('pelamar.terima', $pelamaritem) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-success">Terima</button>
                                                </form>
                                                <form action="{{ route('pelamar.tolak', $pelamaritem) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-danger">Tolak</button>
                                                </form>
                                            @else
                                            <p>Status: {{ $pelamaritem->status }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        </div>
                    </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@endsection