@extends('Dewa.Base.Basic-page')

@section('css')

<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&amp;display=swap" rel="stylesheet">

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

<style>
    .dropdown-container {
        overflow-y: hidden;
        position: relative;
        width: 100%;
        max-width: 500px;
    }

    .dropdown-list {
        max-height: 250px;
        overflow-y: auto;
        border: 1px solid #dee2e6;
        border-radius: 0.5rem;
        background-color: #fff;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease-in-out;
    }

    .dropdown-list::-webkit-scrollbar {
        width: 8px;
    }

    .dropdown-list::-webkit-scrollbar-thumb {
        background-color: #ced4da;
        border-radius: 4px;
    }

    .dropdown-list::-webkit-scrollbar-thumb:hover {
        background-color: #adb5bd;
    }


    .form-container {
        max-width: 720px;
        margin: auto;
    }

    .form-label {
        font-weight: 600;
        color: #333;
    }

    .form-select {
        border-radius: 0.5rem;
    }

    .form-section {
        margin-bottom: 1.75rem;
    }

    .form-title {
        font-size: 1.75rem;
        font-weight: bold;
        color: #0d6efd;
    }

    .dropdown-label {
        width: 100%;
        min-width: 100%;
        max-width: 100%;
    }
</style>
@endsection

@section('content')
<div class="container py-5">
    <div class="form-container bg-white p-5 rounded shadow-sm">
        <h2 class="text-center form-title mb-4">📝 Formulir Kemampuan dan Preferensi Kerja</h2>

        {{-- Flash Message jika ada --}}
        @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <form method="POST" action="/user/preferensi/save">

            @csrf
            @php
            $questions = [
            

            ];
            @endphp

            <div class="form-group d-flex flex-column">


                <label for="" class="form-label"> Deskripsikan Dirimu</label>
                <textarea name="deskripsi" id="" class="shadow-sm form-control mb-3"
                    placeholder="Beri gambaran kepada kami anda orang yang seperti apa, bisa kebiasaan, pekerjaan, dan hal yang disukai"></textarea>
            </div>

            @foreach ($questions as $name => $q)
            <div class="form-section">
                <label for="{{ $name }}" class="form-label">{{ $q['label'] }}</label>
                <select id="{{ $name }}" name="{{ $name }}"
                    class="form-select shadow-sm @error($name) is-invalid @enderror" data-style="btn-outline-primary"
                    data-width="100%">
                    <option value="-" selected>Pilih jawaban...</option>
                    @foreach ($q['options'] as $key => $desc)
                    <option value="{{ $key.' '.$desc }}" {{old($name)==(string)$key?'selected':''}}>
                        {{ $desc }}
                    </option>
                    @endforeach
                </select>
                @error($name)
                <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
            @endforeach

            <div class="form-group mt-2">
                <div class="justify-content-center w-100 m-0">
                    <div class="w-100 d-flex justify-content-center p-0 m-0 align-items-center">
                        <div class="w-100" style="min-width: 100%; height: 60vh; min-height: 60vh; max-height: 60vh;">
                            <div class="dropdown-button noselect w-100 mb-2">
                                <label class="form-label fw-semibold text-dark">Kriteria Pekerjaan (pilih minimal
                                    3)</label>
                            </div>
                            <div class="dropdown-list h-100 p-3 overflow-y-hidden" style="height: 50vh; min-height: 50vh; max-height: 50vh;">
                                <!-- Input manual -->
                                <input type="text" name="kriteria_manual" class="form-control mb-2"
                                    placeholder="Tulis disini jika tidak ada, format: text, text, text">

                                <!-- Input search -->
                                <input type="text" class="form-control mb-3" oninput="SearchBox(this)"
                                    placeholder="Cari Kriteria..." id="searchKriteria">

                                <!-- Tombol Reset -->
                                <div class="text-end mb-2">
                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                        onclick="resetCheckboxes()">
                                        Reset Checkbox
                                    </button>
                                </div>

                                <!-- List kriteria -->
                                <ul class="list-unstyled flex-grow-1 mb-0 overflow-y-scroll"
                                    style="max-height: 25vh;">
                                    @foreach($kriteria as $k)
                                    <li class="mb-2 li_container">
                                        <div class="form-check">
                                            <input class="form-check-input kriteriaInput" type="checkbox" name="kriteria{{$k}}"
                                                value="{{ $k}}" id="kriteria{{$k}}">
                                            <label class="form-check-label" for="kriteria{{ $k }}">
                                                {{ $k }}
                                            </label>
                                        </div>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>

                        </div>
                    </div>
                </div>


                @error('kriteria')
                <div class="invalid-feedback">{{ $message }}</div>

                @enderror
            </div>


            <div class="text-center mt-5">
                <button type="submit" class="btn btn-primary px-5 py-2">🚀 Kirim Jawaban</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
<script>
    function resetCheckboxes() {
        const checkboxes = document.querySelectorAll('.form-check-input');
        checkboxes.forEach(cb => cb.checked = false);
    }

    function SearchBox(elemen) {
        let all_jobs = document.querySelectorAll('.kriteriaInput')
        all_jobs.forEach(element => {
            console.log('apani : '+element.value.toLowerCase().includes(elemen.value.toLowerCase()),element.value.toLowerCase(),elemen.value.toLowerCase())
            if (element.value.toLowerCase().includes(elemen.value.toLowerCase())) {
                let li = element.closest('.li_container')
                li.style.display = "flex"
            }
            else {
                let li = element.closest('.li_container')
                li.style.display = "none"
            }
        });

    }
</script>

@endsection