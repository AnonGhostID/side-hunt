@extends('Dewa.Base.Basic-page')

@section('css')
<style>
.job {
    background-color: #CDCDCD !important;
}
.table-bg {
    background: white;
    border-radius: 24px;
    box-shadow: 0 4px 32px #00000012;
    padding: 2rem;
}
</style>
@endsection

@section('content')
<div class="container py-5">
    <div class="table-bg">
        <div class="text-center flex-column justify-content-center align-items-center w-100 flex-grow-1 mb-4 fw-bold" style="color: #2c2c2c;">
            <h3>Daftar Pelamar</h3>
        </div>
        
        <div class="table-responsive" style="min-height: 45vh;">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th class="text-center">Tanggal Daftar</th>
                        <th class="text-center">Pekerjaan yang dilamar</th>
                        <th class="text-center">Nama Pelamar</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($pelamars) && count($pelamars) > 0)
                        @foreach($pelamars as $pelamar)
                        <tr>
                            <td class="text-center">{{ isset($pelamar->created_at) ? $pelamar->created_at->format('d/m/Y') : 'N/A' }}</td>
                            <td class="text-center">{{ isset($pelamar->nama_pekerjaan) ? $pelamar->nama_pekerjaan : 'N/A' }}</td>
                            <td class="text-center">{{ isset($pelamar->nama_pelamar) ? $pelamar->nama_pelamar : 'N/A' }}</td>
                            <td class="text-center">
                                <span class="badge bg-info">{{ isset($pelamar->status) ? $pelamar->status : 'Pending' }}</span>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-success btn-sm">Terima</button>
                                <button class="btn btn-danger btn-sm">Tolak</button>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="text-center">Belum ada pelamar</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection