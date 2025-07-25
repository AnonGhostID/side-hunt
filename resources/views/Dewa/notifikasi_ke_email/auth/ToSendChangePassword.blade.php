@extends('Dewa.Base.Basic-page')

@section('css')
<!-- <link rel="stylesheet" href="{{asset('css/login.css')}}"> -->
<link rel="stylesheet" href="{{ auto_asset('Dewa/css/login.css') }}">
@endsection

@section('peta')
@endsection

@section('content')
<div class="container login">
    <h5 class="text-center">Kami akan mengirim link untuk penggantian password ke email</h5>

    <form action="/send_change_password" method="POST" class="w-100">

        @csrf
        <!-- <label for="">Kode Verifikasi*</label> -->
        <input type="text" name="email" maxlength="60" placeholder="Masukkan Email"
            class="w-100" value="{{ old('email') }}">
        @error('verify_code')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <div class="submit-button mt-5">
            <button type="submit" class="btn btn-warning">Kirim Link</button>
        </div>

    </form>
</div>
@endsection

@section('script')
@endsection