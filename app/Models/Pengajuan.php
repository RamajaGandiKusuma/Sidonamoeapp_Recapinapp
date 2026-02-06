<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengajuan extends Model
{
    protected $table = 'pengajuan';
    protected $primaryKey = 'id_pengajuan';
    public $timestamps = false;

    protected $fillable = [
        'email',
        'nama_instansi',
        'alamat_instansi',
        'tanggal',
        'jam_kunjung',
        'materi',
        'pimpinan_rombongan',
        'jumlah',
        'no_wa',
        'dokumen'
    ];

    public function persetujuan()
    {
        return $this->belongsTo(Pengajuan::class, 'id_pengajuan', 'id_pengajuan');
    }
    
    use HasFactory;
}
