<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Jika Anda memerlukan info Auth
use App\Models\Transaksi;
use App\Models\SideJob;
use App\Models\User;
use App\Models\Payment; // add Payment import

class ManagementPageController extends Controller
{
    // Pastikan user terautentikasi untuk mengakses halaman manajemen
    public function __construct()
    {
        $this->middleware('auth');
        // Anda mungkin ingin menambahkan middleware admin untuk beberapa rute di sini atau di file rute
    }

    public function dashboard()
    {
        $user = Auth::user();
        $totalSideJobs = SideJob::count();
        $totalPekerja = User::where('isAdmin', 0)->count();
        
        return view('manajemen.dashboard', compact('user', 'totalSideJobs', 'totalPekerja'));
    }

    // --- Fitur Manajemen Utama ---
    public function pekerjaanBerlangsung()
    {
        // Logika untuk mengambil data pekerjaan yang sedang berlangsung
        return view('manajemen.pekerjaan.berlangsung'); // contoh path
    }

    public function uploadLaporan()
    {
        return view('manajemen.laporan.upload');
    }

    public function topUp()
    {
        $user = Auth::user();
        return view('manajemen.keuangan.topUp', compact('user'));
    }

    public function tarikSaldo()
    {
        return view('manajemen.keuangan.tarik_saldo');
    }

    public function riwayatTransaksi()
    {
        $user = Auth::user();
        // Fetch payments for logged-in user
        $perPage = 10;
        $transaksi = Payment::where('user_id', $user->id)
            ->orderBy('updated_at', 'desc')
            ->paginate($perPage);

        return view('manajemen.keuangan.riwayat_transaksi', compact('user', 'transaksi'));
    }

    /**
     * AJAX endpoint to fetch riwayat transaksi data without page reload
     */
    public function riwayatTransaksiData(Request $request)
    {
        $user = Auth::user();
        $search = $request->query('search', '');
        $perPage = $request->query('per_page', 10);
        // Determine per page count
        if ($perPage === 'all') {
            $perPage = Payment::where('user_id', $user->id)->count();
        } else {
            $perPage = (int) $perPage;
        }
        $query = Payment::where('user_id', $user->id);
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('external_id', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        $transaksi = $query->orderBy('updated_at', 'desc')
            ->paginate($perPage)
            ->appends(['search' => $search, 'per_page' => $request->query('per_page')]);

        // Render partials
        $rows = view('manajemen.keuangan.partials.riwayat_transaksi_rows', compact('transaksi'))->render();
        $pagination = view('manajemen.keuangan.partials.riwayat_transaksi_pagination', compact('transaksi'))->render();

        return response()->json(['table' => $rows, 'pagination' => $pagination]);
    }

    public function refundDana()
    {
        return view('manajemen.keuangan.refund_dana');
    }

    public function laporanKeuangan()
    {
        return view('manajemen.keuangan.laporan_keuangan');
    }

    public function laporPenipuan()
    {
        return view('manajemen.pelaporan.form_penipuan');
    }

    public function panelBantuan()
    {
        return view('manajemen.bantuan.panel');
    }

    // --- Fitur Administrasi Sistem (Contoh) ---
    public function pemantauanLaporanAdmin()
    {
        // $this->authorize('viewAdminContent'); // Contoh otorisasi
        return view('manajemen.admin.laporan_pemantauan');
    }

    public function usersListAdmin()
    {
        // $this->authorize('manageUsers');
        // Logika untuk mengambil daftar user
        return view('manajemen.admin.users.list');
    }

    public function usersTambahAdmin()
    {
        // $this->authorize('manageUsers');
        return view('manajemen.admin.users.tambah');
    }
    
    // --- Fitur Manajemen Lainnya ---
    public function notifikasiStatusPekerjaan()
    {
        return view('manajemen.notifikasi.status_pekerjaan');
    }

    public function notifikasiStatusPelamaran()
    {
        return view('manajemen.notifikasi.status_pelamaran');
    }

    public function chatPengguna()
    {

        return view('manajemen.chat.panel');
    }
    
    public function riwayatPekerjaan()
    {
        return view('manajemen.pekerjaan.riwayat');
    }

    public function ratingUser()
    {
        return view('manajemen.rating.form_rating');
    }

    public function trackRecordPelamar()
    {
        return view('manajemen.pelamar.track_record');
    }

}
