<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penugasan extends Model
{
    use HasFactory;

    // Pastikan baris ini tersimpan dengan benar
    protected $table = 'penugasan';
    
    protected $fillable = ['kodetugas', 'id_admin', 'batas_waktu_lapor'];

    public function tugas()
    {
        return $this->belongsTo(Tugas::class, 'kodetugas', 'kodetugas');
    }

    public function admin()
    {
        // PERBAIKAN: Tambahkan parameter 'nip' di sini
        return $this->belongsTo(User::class, 'id_admin', 'nip');
    }

    public function anggota()
    {
        return $this->hasMany(AnggotaPenugasan::class, 'id_penugasan');
    }

    public function laporan()
    {
        return $this->hasOne(Laporan::class, 'id_penugasan');
    }
}