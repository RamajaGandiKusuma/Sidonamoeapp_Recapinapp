<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persetujuan extends Model
{
    protected $table = 'persetujuan';
    public $timestamps = false;

    protected $fillable = [
        'id_pengajuan',
        'status',
        'catatan',
        'updated_at',
    ];

    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class, 'id_pengajuan');
    }
    
    use HasFactory;
}
