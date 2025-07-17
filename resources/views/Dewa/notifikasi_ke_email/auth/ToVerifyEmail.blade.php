@extends('Dewa.Base.Basic-page')

@section('css')
<!-- <link rel="stylesheet" href="{{asset('css/login.css')}}"> -->
<link rel="stylesheet" href="{{ auto_asset('Dewa/css/login.css') }}">
@endsection

@section('peta')
@endsection

@section('content')
<div class="container login">
    <h1>Verifikasi Email Anda</h1>

    <form action="/verify_email" method="POST" class="w-100">

        @csrf

        <!-- <label for="">Kode Verifikasi*</label> -->
        <input type="text" name="email" maxlength="60" placeholder="Masukkan Email"
            class="w-100" value="{{ old('email') }}">
        <input type="text" name="kode_verifikasi" maxlength="10" placeholder="Masukkan Kode Verifikasi"
            class="w-100 @error('verify_code') is-invalid @enderror" value="{{ old('verify_code') }}">
        @error('verify_code')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <div class="submit-button mt-5">
            <button type="submit" class="btn btn-warning">Submit</button>
        </div>

    </form>
</div>
@endsection

@section('script')
@endsection