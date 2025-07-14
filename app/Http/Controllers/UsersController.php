<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\ResetPassword;
use App\Models\Pelamar;
use App\Models\Users;
use App\Models\Pekerjaan;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Ui\Presets\React;
use App\Mail\VerificationCodeMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Arr;

// use User;

class UsersController extends Controller
{
    //Untuk non-admin
    // public function pelamaran()
    // {
    //     $user = auth()->user();
    //     $lamaran = Pelamar::with('sidejob')->where("user_id", $user->id)->get();

    //     return view('users.lamaran', compact('lamaran'));
    // }

    public function show($id)
    {
        // $user = Users::findOrFail($id);
        // $jobs = SideJob::where('pembuat', $id)->get(); // Fetch jobs created by the user
        $active_navbar = 'Profile';
        $nama_halaman = 'Profile';
        $kode_Nomor = json_decode(file_get_contents(public_path('json/dial_country.json')), TRUE);
        return view('Dewa.need_auth.profile', compact('active_navbar', 'nama_halaman', 'kode_Nomor'));

        // return view('users.profile', compact('user', 'jobs'));
    }


    //Untuk admin
    public function showAdmin($id)
    {
        $user = Users::findOrFail($id);
        $jobs = Pekerjaan::where('pembuat', $id)->get();
        return view('admin.users.profile', compact('user', 'jobs'));
    }

    public function edit(string $id)
    {
        $user = Users::findorfail($id);

        return view('admin.users.edit', compact('user'));
    }

