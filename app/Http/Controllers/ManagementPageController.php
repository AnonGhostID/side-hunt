<?php

namespace App\Http\Controllers;

use App\Models\Pekerjaan;
use App\Models\Pelamar;
use App\Models\Laporan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Jika Anda memerlukan info Auth
use App\Models\Transaksi;
use App\Models\User;
use App\Models\Payment; // add Payment import
use App\Models\Users;
use App\Models\TiketBantuan;
use Carbon\Carbon;

class ManagementPageController extends Controller
{
    // Pastikan user terautentikasi untuk mengakses halaman manajemen
    // public function __construct()
    // {
    //     $this->middleware('auth');
    //     // Anda mungkin ingin menambahkan middleware admin untuk beberapa rute di sini atau di file rute
    // }

    public function dashboard()
    {
        $user = session('account');
        $totalPekerjaans = Pekerjaan::where('pembuat', $user['id'])
                  ->where('is_active', '1')
                  ->count();
        $totalPekerja = Users::where('role', 'user')->count();
        
        // Get fresh user data from database to ensure balance is current
        $currentUser = Users::find($user['id']);
        
        // Update session with fresh data if balance is different
        if ($currentUser && $currentUser->dompet != $user->dompet) {
            session(['account' => $currentUser]);
            $user = $currentUser;
        }
        
        return view('manajemen.dashboard', compact('totalPekerjaans','totalPekerja', 'user'));
    }

    // --- Fitur Manajemen Utama ---
    public function pekerjaanBerlangsung()
    {
        $user = session('account');
        $pekerjaanBerlangsung = [];
        
        if ($user->isUser()) {
            // For regular users, get jobs they've applied to and been accepted or are pending
            $pekerjaanBerlangsung = Pelamar::where('user_id', $user->id)
                ->with(['sidejob', 'user'])
                ->get();
        } elseif ($user->isMitra()) {
            // For mitra, get jobs they've created that have applicants
            $pekerjaanIds = Pekerjaan::where('pembuat', $user->id)
                ->pluck('id');
                
            $pekerjaanBerlangsung = Pelamar::whereIn('job_id', $pekerjaanIds)
                ->with(['sidejob', 'user'])
                ->get();
        }
        
        // Load the job creators for each job
        foreach ($pekerjaanBerlangsung as $pelamar) {
            if ($pelamar->sidejob) {
                $pembuatId = $pelamar->sidejob->pembuat;
                $pembuatUser = Users::find($pembuatId);
                $pelamar->sidejob->pembuatUser = $pembuatUser;
            }
        }
        
        return view('manajemen.pekerjaan.berlangsung', compact('pekerjaanBerlangsung'));
    }

    public function pekerjaanTerdaftar()
    {
        $user = session('account');
        $pekerjaans = Pekerjaan::where('pembuat', $user['id'])
                               ->with(['pelamar', 'pembuat'])
                               ->orderBy('created_at', 'desc')
                               ->get();
        
        return view('manajemen.pekerjaan.terdaftar', compact('pekerjaans'));
    }

    public function uploadLaporan()
    {
        $user = session('account');
        $jobs = Pelamar::where('user_id', $user->id)
            ->where('status', 'diterima')
            ->with('sidejob')
            ->get()
            ->pluck('sidejob')
            ->filter(function($job) {
                // Only show jobs that are not yet finished
                return $job && $job->status !== 'Selesai';
            })
            ->unique('id');

        return view('manajemen.laporan.upload', compact('jobs'));
    }

    public function storeLaporan(Request $request)
    {
        $user = session('account');

        $request->validate([
            'pekerjaan_id' => 'required|exists:pekerjaans,id',
            'deskripsi_laporan' => 'required|string',
            'foto_selfie' => 'required|image',
            'dokumentasi_pekerjaan.*' => 'required|image',
        ]);

        // Check if the job is already completed
        $pekerjaan = Pekerjaan::find($request->pekerjaan_id);
        if ($pekerjaan && $pekerjaan->status === 'Selesai') {
            return redirect()->back()->with('error', 'Tidak dapat mengunggah laporan untuk pekerjaan yang sudah selesai.');
        }

        // Check if user is actually assigned to this job
        $pelamar = Pelamar::where('user_id', $user->id)
            ->where('job_id', $request->pekerjaan_id)
            ->where('status', 'diterima')
            ->first();
        
        if (!$pelamar) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk mengunggah laporan untuk pekerjaan ini.');
        }

        // Check if a report has already been submitted for this job by this user
        $existingLaporan = Laporan::where('job_id', $request->pekerjaan_id)
            ->where('user_id', $user->id)
            ->first();
        
        if ($existingLaporan) {
            return redirect()->back()->with('error', 'Anda sudah mengunggah laporan untuk pekerjaan ini sebelumnya.');
        }

        $selfiePath = $request->file('foto_selfie')->store('laporan/selfie', 'public');

        $dokPaths = [];
        foreach ($request->file('dokumentasi_pekerjaan', []) as $file) {
            $dokPaths[] = $file->store('laporan/dokumentasi', 'public');
        }

        Laporan::create([
            'job_id' => $request->pekerjaan_id,
            'user_id' => $user->id,
            'deskripsi' => $request->deskripsi_laporan,
            'foto_selfie' => $selfiePath,
            'foto_dokumentasi' => json_encode($dokPaths),
        ]);

