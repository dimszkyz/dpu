<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    use HasFactory;

    protected $table = 'laporans';
    
    protected $fillable = [
        'id_penugasan', 
        'status', 
        'teks_laporan', 
        'file_final_path'
    ];

    // Relasi balik ke tabel Penugasan
    public function penugasan()
    {
        return $this->belongsTo(Penugasan::class, 'id_penugasan');
    }

    // Satu laporan bisa memiliki banyak file unggahan (riwayat)
    public function files()
    {
        return $this->hasMany(LaporanFile::class, 'id_laporan');
    }

    // Satu laporan bisa memiliki banyak riwayat chat revisi
    public function chats()
    {
        return $this->hasMany(LaporanRevisiChat::class, 'id_laporan');
    }
}