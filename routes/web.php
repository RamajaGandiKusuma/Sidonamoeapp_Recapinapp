<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SidonamoeApi;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('sidonamoe.form');
})->name('sidonamoe.form');

Route::post('/pengajuan', [SidonamoeApi::class, 'inputPengajuan'])
    ->name('pengajuan.inputPengajuan');

Route::put('/admin/konfirmasi/{id_pengajuan}', [SidonamoeApi::class, 'updateData'])
    ->name('admin.konfirmasi');

Route::get('/surat-pdf/{filename}', function ($filename) {
    $path = storage_path('app/public/surat/' . $filename);
    if (!file_exists($path)) {
        abort(404, 'PDF tidak ditemukan');
    }
    return response()->file($path, [
        'Content-Type' => 'application/pdf',
    ]);
})->where('filename', '.*')->name('surat.pdf');