        return redirect()->route('manajemen.laporan.upload')->with('success', 'Laporan berhasil dikirim.');
    }

    public function topUp()
    {
        return view('manajemen.keuangan.topUp');
    }

    public function tarikSaldo()
    {
        return view('manajemen.keuangan.tarik_saldo');
    }

    public function riwayatTransaksi()
    {
        // Fetch payments for logged-in user
        $perPage = 10;
        $transaksi = Payment::where('user_id', session('account')['id'])
            ->orderBy('updated_at', 'desc')
            ->paginate($perPage);

        return view('manajemen.keuangan.riwayat_transaksi', compact('transaksi'));
    }

    /**
     * AJAX endpoint to fetch riwayat transaksi data without page reload
     */
    public function riwayatTransaksiData(Request $request)
    {
        $user = session('account');
        $search = $request->query('search', '');
        $perPage = $request->query('per_page', 10);
        // Determine per page count
        if ($perPage === 'all') {
            $perPage = Payment::where('user_id', $user['id'])->count();
        } else {
            $perPage = (int) $perPage;
        }
        $query = Payment::where('user_id', $user['id']);
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

    public function panelBantuanDanPenipuan()
    {
        $user = session('account');
        if ($user->isAdmin()) {
            $tickets = TiketBantuan::with('user')->orderBy('created_at', 'desc')->get();
        } else {
            $tickets = TiketBantuan::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        }
        return view('manajemen.bantuan.panel', compact('tickets', 'user'));
    }

    public function storeBantuanDanPenipuan(Request $request)
    {
        $user = session('account');
        $type = $request->input('type', 'bantuan');
        
        if ($type === 'penipuan') {
            $data = $request->validate([
                'type' => 'required|in:bantuan,penipuan',
                'subject' => 'required|string|max:255',
                'description' => 'required|string',
                'pihak_terlapor' => 'required|string|max:255',
                'tanggal_kejadian' => 'required|date',
                'bukti_pendukung.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
            ]);
            
            $buktiPaths = [];
            if ($request->hasFile('bukti_pendukung')) {
                foreach ($request->file('bukti_pendukung') as $file) {
                    $buktiPaths[] = $file->store('bukti_penipuan', 'public');
                }
            }
            
            TiketBantuan::create([
                'user_id' => $user->id,
                'type' => 'penipuan',
                'subject' => $data['subject'],
                'description' => $data['description'],
                'pihak_terlapor' => $data['pihak_terlapor'],
                'tanggal_kejadian' => $data['tanggal_kejadian'],
                'bukti_pendukung' => $buktiPaths,
            ]);
            
            return redirect()->route('manajemen.bantuan.panel')->with('success', 'Laporan penipuan berhasil dikirim.');
        } else {
            $data = $request->validate([
                'type' => 'required|in:bantuan,penipuan',
                'subject' => 'required|string|max:255',
                'description' => 'required|string',
            ]);
            
            TiketBantuan::create([
                'user_id' => $user->id,
                'type' => 'bantuan',
                'subject' => $data['subject'],
                'description' => $data['description'],
            ]);
            
            return redirect()->route('manajemen.bantuan.panel')->with('success', 'Tiket bantuan berhasil dibuat.');
        }
    }

    public function panelBantuan()
    {
        return $this->panelBantuanDanPenipuan();
    }

    public function respondTicket(Request $request, $id)
    {
        $ticket = TiketBantuan::findOrFail($id);
        $data = $request->validate([
            'admin_response' => 'required|string',
        ]);
        $ticket->status = 'closed';
        $ticket->admin_response = $data['admin_response'];
        $ticket->save();
        return redirect()->route('manajemen.bantuan.panel')->with('success', 'Tiket telah ditutup.');
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

    public function managePekerjaan($id)
    {
        $user = session('account');
        $pekerjaan = Pekerjaan::with('pelamar')->findOrFail($id);

        if (!$user->isAdmin() && $pekerjaan->pembuat != $user->id) {
            abort(403);
        }

        $laporans = Laporan::where('job_id', $id)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('manajemen.pekerjaan.manage', compact('pekerjaan', 'laporans'));
    }


    public function storeJobRating(Request $request, $jobId)
    {
        $request->validate([
            'pekerja_id' => 'required',
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string|max:500'
        ]);

        $user = session('account');
        $pekerjaan = Pekerjaan::findOrFail($jobId);

        // Verify that the user is the job creator
        if ($pekerjaan->pembuat != $user->id) {
            return redirect()->back()->with('rating_error', 'Anda tidak memiliki akses untuk memberikan rating pada pekerjaan ini.');
        }

        // Verify that the job is completed
        if ($pekerjaan->status != 'Selesai') {
            return redirect()->back()->with('rating_error', 'Rating hanya dapat diberikan setelah pekerjaan selesai.');
        }

        // Verify that the worker was actually accepted for this job
        $acceptedWorker = $pekerjaan->pelamar()
            ->where('status', 'diterima')
            ->where('user_id', $request->pekerja_id)
            ->first();

        if (!$acceptedWorker) {
            return redirect()->back()->with('rating_error', 'Pekerja yang dipilih tidak valid untuk pekerjaan ini.');
        }

        // For static implementation, just return success message
        // In a real implementation, you would save the rating to database
        return redirect()->back()->with('rating_success', 'Rating berhasil diberikan kepada pekerja!');
    }

    public function trackRecordPelamar()
    {
        return view('manajemen.pelamar.track_record');
    }

}
