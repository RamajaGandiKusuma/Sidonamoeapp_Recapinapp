<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('persetujuan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_pengajuan');
            $table->string('status')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->foreign('id_pengajuan')->references('id_pengajuan')->on('pengajuan')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('persetujuan');
    }
};
