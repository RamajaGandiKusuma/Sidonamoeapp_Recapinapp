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
        'instansi',
        'alamat',
        'tanggal',
        'jam_kunjungan',
        'materi',
        'pimpinan',
        'jumlah',
        'no_wa',
        'dokumen',
        'created_at'
    ];

    public function persetujuan()
    {
        return $this->hasOne(Persetujuan::class, 'id_pengajuan', 'id_pengajuan');
    }
    
    use HasFactory;
}
