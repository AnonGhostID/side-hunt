<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PekerjaanController;
use App\Http\Controllers\PelamarController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SideJobController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\ManagementPageController;
use App\Http\Controllers\TopUpController;
use App\Models\Pelamar;
use App\Models\Users;
use Illuminate\Support\Facades\Auth;

//DEWA
Route::get('/', function () {
    return redirect('/Index');
});

Route::get('/tes', function () {
    return view('Dewa.tes');
});

Route::get('/see', function () {
    return view('Dewa.notifikasi_ke_email.pekerjaan.lolos_siap_bekerja');
});


//Auth
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/Index', [HomeController::class, 'index'])->name('home');
Route::get('/Login', [HomeController::class, 'Login'])->name(name: 'Login');
Route::get('/Verify-Email', [UsersController::class, 'verify_view']);
Route::post('/verify_email', [UsersController::class, 'submit_verify_email']);
Route::get('/forget/password', action: [UsersController::class, 'forget_password']);
Route::post('/send_change_password', action: [UsersController::class, 'send_code_change_password']);
Route::get('/reset_password/{token}/{email}', action: [UsersController::class, 'view_Reset_Password']);
Route::post('/reset-new-password', action: [UsersController::class, 'reset_password']);

Route::get('/Register', [HomeController::class, 'Register']);
Route::get('/Logout', [UsersController::class, 'logout']);
Route::get('/Pekerjaan/{id}', [PekerjaanController::class, 'view_pekerjaan']);

Route::get('/NotAllowed', function () {
    $nama_halaman = 'Akses Ditolak';
    $active_navbar = 'none';
    return view('Dewa.NotAllowedPage', compact('nama_halaman', 'active_navbar'));
});



Route::post('/Login_account', [UsersController::class, 'Login_Account']);
Route::post('/Register_account', action: [UsersController::class, 'create']);
Route::get('/kerja/', action: [PekerjaanController::class, 'index']);


