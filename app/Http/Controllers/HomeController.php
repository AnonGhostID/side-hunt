<?php

namespace App\Http\Controllers;

use App\Models\Pelamar;
use App\Models\SideJob;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $sidejob = SideJob::latest()->paginate(10);
        $peta = SideJob::all();

        // return view('pekerjaan.list', compact('sidejob'));

        return view('home', compact('sidejob', 'peta'));
    }

    public function show(string $id): View
    {
        //Cari data sesuai id
        $sidejob = SideJob::findorfail($id);
        $pelamar = Pelamar::where('job_id', $sidejob->id)->paginate(10);

        //Kirimkan ke view ini
        return view('pekerjaan.detail', compact('sidejob', 'pelamar'));
    }

    public function admin()
    {
        $sidejob = SideJob::latest()->paginate(10);
        $user = User::paginate(10);
        $pelamar = Pelamar::latest()->paginate(10);
        $transaksi = Transaksi::latest()->paginate(10);

        $user_count = User::count();
        $job_count = SideJob::count();
        $pelamar_count = Pelamar::count();
        $transaksi_count = Transaksi::count();

        return view('admin.AdminDashboard', compact('sidejob','transaksi', 'user', 'pelamar', 'user_count', 'job_count', 'pelamar_count','transaksi_count'));
    }

    public function management()
    {
        return view('management-dashboard');
    }
}
