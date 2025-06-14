<?php

namespace App\Http\Controllers;

use App\Models\SideJob;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\Pelamar;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SideJobController extends Controller
{
    //Tampilkan semua pekerjaan
    public function index(): View
    {
        $sidejob = SideJob::where('pembuat', auth()->id())->paginate(10);

        return view('pekerjaan.list', compact('sidejob'));
    }

    //Tampilan form tambah kerja sampingan
    public function create(): View
    {
        return view('pekerjaan.add');
    }

    //Cara memasukan inputan kerja sampingan ke database
    public function store(Request $request): RedirectResponse
    {
        // dd($request->all());

        $request->validate([
            'nama' => 'required|min:5',
            'deskripsi' => 'required|min:10',
            'alamat' => 'required',
            'koordinat' => 'required',
            'min_gaji' => 'required|numeric',
            'max_gaji' => 'required|numeric',
            'max_pekerja' => 'required|numeric',
        ]);
        $today = Carbon::now();

        SideJob::create([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'tanggal_buat' => $today->toDateString(),
            'alamat' => $request->alamat,
            'koordinat' => $request->koordinat,
            'min_gaji' => $request->min_gaji,
            'max_gaji' => $request->max_gaji,
            'max_pekerja' => $request->max_pekerja,
            'jumlah_pelamar_diterima' => '0',
            'pembuat' => auth()->id()
        ]);
        return redirect()->route('sidejob.index')->with(['success' => 'Data berhasil tersimpan']);
    }

    //Tampilan lihat data pekerjaan sesuai id
    public function show(string $id): View
    {
        //Cari data sesuai id
        $sidejob = SideJob::findorfail($id);
        $pelamar = Pelamar::where('job_id', $sidejob->id)->paginate(10);
        $pembuat = User::findorfail($sidejob->pembuat);

        //Kirimkan ke view ini
        return view('pekerjaan.detail', compact('sidejob', 'pelamar','pembuat'));
    }

    //Tampilan lihat data pekerjaan sesuai id untuk admin
    public function showAdmin(string $id): View
    {
        //Cari data sesuai id
        $sidejob = SideJob::findorfail($id);
        $pelamar = Pelamar::where('job_id', $sidejob->id)->paginate(10);

        //Kirimkan ke view ini
        return view('admin.pekerjaan.detail', compact('sidejob', 'pelamar'));
    }

    //Tampilan untuk form edit 
    public function edit(string $id): View
    {
        $sidejob = SideJob::findorfail($id);

        return view('pekerjaan.edit', compact('sidejob'));
    }

    //Tampilan untuk form edit 
    public function editAdmin(string $id): View
    {
        $sidejob = SideJob::findorfail($id);

        return view('pekerjaan.edit', compact('sidejob'));
    }

    //Cara code untuk ubah data
    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'nama' => 'required|min:5',
            'deskripsi' => 'required|min:10',
            'alamat' => 'required',
            'koordinat' => 'required',
            'min_gaji' => 'required|numeric',
            'max_gaji' => 'required|numeric',
            'max_pekerja' => 'required|numeric',
        ]);
        $sidejob = SideJob::findorfail($id);
        $sidejob->update([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'alamat' => $request->alamat,
            'koordinat' => $request->koordinat,
            'min_gaji' => $request->min_gaji,
            'max_gaji' => $request->max_gaji,
            'max_pekerja' => $request->max_pekerja,
            'jumlah_pelamar_diterima' => '0',
            'pembuat' => auth()->id()
        ]);

        return redirect()->route('sidejob.index')->with(['success' => 'Data berhasil diubah']);
    }

    //Metode untuk menghapus data di database
    public function destroy($id): RedirectResponse
    {
        $sidejob = SideJob::findorfail($id);

        $sidejob->delete();

        return redirect()->route('sidejob.index')->with(['success' => 'Data berhasil dihapus']);
    }

    //Metode untuk mencari data
    public function cari(Request $request)
    {
        $cari = $request->input('cari');
        $hasil = SideJob::where('nama', 'like', "%" . $cari . "%")->paginate(30);

        return view('pekerjaan.hasil', compact('hasil'));
    }

    public function buatPermintaan(Request $request, SideJob $sidejob): RedirectResponse
    {
        Pelamar::create([
            'user_id' => auth()->id(),
            'job_id' => $sidejob->id,
            'status' => 'tunda'
        ]);

        return redirect()->back();
    }

    
}