Route::middleware(['role:user|mitra|admin'])->group(function () {
    Route::get('/chat/{id_target}', [ChatController::class, 'index']);
    Route::post('/make_chat', action: [ChatController::class, 'store']);
});
Route::middleware(['role:user|mitra'])->group(function () {
    Route::get('/daftar-lamaran/', action: [PekerjaanController::class, 'Daftar_Lamaran']);
    Route::post('/user/preferensi/save', action: [UsersController::class, 'save_preverensi']);
    Route::post('/kerja/add', action: [PekerjaanController::class, 'store']);
    // Route::post('/kerja/add', action: [PekerjaanController::class, 'store']);
    Route::post('/Profile/Edit', [UsersController::class, 'Profile_Edit']);
    Route::get('/transaksi', [TransaksiController::class, 'index'])->name('user.transaksi');
    Route::post('/Lamar/{idPekerjaan}', action: [PelamarController::class, 'store']);
    Route::get('/Chat/Kerja/{idPekerjaan}', action: [ChatController::class, 'Lamaran']);

    // Route::get('/Chat/{id_target}', action: [ChatController::class, 'index']);

    //Kerja
    Route::get('/question-new-user', action: [HomeController::class, 'new_user']);

    //NewUser

    //Profile
    Route::get('/Profile', [UsersController::class, 'Profile']);
    Route::get('/profile/{id}', [UsersController::class, 'show'])->name('user.profile');

    Route::middleware(['role:mitra'])->group(function () {
        Route::get('/kerja/create', action: [PekerjaanController::class, 'create']);
        Route::post('/lamaran/delete/{id_lamaran}', action: [PelamarController::class, 'delete']);
        Route::get('/daftar-Pekerjaan/', action: [PekerjaanController::class, 'Daftar_Pekerjaan']);

        Route::get('/daftar-Pelamar/{id}', action: [PekerjaanController::class, 'Daftar_Pelamar']);
        Route::get('/Pelamar/Profile/{idPelamar}', [PelamarController::class, 'Profile_Pelamar']);

        Route::post('/pelamar/tolak', action: [PelamarController::class, 'tolak']);
        Route::post('/pelamar/terima', action: [PelamarController::class, 'terima']);
        Route::post('/pelamar/interview', action: [PelamarController::class, 'interview_pelamar']);


        Route::prefix('management')->name('manajemen.')->group(function () {
            Route::get('/', [ManagementPageController::class, 'dashboard'])->name('dashboard');

            // Pekerjaan
            Route::get('/pekerjaan-berlangsung', [ManagementPageController::class, 'pekerjaanBerlangsung'])->name('pekerjaan.berlangsung');
            Route::get('/upload-laporan', [ManagementPageController::class, 'uploadLaporan'])->name('laporan.upload');
            Route::get('/riwayat-pekerjaan', [ManagementPageController::class, 'riwayatPekerjaan'])->name('pekerjaan.riwayat');

            // Keuangan
            // Route::get('/gateway-pembayaran', [ManagementPageController::class, 'gatewayPembayaran'])->name('pembayaran.gateway');
            Route::get('/Top-Up', [ManagementPageController::class, 'topUp'])->name('topUp');
            Route::post('/Top-Up', [TopUpController::class, 'store'])->name('topup.store');

            // TopUp Controller disini
            Route::get('/Top-Up/{external_id}', [TopUpController::class, 'payment'])->name('topup.payment');
            Route::post('/Top-Up/check-status', [TopUpController::class, 'checkStatus'])->name('topup.check-status');
            Route::post('/Top-Up/expire-timeout', [TopUpController::class, 'expireOnTimeout'])->name('topup.expire-timeout');
            Route::post('/Top-Up/cancel/{external_id}', [TopUpController::class, 'cancel'])->name('topup.cancel');
            //
            Route::get('/tarik-saldo', [ManagementPageController::class, 'tarikSaldo'])->name('tarik_saldo');
            Route::get('/riwayat-transaksi', [ManagementPageController::class, 'riwayatTransaksi'])->name('transaksi.riwayat');
            // AJAX endpoint for fetching riwayat transaksi data without page reload
            Route::get('/riwayat-transaksi/data', [ManagementPageController::class, 'riwayatTransaksiData'])->name('transaksi.riwayat.data');
            Route::get('/refund-dana', [ManagementPageController::class, 'refundDana'])->name('dana.refund');
            Route::get('/laporan-keuangan', [ManagementPageController::class, 'laporanKeuangan'])->name('keuangan.laporan');

            // Pelaporan & Bantuan
            Route::get('/lapor-penipuan', [ManagementPageController::class, 'laporPenipuanForm'])->name('pelaporan.penipuan.form');
            Route::post('/lapor-penipuan', [ManagementPageController::class, 'storePenipuanReport'])->name('pelaporan.penipuan.store');
            Route::get('/panel-bantuan', [ManagementPageController::class, 'panelBantuan'])->name('bantuan.panel');

            // Fitur Lainnya
            Route::get('/notifikasi-pekerjaan', [ManagementPageController::class, 'notifikasiStatusPekerjaan'])->name('notifikasi.pekerjaan');
            Route::get('/notifikasi-pelamaran', [ManagementPageController::class, 'notifikasiStatusPelamaran'])->name('notifikasi.pelamaran');
            Route::get('/chat', [ManagementPageController::class, 'chatPengguna'])->name('chat');
            Route::get('/rating-user', [ManagementPageController::class, 'ratingUser'])->name('rating.user');
            Route::get('/track-record-pelamar', [ManagementPageController::class, 'trackRecordPelamar'])->name('pelamar.track-record');
            Route::post('/transaksi/{jobId}', [TransaksiController::class, 'buatTransaksi'])->name('transaksi.buat');


            // Rute Khusus Admin (Contoh)
            Route::prefix('admin')->name('admin.') // Buat middleware 'admin' jika belum ada
                ->group(function () {
                    Route::get('/pemantauan-laporan', [ManagementPageController::class, 'pemantauanLaporanAdmin'])->name('laporan.pemantauan');
                    Route::get('/users', [ManagementPageController::class, 'usersListAdmin'])->name('users.list');
                    Route::get('/users/tambah', [ManagementPageController::class, 'usersTambahAdmin'])->name('users.tambah');
                    // Route::get('/users/{user}/edit', [ManagementPageController::class, 'usersEditAdmin'])->name('users.edit');
                    // Route::put('/users/{user}', [ManagementPageController::class, 'usersUpdateAdmin'])->name('users.update');
                    Route::get('/admin', [HomeController::class, 'admin'])->name('admin.index'); // Index
                    Route::get('/admin/user/{id}/edit', [UsersController::class, 'showAdmin'])->name('admin.show.profile');
                    Route::match(['get', 'put'], '/admin/user/{id}', [UsersController::class, 'update'])->name('admin.update.profile');
                    Route::get('/admin/user/edit/{id}', [UsersController::class, 'edit'])->name('admin.edit.profile');
                    Route::get('/admin/user/delete/{id}', [UsersController::class, 'delete'])->name('admin.delete.profile');
                    Route::get('/admin/transaksi/setujui/{kode}', [TransaksiController::class, 'setujuiTransaksi'])->name('admin.transaksi.setuju');
                    Route::post('/admin/transaksi/tolak/{kode}', [TransaksiController::class, 'tolakTransaksi'])->name('admin.transaksi.tolak');
                    // Route::patch('/users/{user}/activate', [ManagementPageController::class, 'usersActivateAdmin'])->name('users.activate');
                    // Route::patch('/users/{user}/deactivate', [ManagementPageController::class, 'usersDeactivateAdmin'])->name('users.deactivate');
                });
            // ->group(function () {
            //     Route::get('/pemantauan-laporan', [ManagementPageController::class, 'pemantauanLaporanAdmin'])->name('laporan.pemantauan');
            //     Route::get('/users', [ManagementPageController::class, 'usersListAdmin'])->name('users.list');
            //     Route::get('/users/tambah', [ManagementPageController::class, 'usersTambahAdmin'])->name('users.tambah');
            // Route::get('/users/{user}/edit', [ManagementPageController::class, 'usersEditAdmin'])->name('users.edit');
            // Route::put('/users/{user}', [ManagementPageController::class, 'usersUpdateAdmin'])->name('users.update');
            // Route::patch('/users/{user}/activate', [ManagementPageController::class, 'usersActivateAdmin'])->name('users.activate');
            // Route::patch('/users/{user}/deactivate', [ManagementPageController::class, 'usersDeactivateAdmin'])->name('users.deactivate');
            // });
        });
    });
});

