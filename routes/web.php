<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SideJobController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\ManagementController;
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
