<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persetujuan extends Model
{
    protected $table = 'persetujuan';
    protected $primaryKey = 'id_persetujuan';
    public $timestamps = false;

    protected $fillable = [
        'id_pengajuan',
        'status',
        'catatan',
        'created_at',
        'updated_at',
    ];

    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class, 'id_pengajuan');
    }
    
    use HasFactory;
}
