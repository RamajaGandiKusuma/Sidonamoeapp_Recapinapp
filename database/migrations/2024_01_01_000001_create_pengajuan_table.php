<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuan', function (Blueprint $table) {
            $table->id('id_pengajuan');
            $table->string('email');
            $table->string('nama_instansi');
            $table->text('alamat_instansi');
            $table->date('tanggal');
            $table->time('jam_kunjung');
            $table->string('materi');
            $table->string('pimpinan_rombongan');
            $table->integer('jumlah');
            $table->string('no_wa');
            $table->string('dokumen');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan');
    }
};
