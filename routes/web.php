<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SideJobController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\ManagementPageController;
use App\Http\Controllers\TopUpController;
use Illuminate\Support\Facades\Auth;


// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/cari', [SideJobController::class, 'cari'])->name('sidejob.cari');
Route::get('/job/{id}', [SideJobController::class, 'show'])->name('sidejob.detail');

Route::get('/profile/{id}', [UsersController::class, 'show'])->name('user.profile');

Route::middleware(['auth'])->group(function () {
    Route::get('/management', [HomeController::class, 'management'])->name('management')->middleware('isAdmin');
    Route::get('/user/lamaran', [UsersController::class, 'pelamaran'])->name('user.history');
    Route::get('/sidejob', [SideJobController::class, 'index'])->name('sidejob.index');
    Route::get('/sidejob/create', [SideJobController::class, 'create'])->name('sidejob.create');
    Route::post('/sidejob', [SideJobController::class, 'store'])->name('sidejob.store');
    Route::get('/sidejob/{id}', [SideJobController::class, 'show'])->name('sidejob.show');
    Route::get('/sidejob/{sidejob}/edit', [SideJobController::class, 'edit'])->name('sidejob.edit');
    Route::put('/sidejob/{sidejob}', [SideJobController::class, 'update'])->name('sidejob.update');
    Route::delete('/sidejob/{sidejob}', [SideJobController::class, 'destroy'])->name('sidejob.destroy');
    Route::post('/sidejob/{sidejob}/buatPermintaan', [SideJobController::class, 'buatPermintaan'])->name('sidejob.buatPermintaan');
    Route::patch('/pelamar/{pelamar}/terima', [SideJobController::class, 'terima'])->name('pelamar.terima');
    Route::patch('/pelamar/{pelamar}/tolak', [SideJobController::class, 'tolak'])->name('pelamar.tolak');
    Route::get('/transaksi', [TransaksiController::class, 'index'])->name('user.transaksi');

    Route::post('/transaksi/{jobId}', [TransaksiController::class, 'buatTransaksi'])->name('transaksi.buat');
});

Route::middleware(['auth', 'isAdmin'])->group(function(){
    Route::get('/admin', [HomeController::class, 'admin'])->name('admin.index'); // Index
    Route::get('/admin/user/{id}/edit',[UsersController::class,'showAdmin'])->name('admin.show.profile');
    Route::match(['get','put'],'/admin/user/{id}',[UsersController::class,'update'])->name('admin.update.profile');
    Route::get('/admin/user/edit/{id}',[UsersController::class,'edit'])->name('admin.edit.profile');
    Route::get('/admin/user/delete/{id}',[UsersController::class,'delete'])->name('admin.delete.profile');

    
    //Sidejob
    Route::get('/sidejob/{id}', [SideJobController::class, 'showAdmin'])->name('admin.sidejob.show');
    Route::get('/sidejob/edit/{id}', [SideJobController::class, 'editAdmin'])->name('admin.sidejob.edit');

    Route::get('/admin/transaksi/setujui/{kode}', [TransaksiController::class, 'setujuiTransaksi'])->name('admin.transaksi.setuju');
    Route::post('/admin/transaksi/tolak/{kode}', [TransaksiController::class, 'tolakTransaksi'])->name('admin.transaksi.tolak');
});

Route::prefix('management')->name('manajemen.')->middleware(['auth'])->group(function () {
    Route::get('/', [ManagementPageController::class, 'dashboard'])->name('dashboard');

    // Pekerjaan
    Route::get('/pekerjaan-berlangsung', [ManagementPageController::class, 'pekerjaanBerlangsung'])->name('pekerjaan.berlangsung');
    Route::get('/upload-laporan', [ManagementPageController::class, 'uploadLaporan'])->name('laporan.upload');
    Route::get('/riwayat-pekerjaan', [ManagementPageController::class, 'riwayatPekerjaan'])->name('pekerjaan.riwayat');

    // Keuangan
    // Route::get('/gateway-pembayaran', [ManagementPageController::class, 'gatewayPembayaran'])->name('pembayaran.gateway');
    Route::get('/Top-Up', [ManagementPageController::class, 'topUp'])->name('topUp');
    Route::post('/Top-Up', [TopUpController::class, 'store'])->name('topup.store');
    Route::get('/Top-Up/{external_id}', [TopUpController::class, 'payment'])->name('topup.payment');
    Route::post('/Top-Up/check-status', [TopUpController::class, 'checkStatus'])->name('topup.check-status');
    Route::post('/Top-Up/cancel/{external_id}', [TopUpController::class, 'cancel'])->name('topup.cancel');
    Route::get('/tarik-saldo', [ManagementPageController::class, 'tarikSaldo'])->name('tarik_saldo');
    Route::get('/riwayat-transaksi', [ManagementPageController::class, 'riwayatTransaksi'])->name('transaksi.riwayat');
    Route::get('/refund-dana', [ManagementPageController::class, 'refundDana'])->name('dana.refund');
    Route::get('/laporan-keuangan', [ManagementPageController::class, 'laporanKeuangan'])->name('keuangan.laporan');

    // Pelaporan & Bantuan
    Route::get('/lapor-penipuan', [ManagementPageController::class, 'laporPenipuan'])->name('pelaporan.penipuan');
    Route::get('/panel-bantuan', [ManagementPageController::class, 'panelBantuan'])->name('bantuan.panel');

    // Fitur Lainnya
    Route::get('/notifikasi-pekerjaan', [ManagementPageController::class, 'notifikasiStatusPekerjaan'])->name('notifikasi.pekerjaan');
    Route::get('/notifikasi-pelamaran', [ManagementPageController::class, 'notifikasiStatusPelamaran'])->name('notifikasi.pelamaran');
    Route::get('/chat', [ManagementPageController::class, 'chatPengguna'])->name('chat');
    Route::get('/rating-user', [ManagementPageController::class, 'ratingUser'])->name('rating.user');
    Route::get('/track-record-pelamar', [ManagementPageController::class, 'trackRecordPelamar'])->name('pelamar.track-record');


    // Rute Khusus Admin (Contoh)
    Route::prefix('admin')->name('admin.')->middleware(['admin']) // Buat middleware 'admin' jika belum ada
    ->group(function () {
        Route::get('/pemantauan-laporan', [ManagementPageController::class, 'pemantauanLaporanAdmin'])->name('laporan.pemantauan');
        Route::get('/users', [ManagementPageController::class, 'usersListAdmin'])->name('users.list');
        Route::get('/users/tambah', [ManagementPageController::class, 'usersTambahAdmin'])->name('users.tambah');
        // Route::get('/users/{user}/edit', [ManagementPageController::class, 'usersEditAdmin'])->name('users.edit');
        // Route::put('/users/{user}', [ManagementPageController::class, 'usersUpdateAdmin'])->name('users.update');
        // Route::patch('/users/{user}/activate', [ManagementPageController::class, 'usersActivateAdmin'])->name('users.activate');
        // Route::patch('/users/{user}/deactivate', [ManagementPageController::class, 'usersDeactivateAdmin'])->name('users.deactivate');
    });
});