    //Cara code untuk ubah data
    public function update(Request $request, $id)
    {
        // dd($request);
        $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'alamat' => ['required', 'string', 'max:255'],
            'telpon' => ['required', 'string', 'numeric'],
            // 'password' => ['string', 'min:8'],
        ]);
        $user = Users::findorfail($id);
        $user->update([
            'nama' => $request['nama'],
            'email' => $request['email'],
            'alamat' => $request['alamat'],
            'telpon' => $request['telpon'],
            // 'password' => Hash::make($request['password']),
        ]);

        return redirect()->route('admin.index')->with(['success' => 'Data berhasil diubah']);
    }

    public function destroy($id)
    {
        $user = Users::findorfail($id);
        $user->delete();

        return redirect()->back()->with(['success' => 'Data berhasil dihapus']);
    }

    public function forget_password()
    {
        $active_navbar = 'Ganti Password';
        $nama_halaman = 'Ganti Password';
        return view('Dewa.notifikasi_ke_email.auth.ToSendChangePassword', compact('active_navbar', 'nama_halaman'));
    }

    public function send_code_change_password(Request $request)
    {
        // dd($request->email);
        $cek = $this->cekExistEmail($request->email);
        // dd($cek);
        if (!($cek != false)) {
            $verificationCode = $this->sendChangePassword($request->email);
            if ($verificationCode != false) {
                $user = Users::where('email', $request->email)->first();
                $user->VerificationCode = $verificationCode;
                $user->save();
                // dd($user);
                return redirect()->back()->with('success', ['Berhasil', 'Link untuk ganti password berhasil dikirim ke email']);
            } else {
                return redirect()->back()->with('fail', ['Gagal', 'Ada Masalah, mungkin bisa coba lain waktu']);
            }
        } else {
            return redirect()->back()->with('fail', ['Gagal', 'Tidak ada akun yg terdaftar atas email tersebut']);
        }
    }

    public function view_Reset_Password($token, $email)
    {
        $user = $this->cekExistEmail($email);
        if (!($user != false)) {
            $user = Users::where('email', $email)->first();
            if ($user->VerificationCode == $token) {
                $active_navbar = 'Reset Password';
                $nama_halaman = 'Reset Password';
                $emails = $email;

                return view('Dewa.notifikasi_ke_email.auth.ResetPassword', compact('active_navbar', 'nama_halaman', 'emails'));
            } else {
                return redirect()->back()->with('fail', ['Gagal', 'Kode Sudah Tidak aktif atau Kode sudah terpakai']);
            }
        } else {
            return redirect()->back()->with('fail', ['Gagal', 'tidak ada akun yang terdaftar atas email ini']);
        }
    }

    //NEW
    public function Login_Account(Request $req)
    {
        // dd('.asuk');
        $credentials = $req->validate([
            'email' => 'required|email',
            'password' => 'required'
        ],[

            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',

            'password.required' => 'Password wajib diisi.',
        ]);

        $user = Users::where('email', $req->email)->first();

        // dd(Hash::check($req->password,$user->password));
        if ($user) {
            if ($user && Hash::check($req->password, $user->password)) {
                if ($user->email_verified_at != null) {
                    if($user->is_ban!=true){
                        if (Auth::attempt($credentials)) {
                            session(['account' => $user]);
                            // dd(session('account'));
    
                            // dd('masuk');
                            if (session('account')->preferensi_user == null && session('account')['role']!='admin') {
                                return redirect('/question-new-user')->with('success', ['Izin Mengganggu waktunya sebentar', 'Isi Data Terlebih Dahulu']);
                            } else {
                                return redirect('/Index')->with('success', ['Sukses', 'Login Berhasil']);
                            }
                        }
                    }
                    else{
                        return redirect()->back()->with('fail',['Login Gagal', 'Akun anda sedang ditangguhkan']);
                    }
                } else {
                    return redirect('/Verify-Email')->with([
                        'fail' => ['Login Gagal', 'Email belum diverifikasi, silahkan buka email anda kemudian lakukan verifikasi terlebih dahulu, lalu login kembali'],
                    ])->withInput();
                }
            } else {
                return back()->with([
                    'fail' => ['Login Gagal', 'Password salah'],
                ])->withInput();
            }
        } else {
            return back()->with([
                'fail' => ['Login Gagal', 'Tidak ada akun yang terdaftar dengan email ini'],
            ])->withInput();
        }
    }
    public function logout()
    {
        if (session('account') != null) {
            Auth::logout();
            session()->invalidate();
            session()->regenerateToken();
            return redirect('/Login')->with('success', ['Berhasil', 'Logout Berhasil']);
        } else {
            $allert = 'Logout Gagal|Anda tidak dalam sesi yang aktif';
            return back()->with('alert', $allert);
        }
    }

    public function create(Request $req)
    {
        // dd(dd($req));

        $req->validate([

            'nama-depan' => 'required|string|max:60',
            'nama-belakang' => 'required|string|max:60',
            'email' => [
                'required',
                'max:100',
                function ($attribute, $value, $fail) {
                    if (!$this->cekExistEmail($value)) {
                        $fail('Gunakan email yang belum pernah terdaftar.');
                    }
                },
            ],
            'password' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!$this->cek_password($value)) {
                        $fail('Password tidak memenuhi kriteria keamanan');
                    }
                },
            ],
            'password-retype' => 'required|same:password',
        ], [
            'nama-depan.required' => 'Nama wajib diisi.',
            'nama-depan.string' => 'Nama harus berupa teks.',
            'nama-depan.max' => 'Nama maksimal 60 karakter.',

            'nama-belakang.required' => 'Nama wajib diisi.',
            'nama-belakang.string' => 'Nama harus berupa teks.',
            'nama-belakang.max' => 'Nama maksimal 60 karakter.',

            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email maksimal 100 karakter.',

            'password.required' => 'Password wajib diisi.',
            'password-retype.required' => 'Verifikasi Password wajib diisi.',
            'password-retype.same' => 'Verifikasi Password harus sama dengan password.',
        ]);
        $data = collect($req->all())->mapWithKeys(function ($value, $key) {
            return [$key => is_array($value) ? $value : (string)$value];
        })->toArray();
        // dd('masuk');
        $data['password'] = Hash::make($req->password);
        $data['nama'] = $req['nama-depan'] . ' ' . $req['nama-belakang'];
        // dd($req);
        $data['role'] = $req['role']=="1"?"mitra":"user";
        // dd($data, $req);
        $save_user = Users::create($data);
        if ($save_user) {
            $verificationCode = $this->sendVerificationCode($data['email']);
            if ($verificationCode != false) {
                $save_user->VerificationCode = $verificationCode;
                $save_user->save();
                return redirect('/Verify-Email')->with('success', ['Registrasi Berhasil', 'Akun anda berhasil didaftarkan, Jangan lupa Cek Email untuk verifikasi lalu silahkan Login']);
            } else {
                return redirect()->back()->with('fail', ['Registrasi Gagal', 'Email tidak ditemukan']);
            }
        } else {
            return redirect()->back()->with('fail', ['Registrasi Gagal', 'Maaf Terjadi Kesalahan dalam penyimpanan data']);
        }
    }

    function sendVerificationCode($email)
    {
        $code = rand(100000, 999999);
        if (Mail::to($email)->send(new VerificationCodeMail($code))) {
            return $code;
        } else {
            dd('email eror');
            return 'email eror';
        }
    }

    function sendChangePassword($email)
    {
        $code = rand(100000, 999999);
        if (Mail::to($email)->send(new ResetPassword($code, $email))) {
            return $code;
        } else {
            return false;
        }
    }

    function verify_view()
    {
        $active_navbar = 'Verifikasi Email';
        $nama_halaman = 'Verifikasi Email';

        return view('Dewa.notifikasi_ke_email.auth.ToVerifyEmail', compact('active_navbar', 'nama_halaman'));
    }

    function submit_verify_email(Request $req)
    {
        $req->validate([

            'kode_verifikasi' => 'required|string|max:10',
            'email' => 'required|string|max:60',
        ], [
            'kode_verifikasi.required' => 'Kode Verifikasi Wajib diisi.',
            'kode_verifikasi.string' => 'Nama harus berupa Angka.',
            'kode_verifikasi.max' => 'Nama maksimal 10 karakter.',

            'email.required' => 'Kode Verifikasi Wajib diisi.',
            'email.string' => 'Nama harus berupa string.',
            'email.max' => 'Nama maksimal 10 karakter.',
        ]);


        $user = Users::where('email', $req->email)->first();
        if ($user->VerificationCode == $req->kode_verifikasi) {
            $user->email_verified_at = now();
            $user->VerificationCode = null;
            $user->save();
            return redirect('/Login')->with('success', ['Email berhasil diverifikasi', 'silahkan login']);
        } else {
            return redirect()->back()->with('fail', ['Kode Verifikasi Salah', 'silahkan input ulang']);
        }
    }



    function cekExistEmail($emailUntukDicek)
    {
        $user = Users::where('email', $emailUntukDicek)->first();
        // dd($user);
        return ($user) ? false : true;
    }


    function cek_password($password)
    {
        return strlen($password) >= 8 &&
            preg_match('/[0-9]/', $password) &&
            preg_match('/[A-Z]/', $password) &&
            preg_match('/[a-z]/', $password);
    }

    function Profile()
    {
        $jobs = Pekerjaan::latest()->paginate(10);
        $peta = Pekerjaan::all();
        $active_navbar = 'Profile';
        $nama_halaman = 'Profile';
        $kode_Nomor = json_decode(file_get_contents(public_path('Dewa/json/dial_country.json')), TRUE);
        // dD($kode_Nomor);


        // return view('pekerjaan.list', compact('Pekerjaan'));

        return view('Dewa.need_auth.profile', compact('jobs', 'peta', 'active_navbar', 'nama_halaman', 'kode_Nomor'));
    }

    function Profile_Edit(Request $req)
    {
        $req->validate([
            'dial_code' => 'required|string|max:10',
            'nama'      => ['required', 'string', 'max:150'],
            'alamat'    => ['required', 'string', 'max:255'],
            'telpon'    => ['required', 'string'],
        ], [
            // dial_code
            'dial_code.required' => 'Kode Negara wajib diisi.',
            'dial_code.string'   => 'Kode Negara harus berupa teks.',
            'dial_code.max'      => 'Kode Negara maksimal 10 karakter.',

            // nama
            'nama.required' => 'Nama wajib diisi.',
            'nama.string'   => 'Nama harus berupa teks.',
            'nama.max'      => 'Nama maksimal 150 karakter.',


            // alamat
            'alamat.required' => 'Alamat wajib diisi.',
            'alamat.string'   => 'Alamat harus berupa teks.',
            'alamat.max'      => 'Alamat maksimal 255 karakter.',

            // telpon
            'telpon.required' => 'Nomor Telepon wajib diisi.',
            'telpon.string'   => 'Nomor Telepon harus berupa teks.',
        ]);

        $user = Users::findOrFail(session('account')['id']);
        $user->nama = $req->nama;
        $user->alamat = $req->alamat;
        $user->telpon = "(" . $req->dial_code . ") " . $req->telpon;
        if ($user->save()) {
            session(['account' => $user]);

            return redirect()->back()->with('success', ['Berhasil', 'Ubah Data Profile Berhasil']);
        }
        // dd($user);
    }

    function save_preverensi(Request $request)
    {
        // dd($request);
        $parameters = $request->all();

        $kriteria = [];

        // Loop dan ekstrak semua kriteria
        foreach ($parameters as $key => $value) {
            if (strpos($key, 'kriteria_manual') === 0) {
                $all = explode(",", $value);
                // dd($all);
                foreach ($all as $new_kriteria) {
                    $kriteria[] = trim($new_kriteria);
                }
                unset($parameters[$key]);
            } elseif (strpos($key, 'kriteria') === 0) {
                $kriteria[] = $value;
                unset($parameters[$key]); // hapus kriteriaX
            }
        }

        // Tambahkan array kriteria baru
        $parameters['kriteria'] = $kriteria;

        // dd($parameters); // Tampilkan hasil akhir
        $user = Users::findOrFail(session('account')['id']);
        $user->preferensi_user = json_encode(Arr::except($parameters, ['_token']));
        if ($user->save()) {
            session(['account' => $user]);

            return redirect('/')->with('success', ['Terimakasih', 'Semoga Lekas Diterima Kerja']);
        }
    }

    public function reset_password(Request $request)
    {
        $request->validate([
            'password' => 'required|string|max:12',
            'password_confirmation' => ['required', 'string', 'max:12', 'same:password'],
            'email' => ['required', 'string', 'max:150'],
        ], [
            // password
            'password.required'      => 'Password wajib diisi.',
            'password.string'        => 'Password harus berupa teks.',
            'password.max'           => 'Password maksimal 12 karakter.',

            // password-retype
            'password_confirmation.required' => 'Ulangi password wajib diisi.',
            'password_confirmation.string'   => 'Ulangi password harus berupa teks.',
            'password_confirmation.max'      => 'Ulangi password maksimal 12 karakter.',
            'password_confirmation.same'     => 'Ulangi password harus sama dengan password.',

            // email
            'email.required'         => 'Email wajib diisi.',
            'email.string'           => 'Email harus berupa teks.',
            'email.max'              => 'Email maksimal 150 karakter.',
        ]);
        $user = Users::where('email', $request->email)->first();
        if ($user) {
            $user->password = $request->password;
            $user->VerificationCode = null;

            if ($user->save()) {
                return redirect('/Login')->with('success', ['Silahkan Login', 'Password Berhasil Diganti!']);
            } else {
                return redirect()->back()->with('fail', ['Kesalahan Sistem!', 'Mohon Coba beberapa saat lagi!']);
            }
        } else {
            return redirect()->back()->with('fail', ['Gagal!', 'Tidak Ada akun dengan email ' . $request->email . '!']);
        }
    }
}