Route::get('/cekkossaine', function () {
    return view('Dewa.cekcossaine');
});

Route::post('/CekKos', [PekerjaanController::class, 'ForTest']);



//Only Mitra

//End Dewa

// Auth::routes();

// Route::get('/cari', [SideJobController::class, 'cari'])->name('sidejob.cari');
// Route::get('/job/{id}', [SideJobController::class, 'show'])->name('sidejob.detail');



// Route::get('/management', [HomeController::class, 'management'])->name('management');
// Route::get('/management', [HomeController::class, 'management'])->name('management')->middleware('isAdmin');
// Route::middleware(['auth'])->group(function () {
// Route::get('/user/lamaran', [UsersController::class, 'pelamaran'])->name('user.history');
// Route::get('/sidejob', [SideJobController::class, 'index'])->name('sidejob.index');
// Route::get('/sidejob/create', [SideJobController::class, 'create'])->name('sidejob.create');
// Route::post('/sidejob', [SideJobController::class, 'store'])->name('sidejob.store');
// Route::get('/sidejob/{id}', [SideJobController::class, 'show'])->name('sidejob.show');
// Route::get('/sidejob/{sidejob}/edit', [SideJobController::class, 'edit'])->name('sidejob.edit');
// Route::put('/sidejob/{sidejob}', [SideJobController::class, 'update'])->name('sidejob.update');
// Route::delete('/sidejob/{sidejob}', [SideJobController::class, 'destroy'])->name('sidejob.destroy');
// Route::post('/sidejob/{sidejob}/buatPermintaan', [SideJobController::class, 'buatPermintaan'])->name('sidejob.buatPermintaan');
// Route::patch('/pelamar/{pelamar}/terima', [SideJobController::class, 'terima'])->name('pelamar.terima');
// Route::patch('/pelamar/{pelamar}/tolak', [SideJobController::class, 'tolak'])->name('pelamar.tolak');

// });

// Route::middleware(['role', 'mitra'])->group(function () {


//Sidejob
// Route::get('/sidejob/{id}', [SideJobController::class, 'showAdmin'])->name('admin.sidejob.show');
// Route::get('/sidejob/edit/{id}', [SideJobController::class, 'editAdmin'])->name('admin.sidejob.edit');

// });


require __DIR__ . '/auth.php';
