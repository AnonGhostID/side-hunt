<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\KriteriaJob;
use App\Models\Pekerjaan;
use App\Models\Pelamar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class PekerjaanController extends Controller
{
    public function index()
    {
        $all = Pekerjaan::orderBy('nama', 'asc')->get();
        $all_rekomendasi = Pekerjaan::all();
        // dd($all[0]);
        $match = ($this->getThe20ies());
        $active_navbar = 'Cari Pekerjaan';
        $nama_halaman = 'Cari Pekerjaan';


        return view("Dewa.pekerjaan.cari", compact("active_navbar", 'nama_halaman', 'all', 'match', 'all_rekomendasi'));
    }


    public function create()
    {
        $active_navbar = 'Beri Lowongan Kerja';
        $nama_halaman = 'Tambah Pekerjaan';
        $path = public_path('Dewa/json/dummy_skills.json');
        $kriteria = json_decode(file_get_contents($path), true)['skills'];
        return view("Dewa.pekerjaan.add", compact("active_navbar", 'nama_halaman', 'kriteria'));
    }
    public function Daftar_Pelamar($id)
    {
        $pekerjaan =Pekerjaan::where('id', $id)
        ->where('pembuat', session('account')['id'])
        ->first();
        $back = null;
        if($id!='all'&&$pekerjaan==null){
            return Redirect()->back()->with('fail',['Akses Ditolak','Pekerjaan Ini tidak termasuk Milik Anda!']);
        }
        $active_navbar = 'Pekerjaan';
        $nama_halaman = 'Daftar Pelamar Pekerjaan';
        $pelamars = Pekerjaan::join('pelamars', 'pekerjaans.id', '=', 'pelamars.job_id')
            ->join('users', 'pelamars.user_id', '=', 'users.id')
            ->where('pekerjaans.pembuat', session('account')['id'])
            ->where('pelamars.is_delete', false);
        if ($id != 'all') {
            $pelamars->where('pelamars.job_id', $id);
        }

        $pelamars = $pelamars->select(
            'pekerjaans.*',
            'pelamars.created_at as daftar',
            'users.nama as nama_pelamar',
            'users.preferensi_user',
            'pelamars.status as status_job',
            'pelamars.id as id_pelamars',
            'pelamars.user_id as id_pelamar',
            'pelamars.link_Interview as link',
            'pelamars.jadwal_interview as jadwal',
            'pelamars.alasan as alasan',
        )->get();

        foreach($pelamars as $pelamar){
            $skills = [];
            if (!empty($pelamar->preferensi_user)) {
                $preferensi = json_decode($pelamar->preferensi_user);
                if (!empty($preferensi->kriteria)) {
                    foreach($preferensi->kriteria as $skill){
                        if($skill != ""){
                            $skills[] = $skill;
                        }
                    }
                }
            }
            $pelamar->preferensi_user=(implode(", ", $skills));;
        }

        $direction = $id;
        // dd($pelamars);
        // dd($pekerjaan);
        // dd($back);
            return view('Dewa.pekerjaan.daftar-pelamar', compact("active_navbar", 'nama_halaman', 'pelamars','pekerjaan','direction'));

    }

    public function view_pekerjaan($id)
    {
        $active_navbar = 'Cari Pekerjaan';
        $nama_halaman = 'Lihat Pekerjaan';
        $data_pekerjaan = Pekerjaan::where('id', $id)->get();
        $kriteria = explode(",", $data_pekerjaan[0]['kriteria']);
        $data_pekerjaan[0]['kriteria'] = $kriteria;
        if (session()->has('account')) {
            $history = Pelamar::where('user_id', session('account')['id'])
                ->where('job_id', $id)
                ->get();
            return view('Dewa.pekerjaan.lihat', compact("active_navbar", 'nama_halaman', 'data_pekerjaan', 'history'));
        } else {
            return view('Dewa.pekerjaan.lihat', compact("active_navbar", 'nama_halaman', 'data_pekerjaan'));
        }
    }

    public function store(Request $request)
    {
        // dd($request);
        $request->validate([
            'nama' => 'required|string|max:60',
            'min_gaji' => 'required|integer|max:99999999999999999999', // maksimal 20 digit
            'max_gaji' => 'required|integer|max:99999999999999999999', // maksimal 20 digit
            'max_pekerja' => 'required|integer|max:9999999999', // maksimal 10 digit
            'deskripsi' => 'required|string|max:5000',
            'alamat' => 'required|string|max:100',
            'petunjuk_alamat' => 'required|string|max:100',
            'koordinat' => 'required|string',
            'start_job' => 'required|date|after_or_equal:today',
            'end_job' => 'required|date|after_or_equal:start_job',
            'deadline_job' => 'nullable|date|before_or_equal:end_job',
            'kriteria' => 'required|array|min:3',
            'foto_job' => 'nullable|file|mimes:jpg,jpeg,png,img,image/jpeg, image/png|max:5120',
        ], [
            'nama.required' => 'Nama wajib diisi.',
            'nama.string' => 'Nama harus berupa teks.',
            'nama.max' => 'Nama maksimal 60 karakter.',

            'min_gaji.required' => 'Gaji minimum wajib diisi.',
            'min_gaji.integer' => 'Gaji minimum harus berupa angka bulat.',
            'min_gaji.max' => 'Gaji minimum maksimal 20 digit angka.',

            'max_gaji.required' => 'Gaji maksimum wajib diisi.',
            'max_gaji.integer' => 'Gaji maksimum harus berupa angka bulat.',
            'max_gaji.max' => 'Gaji maksimum maksimal 20 digit angka.',

            'max_pekerja.required' => 'Pekerja diterima wajib diisi.',
            'max_pekerja.integer' => 'Pekerja diterima harus berupa angka bulat.',
            'max_pekerja.max' => 'Pekerja diterima maksimal 10 digit angka.',

            'deskripsi.required' => 'Deskripsi wajib diisi.',
            'deskripsi.string' => 'Deskripsi harus berupa teks.',
            'deskripsi.max' => 'Deskripsi maksimal 3000 karakter.',

            'alamat.required' => 'Alamat wajib diisi.',
            'alamat.string' => 'Alamat harus berupa teks.',
            'alamat.max' => 'Alamat maksimal 100 karakter.',

            'petunjuk_alamat.required' => 'Petunjuk alamat wajib diisi.',
            'petunjuk_alamat.string' => 'Petunjuk alamat harus berupa teks.',
            'petunjuk_alamat.max' => 'Petunjuk alamat maksimal 100 karakter.',

            'koordinat.required' => 'Lokasi Alamat wajib diisi.',

            'start_job.required' => 'Tanggal & waktu mulai wajib diisi.',
            'start_job.date' => 'Format tanggal & waktu mulai tidak valid.',
            'start_job.after_or_equal' => 'Tanggal & waktu mulai tidak boleh sebelum hari ini.',

            'end_job.required' => 'Tanggal & waktu selesai wajib diisi.',
            'end_job.date' => 'Format tanggal & waktu selesai tidak valid.',
            'end_job.after_or_equal' => 'Waktu selesai tidak boleh lebih awal dari waktu mulai.',

            'deadline_job.date' => 'Format deadline tidak valid.',
            'deadline_job.before_or_equal' => 'Deadline harus sebelum atau sama dengan waktu berakhirnya kerja.',

            'foto_job.mimes' => 'Foto harus berformat JPG/PNG/JPEG/IMG/IMAGE.',
            'foto_job.max' => 'Ukuran foto maksimal 5MB.',


            'kriteria.required' => 'Minimal masukkan 3 kriteria.',
            'kriteria.array' => 'Format kriteria tidak valid.',
            'kriteria.min' => 'Minimal masukkan 3 kriteria.',



        ]);
        if ($request->koordinat != null) {
            $request['pembuat'] = session('account')['id'];
            $kriteria = implode(",", $request['kriteria']);
            $request['kriteria'] = $kriteria;

            $data = collect($request->all())->mapWithKeys(function ($value, $key) {
                return [$key => is_array($value) ? $value : (string)$value];
            })->toArray();
            if ($request['deadline_job'] == null) {
                $data['deadline_job'] = null;
            } else {
                $data['deadline_job'] = $request['deadline_job'];
            }

            if ($request->hasFile('foto_job')) {
                $file = $request->file('foto_job');
                $filename = $request->nama . "." . $file->getClientOriginalExtension();
                $path = 'storage/'.$file->storeAs('job', $filename, 'public');
                $data['foto_job'] = $path;
            }
            if (Pekerjaan::create($data)) {
                return redirect()->back()->with('success', ['Berhasil', 'Pekerjaan Berhasil didaftarkan']);
            };
        } else {
            return redirect()->back()->with('fail', ['Gagal Disimpan!', 'Pin belum diset di map']);
        }
    }


    public function terima(Pelamar $pelamar)
    {
        $pelamar->update(['status' => 'diterima']);
        return redirect()->back();
    }

    public function tolak(Pelamar $pelamar)
    {
        $pelamar->update(['status' => 'ditolak']);
        return redirect()->back();
    }

    public function Daftar_Lamaran()
    {
        $active_navbar = 'Daftar Lamaran';
        $nama_halaman = 'Daftar Lamaran';
        $pekerjaans = Pekerjaan::join('pelamars', 'pekerjaans.id', '=', 'pelamars.job_id')
            ->where('pelamars.user_id', session('account')['id'])
            ->select('pekerjaans.*', 'pelamars.*') // pilih kolom sesuai kebutuhanmu
            ->get();
        // dd($pekerjaans);
        // $Pekerjaans = Pelamar::where('user_id', session('account')['id'])->get();
        return view('Dewa.pekerjaan.dilamar', compact('active_navbar', 'nama_halaman', 'pekerjaans'));
    }

    public function Daftar_Pekerjaan()
    {
        $active_navbar = 'Pekerjaan';
        $nama_halaman = 'Lowongan Terdaftar';


        $pekerjaans = Pekerjaan::select('pekerjaans.id', 'pekerjaans.nama', 'pekerjaans.alamat', 'pekerjaans.created_at', 'pekerjaans.latitude', 'pekerjaans.longitude', DB::raw('COUNT(pelamars.status) as total_pelamar'))
            ->join('users', 'pekerjaans.pembuat', '=', 'users.id')
            ->leftJoin('pelamars', 'pekerjaans.id', '=', 'pelamars.job_id')
            ->where('pekerjaans.pembuat', session('account')['id'])
            ->groupBy([
                'pekerjaans.id',
                'pekerjaans.nama',
                'pekerjaans.alamat',
                'pekerjaans.created_at',
                'pekerjaans.latitude',
                'pekerjaans.longitude'
            ])
            ->get();
        // dd($pekerjaans);
        return view('Dewa.pekerjaan.dibuat', compact('active_navbar', 'nama_halaman', 'pekerjaans'));
    }

    function cosineSimilarityPercent($text1, $text2)
    {
        // Normalize: lowercase and remove extra spaces
        $text1 = strtolower(trim($text1));
        $text2 = strtolower(trim($text2));

        // Create n-grams
        $ngrams1 = $this->getCharacterNgrams($text1, 2);
        $ngrams2 = $this->getCharacterNgrams($text2, 2);

        // Count frequency of each n-gram
        $freq1 = array_count_values($ngrams1);
        $freq2 = array_count_values($ngrams2);

        // Union of keys
        $allKeys = array_unique(array_merge(array_keys($freq1), array_keys($freq2)));

        // Vectorize both n-gram sets
        $vec1 = [];
        $vec2 = [];

        foreach ($allKeys as $key) {
            $vec1[] = isset($freq1[$key]) ? $freq1[$key] : 0;
            $vec2[] = isset($freq2[$key]) ? $freq2[$key] : 0;
        }

        // Cosine similarity
        $dotProduct = 0;
        $mag1 = 0;
        $mag2 = 0;

        for ($i = 0; $i < count($vec1); $i++) {
            $dotProduct += $vec1[$i] * $vec2[$i];
            $mag1 += pow($vec1[$i], 2);
            $mag2 += pow($vec2[$i], 2);
        }

        if ($mag1 == 0 || $mag2 == 0) return 0.0;

        $similarity = $dotProduct / (sqrt($mag1) * sqrt($mag2));
        return round($similarity * 100, 2); // Convert to percent
    }

    function ForTest(Request $request)
    {
        // $this->runTests();
        return $this->cosineSimilarity($request->text1, $request->text2);
    }

    public function cosineSimilarity($text1, $text2)
    {
        $tokens1 = $this->preprocess($text1);
        $tokens2 = $this->preprocess($text2);

        $vocab = $this->fuzzyUnion($tokens1, $tokens2);
        $vec1 = array_fill_keys($vocab, 0);
        $vec2 = array_fill_keys($vocab, 0);

        foreach ($tokens1 as $t1) {
            $match = $this->fuzzyMatch($t1, $vocab);
            if ($match) $vec1[$match]++;
        }

        foreach ($tokens2 as $t2) {
            $match = $this->fuzzyMatch($t2, $vocab);
            if ($match) $vec2[$match]++;
        }

        $dot = 0;
        $mag1 = 0;
        $mag2 = 0;
        foreach ($vocab as $term) {
            $dot += $vec1[$term] * $vec2[$term];
            $mag1 += pow($vec1[$term], 2);
            $mag2 += pow($vec2[$term], 2);
        }

        if ($mag1 == 0 || $mag2 == 0) return 0.0;
        return round(($dot / (sqrt($mag1) * sqrt($mag2))) * 100, 2);
    }

    private function preprocess($text)
    {
        $text = strtolower($text);
        $text = preg_replace('/[^a-z\s]/', '', $text);
        $words = preg_split('/\s+/', $text);

        $stopwords = ['aku', 'saya', 'yang', 'untuk', 'dengan', 'secara', 'atau', 'ke', 'dari', 'bisa', 'adalah', 'itu', 'ini', 'dan', 'biasanya', 'sebagai'];

        return array_values(array_filter(array_diff($words, $stopwords)));
    }

    private function fuzzyMatch($token, $vocab)
    {
        foreach ($vocab as $v) {
            similar_text($token, $v, $percent);
            if ($percent >= 80.0) return $v;
        }
        return null;
    }

    private function fuzzyUnion($tokens1, $tokens2)
    {
        $combined = array_merge($tokens1, $tokens2);
        $result = [];

        foreach ($combined as $token) {
            if (!$this->fuzzyMatch($token, $result)) {
                $result[] = $token;
            }
        }

        return $result;
    }

    // Tambahkan fungsi ini untuk menjalankan 3 pengujian langsung
    public function runTests()
    {
        echo "1. 'program' vs 'programmer': " . $this->cosineSimilarity("program", "programmer") . "%\n\n";

        echo "2. kerja vs kurir: " . $this->cosineSimilarity(
            "Aku bisa coding, biasanya aku program sesuatu",
            "Kurir Barang Mengantarkan barang dari penjual ke pembeli."
        ) . "%\n\n";

        echo "3. kerja vs freelance programmer: " . $this->cosineSimilarity(
            "Aku bisa coding, biasanya aku program sesuatu",
            "Programmer Lepas Mengembangkan aplikasi atau website secara freelance."
        ) . "%\n\n";
    }

    public function getThe20ies()
    {
        $result = [];
        $all = Pekerjaan::all();
        $filtered = [];
        if (session()->has('account')) { //apabila sudah login

            foreach ($all as $kerja) {
                //cek hasil CSP
                // dd(
                //     json_decode(session('account')->preferensi_user)->deskripsi,
                //     json_decode($all[6])->nama . " " . json_decode($all[6])->deskripsi
                // );
                // $decimal = $this->cosineSimilarity(
                //     json_encode(json_decode($kerja)->nama . " " . json_decode($kerja)->deskripsi),
                //     json_encode(json_decode(session('account')->preferensi_user)->deskripsi)
                // );
                $decimal = $this->cosineSimilarity(
                    json_encode(json_decode($kerja)->nama . " " . json_decode($kerja)->deskripsi),
                    json_encode(json_decode(session('account')->preferensi_user)->deskripsi . " " . implode(",", json_decode(session('account')->preferensi_user)->kriteria))
                );
                // echo "<br> ".json_decode(session('account')->preferensi_user)->deskripsi."n dg <br>".json_decode($kerja)->nama . " <br>" . json_decode($kerja)->deskripsi." Hasilnya: ".$decimal."<br>";

                // echo "<br>".json_decode($kerja)->id." ".json_decode(session('account')->preferensi_user)->deskripsi. json_decode($kerja)->nama." Hasilnya: ".$decimal."<br>";
                //membatasi jumlah pekerjaan disarankan
                if (count($result) < 20) {
                    $result[$kerja->id] = $decimal;
                }
                //jika sudah mencapai jumlah, maka cut yg paling kecil didalam array
                else {
                    //angka berapa yg paling kecil dalam array
                    $minimal_value = min($result);
                    //cari keynya dari angka paling kecil
                    $key_minimal_value = array_search($minimal_value, $result);

                    //cek jika hasil CSP lebih besar dr minimal value maka ganti
                    if ($decimal > $minimal_value) {
                        unset($result[$key_minimal_value]); // hapus yang terkecil
                        $result[$kerja->id] = $decimal; //ganti isi array
                    }
                }
            }
            $filtered = array_filter($result, function ($value) {
                return $value != 0.0;
            });
            (arsort($filtered));
        } else {
            // $result = $all;
            // dd($result,'belum');
        }
        // dd($filtered);

        return ($filtered);
    }

    function hitungJarak($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // kilometer

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $distance = $earthRadius * $c;

        return $distance;
    }

    function is_own_pelamar($idPelamar){
        $job = Pekerjaan::findOrFail($idPelamar);
        return ($job->pembuat==session('account')['id']);
    }
}
