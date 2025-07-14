<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\Notify_Applications;
use App\Models\Pekerjaan;
use App\Models\Pelamar;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class PelamarController extends Controller
{
    public function store($idPekerjaan, Request $req)
    {
        try {
            $cekJob = Pekerjaan::where('id', $idPekerjaan)->first();
            if ($cekJob != null) {
                try {
                    $cek = Pelamar::where('user_id', session('account')['id'])
                        ->where('job_id', $idPekerjaan)
                        ->first();
                } catch (\Exception $e) {
                    // Log::error('Error saat mengecek pelamar: ' . $e->getMessage());
                    return response()->json([
                        'success' => false,
                        'message' => 'Gagal memeriksa pelamar sebelumnya: ' . $e->getMessage(),
                    ]);
                }

                if ($cek == null) {
                    $data = [];
                    $data['user_id'] = session('account')['id'];
                    $data['job_id'] = $idPekerjaan;
                    $data['Tipe_Group'] = $req->team == true ? 'Team' : 'Sendiri';
                    $data['Data_Team'] = $req->team == true ? json_encode($req->data) : null;

                    try {
                        $save_attemp_job = Pelamar::create($data);
                    } catch (QueryException $e) {
                        // Log::error('DB Error saat menyimpan pelamar: ' . $e->getMessage());
                        return response()->json([
                            'success' => false,
                            'message' => 'Terjadi kesalahan database saat menyimpan pelamar: ' . $e->getMessage(),
                        ]);
                    } catch (\Exception $e) {
                        // Log::error('Error umum saat menyimpan pelamar: ' . $e->getMessage());
                        return response()->json([
                            'success' => false,
                            'message' => 'Terjadi kesalahan saat menyimpan data pelamar: ' . $e->getMessage(),
                        ]);
                    }

                    if ($save_attemp_job) {
                        try {
                            $job = Pekerjaan::where('id', $idPekerjaan)->first();

                            if ((new ChatController())->update_lamaran($save_attemp_job->id, $job->pembuat, session('account')['id'])) {
                                return response()->json([
                                    'success' => true,
                                    'message' => $req->data
                                ]);
                            } else {
                                return response()->json([
                                    'success' => false,
                                    'message' => 'Lamaran tidak diterima oleh sistem.',
                                ]);
                            }
                        } catch (\Exception $e) {
                            // Log::error('Error saat memproses lamaran_diterima: ' . $e->getMessage());
                            return response()->json([
                                'success' => false,
                                'message' => 'Terjadi kesalahan saat memproses lamaran: ' . $e->getMessage(),
                            ]);
                        }
                    } else {
                        return response()->json([
                            'success' => false,
                            'message' => 'Gagal menyimpan data pelamar.',
                        ]);
                    }
                } else {
                    return response()->json([
                        'success' => true,
                    ]);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Pekerjaan ini tidak ada atau ada yang salah dengan data Anda.',
                ]);
            }
        } catch (\Exception $e) {
            // Log::error('Error umum pada store pelamar: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage(),
            ]);
        }
    }


    public function Profile_Pelamar($idPelamar)
    {
        // dD()
        $cek = (new PekerjaanController())->is_own_pelamar((Pelamar::findOrFail($idPelamar)['id']));
        if ($cek) {
            $data = Pelamar::join('users', 'pelamars.user_id', '=', 'users.id')
                ->select('users.*')
                ->get();
            $nama_halaman = 'Profil Pelamar';
            $active_navbar = 'Pekerjaan';
            return view('Dewa.pekerjaan.show_profile_pelamar', compact('data', 'nama_halaman', 'active_navbar'));
        } else {
            return redirect()->back()->with('fail', ['Akses Ditolak', 'Ini bukan Halaman yang bisa anda akses']);
        }
    }

    public function interview_pelamar(Request $request)
    {
        $request->validate([
            'tanggal_interview' => 'required|date|max:20',
            'link_interview'    => 'required|string|max:500',
            'pesan_interview'   => 'nullable|string|max:300',
            'id'                => 'required|string|max:10',
        ], [
            // tanggal_interview
            'tanggal_interview.required' => 'Tanggal Interview wajib diisi.',
            'tanggal_interview.date'   => 'Tanggal Interview harus berupa tanggal.',
            'tanggal_interview.max'      => 'Tanggal Interview maksimal 20 karakter.',

            // link_interview
            'link_interview.required' => 'Link Interview wajib diisi.',
            'link_interview.string'   => 'Link Interview harus berupa teks.',
            'link_interview.max'      => 'Link Interview maksimal 500 karakter.',

            // pesan_interview
            'pesan_interview.string' => 'Pesan Interview harus berupa teks.',
            'pesan_interview.max'    => 'Pesan Interview maksimal 300 karakter.',

            // id
            'id.required' => 'ID kosong.',
            'id.max'      => 'ID maksimal 10 karakter.',
        ]);

        $find = Pelamar::findOrFail($request->id);
        // dd($find);
        if ($find) {
            $find->jadwal_interview = $request->tanggal_interview;
            $find->link_Interview = $request->link_interview;
            $find->status = 'interview';
            $this->tolak_selain($find->user_id, $find->id, $find->job_id);
            // dd($find);;
            if ($find->save()) {
                (new ChatController())->update_lamaran($find->id, session('account')['id'], $find->user_id);
                $user = Users::where('id', $find->user_id)->first();
                $Pekerjaan = Pekerjaan::where('id', $find->job_id)->first();
                $email = ($user->email);
                Mail::to($email)->send(new Notify_Applications([$find, $user, $Pekerjaan, session('account')], 'interview'));
                return redirect('/daftar-Pelamar/all')->with('success', ['Selamat!', 'Interview Sudah kami kabarkan kepada user']);
            } else {
                return redirect()->back()->with('fail', ['Gagal!', 'Ada yang salah, coba beberapa saat lagi!']);
            }
        }
    }

    public function terima(Request $request)
    {
        // dd($request->idLamaran);
        $find = Pelamar::findOrFail($request->idLamaran);
        // dd($find);
        if ($find) {
            $find->jadwal_interview = null;
            $find->link_Interview = null;
            $find->status = $request->status;
            if ($find->save()) {
                (new ChatController())->update_lamaran($find->id, session('account')['id'], $find->user_id);

                $user = Users::where('id', $find->user_id)->first();
                $Pekerjaan = Pekerjaan::where('id', $find->job_id)->first();
                $email = ($user->email);
                Mail::to($email)->send(new Notify_Applications([$find, $user, $Pekerjaan, session('account')], 'Menunggu Pekerjaan'));
                return redirect('/daftar-Pelamar/all')->with('success', ['Berhasil!', 'User Sudah Menerima Informasi Ini Lewat Email Mereka']);
            } else {
                return redirect()->back()->with('fail', ['Gagal!', 'Ada yang salah, coba beberapa saat lagi!']);
            }
        }
    }


    public function delete($id_lamaran)
    {
        // dd($request->idLamaran);
        $find = Pelamar::findOrFail($id_lamaran);
        // dd($find);
        if ($find) {
            $find->jadwal_interview = null;
            $find->link_Interview = null;
            $find->is_delete = true;
            if ($find->save()) {
                (new ChatController())->delete_lamaran($find->id, session('account')['id'], $find->user_id);
                $user = Users::where('id', $find->user_id)->first();
                $Pekerjaan = Pekerjaan::where('id', $find->job_id)->first();
                $email = ($user->email);
                Mail::to($email)->send(new Notify_Applications([$find, $user, $Pekerjaan, session('account')], 'Menunggu Pekerjaan'));
                return redirect('/daftar-Pelamar/all')->with('success', ['Berhasil!', 'Lamaran Berhasil DIhapus']);
            } else {
                return redirect()->back()->with('fail', ['Gagal!', 'Ada yang salah, coba beberapa saat lagi!']);
            }
        }
    }

    public function tolak(Request $request)
    {
        // dd($request->idLamaran);
        $find = Pelamar::findOrFail($request->idLamaran);
        // dd($find);
        if ($find) {
            $find->jadwal_interview = null;
            $find->link_Interview = null;
            $find->status = $request->status;
            $find->alasan = $request->alasan;
            if ($find->save()) {
                (new ChatController())->update_lamaran($find->id, session('account')['id'], $find->user_id);

                $user = Users::where('id', $find->user_id)->first();
                $Pekerjaan = Pekerjaan::where('id', $find->job_id)->first();
                $email = ($user->email);
                if ($request->status == 'ditolak') {
                    Mail::to($email)->send(new Notify_Applications([$find, $user, $Pekerjaan, session('account')], 'ditolak'));
                } else {
                    Mail::to($email)->send(new Notify_Applications([$find, $user, $Pekerjaan, session('account')], 'Gagal'));
                }
                return redirect('/daftar-Pelamar/all')->with('success', ['Sedih Mendengarnya!', 'User Sudah Menerima Informasi Ini Lewat Email Mereka']);
            } else {
                return redirect()->back()->with('fail', ['Gagal!', 'Ada yang salah, coba beberapa saat lagi!']);
            }
        }
    }

    public function tolak_selain($id_pelamar, $lamaran_diterima, $id_pekerjaan)
    {

        $all = Pelamar::join('pekerjaans', 'pelamars.job_id', '=', 'pekerjaans.id')
            ->where('pelamars.user_id', $id_pelamar)
            ->where('pekerjaans.pembuat', session('account')['id'])
            ->select('*', 'pelamars.id as id_lamaran')
            ->get();

        foreach ($all as $lamaran) {
            if ($lamaran->id_lamaran != $lamaran_diterima) {
                $the_lamaran = Pelamar::where('id', $lamaran->id_lamaran)->first();
                $the_lamaran->status = 'ditolak';
                $the_lamaran->alasan = 'sudah ada lamaran lain yang lanjut interview';
                $the_lamaran->save();
                (new ChatController())->update_lamaran($lamaran->id_lamaran, session('account')['id'], $lamaran->user_id);
            }
        }
    }
}
