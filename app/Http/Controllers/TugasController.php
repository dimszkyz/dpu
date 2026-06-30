<?php

namespace App\Http\Controllers;

use App\Models\Tugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\User;

// Tambahkan dua baris ini untuk fungsi Excel
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Storage;

class TugasController extends Controller
{
    public function index(Request $request)
    {
        // Mulai Query Builder dan ambil relasi admin
        $query = Tugas::with('admin');

        // 1. Pencarian (Search) berdasarkan Kode Tugas atau Nama Tugas
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kodetugas', 'like', "%{$search}%")
                    ->orWhere('nama_tugas', 'like', "%{$search}%");
            });
        }

        // 2. Filter Bulan (Berdasarkan tanggal_mulai)
        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal_mulai', $request->bulan);
        }

        // 3. Filter Tahun (Berdasarkan tanggal_mulai)
        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_mulai', $request->tahun);
        }

        // 4. Filter Status 
        // Menggunakan tanggal_selesai karena tabel tidak punya kolom status secara eksplisit
        if ($request->filled('status')) {
            $now = \Carbon\Carbon::now(); // Ambil waktu hari ini

            if ($request->status == 'aktif') {
                // Aktif: Tanggal selesai lebih besar atau sama dengan hari ini (Belum lewat deadline)
                $query->where('tanggal_selesai', '>=', $now);
            } elseif ($request->status == 'selesai') {
                // Selesai: Tanggal selesai lebih kecil dari hari ini (Sudah lewat deadline)
                $query->where('tanggal_selesai', '<', $now);
            }
        }

        // Ambil data yang sudah difilter, urutkan dari yang terbaru dibuat
        $tugas = $query->orderBy('created_at', 'desc')->get();

        return view('admin.tugas', compact('tugas'));
    }

    /**
     * Download Template Tugas (Format XLSX)
     */
    public function template()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set Header
        $sheet->setCellValue('A1', 'Nama Tugas');
        $sheet->setCellValue('B1', 'Deskripsi');
        $sheet->setCellValue('C1', 'Tanggal Mulai (YYYY-MM-DD)');
        $sheet->setCellValue('D1', 'Tanggal Selesai (YYYY-MM-DD)');

        // Set Contoh Data
        $sheet->setCellValue('A2', 'Contoh: Pengecekan Server');
        $sheet->setCellValue('B2', 'Mengecek ketersediaan server mingguan yang ada di rak utama.');
        $sheet->setCellValue('C2', date('Y-m-d'));
        $sheet->setCellValue('D2', date('Y-m-d', strtotime('+7 days')));

        // Format Header agar tebal (Bold)
        $sheet->getStyle('A1:D1')->getFont()->setBold(true);

        // Mengatur lebar kolom otomatis (Auto-size)
        foreach (range('A', 'D') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Mengatur tinggi baris otomatis berdasarkan isi (Wrap Text)
        $sheet->getStyle('A1:D2')->getAlignment()->setWrapText(true);
        $sheet->getStyle('A1:D2')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

        // Proses download file
        $writer = new Xlsx($spreadsheet);
        $filename = 'Template_Import_Tugas.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    /**
     * Export Data Tugas ke Excel (Format XLSX)
     */
    public function export()
    {
        $tugas = Tugas::with('admin')->orderBy('created_at', 'desc')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set Header
        $headers = ['Kode Tugas', 'Nama Tugas', 'Deskripsi', 'Tanggal Mulai', 'Tanggal Selesai', 'Admin Pembuat', 'Status'];
        $columnLetter = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($columnLetter . '1', $header);
            $columnLetter++;
        }

        // Format Header (Bold & Background Abu-abu)
        $sheet->getStyle('A1:G1')->getFont()->setBold(true);
        $sheet->getStyle('A1:G1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFF0F0F0');

        // Isi Data
        $row = 2; // Mulai dari baris ke-2
        foreach ($tugas as $t) {
            $today = \Carbon\Carbon::today();
            $start = \Carbon\Carbon::parse($t->tanggal_mulai);
            $end = \Carbon\Carbon::parse($t->tanggal_selesai);

            if ($today->lt($start)) {
                $status = 'Mendatang';
            } elseif ($today->gt($end)) {
                $status = 'Selesai';
            } else {
                $status = 'Aktif';
            }

            $sheet->setCellValue('A' . $row, $t->kodetugas);
            $sheet->setCellValue('B' . $row, $t->nama_tugas);
            $sheet->setCellValue('C' . $row, $t->deskripsi);
            $sheet->setCellValue('D' . $row, $t->tanggal_mulai);
            $sheet->setCellValue('E' . $row, $t->tanggal_selesai);
            $sheet->setCellValue('F' . $row, $t->admin->name ?? 'Admin');
            $sheet->setCellValue('G' . $row, $status);

            $row++;
        }

        // Mengatur lebar kolom otomatis (Auto-size) dari kolom A sampai G
        foreach (range('A', 'G') as $colID) {
            $sheet->getColumnDimension($colID)->setAutoSize(true);
        }

        // Mengatur tinggi baris otomatis (Wrap Text) untuk seluruh area data
        $highestRow = $sheet->getHighestRow();
        $sheet->getStyle('A1:G' . $highestRow)->getAlignment()->setWrapText(true);
        $sheet->getStyle('A1:G' . $highestRow)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

        // Proses download file
        $writer = new Xlsx($spreadsheet);
        $filename = 'Data_Tugas_' . date('Y-m-d') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    // Menampilkan halaman form tambah tugas
    public function create()
    {
        return view('admin.tambahtugas');
    }

    // Menyimpan data tugas ke database
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'kodetugas' => 'required|string|max:10|unique:tugas,kodetugas',
            'nama_tugas' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'lampiran' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048', // max 2MB
        ]);

        // 2. Siapkan data yang akan disimpan
        $data = $request->except('lampiran'); // Ambil semua input kecuali file lampiran

        // Isi id_admin otomatis dari id user yang sedang login
        $data['id_admin'] = Auth::user()->nip;

        // 3. Proses Upload Lampiran (jika ada)
        if ($request->hasFile('lampiran')) {
            // Simpan file ke folder storage/app/public/lampiran_tugas
            $path = $request->file('lampiran')->store('lampiran_tugas', 'public');
            $data['lampiran'] = $path;
        }

        // 4. Simpan ke database
        Tugas::create($data);

        // 5. Redirect kembali ke halaman daftar tugas dengan pesan sukses
        return redirect()->route('admin.tugas.index')->with('success', 'Tugas baru berhasil ditambahkan!');
    }

    public function destroy($kodetugas)
    {
        // Cari data tugas berdasarkan primary key (kodetugas)
        $tugas = Tugas::findOrFail($kodetugas);

        // Cek apakah tugas memiliki file lampiran dan file tersebut ada di storage
        if ($tugas->lampiran && Storage::disk('public')->exists($tugas->lampiran)) {
            // Hapus file fisik dari storage
            Storage::disk('public')->delete($tugas->lampiran);
        }

        // Hapus data tugas dari database
        $tugas->delete();

        // Redirect dengan pesan sukses (akan ditangkap oleh toast notifikasi yang sudah kita buat)
        return redirect()->route('admin.tugas.index')->with('success', 'Tugas beserta lampirannya berhasil dihapus!');
    }

    public function edit($kodetugas)
    {
        $tugas = Tugas::findOrFail($kodetugas);
        return view('admin.edittugas', compact('tugas'));
    }

    public function update(Request $request, $kodetugas)
    {
        $tugas = Tugas::findOrFail($kodetugas);

        $request->validate([
            'nama_tugas' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'lampiran' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $data = $request->except(['lampiran', 'kodetugas']);

        if ($request->hasFile('lampiran')) {
            if ($tugas->lampiran && Storage::disk('public')->exists($tugas->lampiran)) {
                Storage::disk('public')->delete($tugas->lampiran);
            }

            $path = $request->file('lampiran')->store('lampiran_tugas', 'public');
            $data['lampiran'] = $path;
        }

        $tugas->update($data);

        return redirect()->route('admin.tugas.index')->with('success', 'Data tugas berhasil diperbarui!');
    }

    public function show($kodetugas)
    {
        $tugas = Tugas::findOrFail($kodetugas);
        return view('admin.detailtugas', compact('tugas'));
    }

    public function importProcess(Request $request)
    {
        // Data input berupa array: tugas[0][nama_tugas], tugas[1][nama_tugas], dst
        $tugasData = $request->input('tugas');

        // Data file berupa array (jika ada yang diupload)
        // $tugasFiles = $request->file('tugas');

        if (!$tugasData) {
            return redirect()->route('admin.tugas.index')->with('error', 'Tidak ada data yang diproses.');
        }

        foreach ($tugasData as $index => $row) {
            // Validasi sederhana, abaikan baris yang kosong nama tugasnya
            if (empty($row['nama_tugas'])) continue;

            // Jika user tidak mengisi kodetugas di form, buat otomatis
            $kode = !empty($row['kodetugas']) ? $row['kodetugas'] : 'TGS' . strtoupper(Str::random(5));

            // Pastikan kode benar-benar unik agar tidak error primary key
            while (Tugas::where('kodetugas', $kode)->exists()) {
                $kode = 'TGS' . strtoupper(Str::random(5));
            }

            $lampiranPath = null;
            
            // PERBAIKAN: Pastikan $tugasFiles tidak null dan merupakan array sebelum mengakses indeksnya
            if ($request->hasFile("tugas.{$index}.lampiran")) {
                $lampiranPath = $request->file("tugas.{$index}.lampiran")->store('lampiran_tugas', 'public');
            }

            // Simpan ke database
            Tugas::create([
                'kodetugas'       => $kode,
                'nama_tugas'      => $row['nama_tugas'],
                'deskripsi'       => $row['deskripsi'],

                // PERUBAHAN: Bungkus nilai tanggal dengan $this->formatTanggalMySQL()
                'tanggal_mulai'   => $this->formatTanggalMySQL($row['tanggal_mulai']),
                'tanggal_selesai' => $this->formatTanggalMySQL($row['tanggal_selesai']),
                
                'lampiran'        => $lampiranPath,
                'id_admin'        => \Illuminate\Support\Facades\Auth::user()->nip,
            ]);
        }

        return redirect()->route('admin.tugas.index')->with('success', 'Data Tugas beserta lampiran berhasil diimport!');
    }

    /**
     * Fungsi untuk menstandarkan berbagai format tanggal Excel menjadi YYYY-MM-DD (MySQL)
     */
    private function formatTanggalMySQL($tanggal)
    {
        if (empty($tanggal)) return null;

        // 1. Jika tanggal terbaca sebagai Angka Seri Excel (misal: 46158)
        if (is_numeric($tanggal)) {
            try {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($tanggal)->format('Y-m-d');
            } catch (\Exception $e) {
                return null;
            }
        }

        // 2. Jika tanggal berupa String / Teks (misal: 16/05/2026, 16-05-2026, dll)
        $tanggal = trim($tanggal);

        // Daftar format yang paling sering digunakan
        $formats = [
            'd/m/Y', // 16/05/2026
            'd-m-Y', // 16-05-2026
            'd.m.Y', // 16.05.2026
            'Y-m-d', // 2026-05-16 (Sudah sesuai MySQL)
            'Y/m/d', // 2026/05/16
            'm/d/Y', // 05/16/2026 (Format US)
        ];

        foreach ($formats as $format) {
            try {
                // Coba cocokkan string dengan format satu per satu
                return \Carbon\Carbon::createFromFormat($format, $tanggal)->format('Y-m-d');
            } catch (\Exception $e) {
                // Jika gagal, lanjut coba format berikutnya di array
                continue; 
            }
        }

        // 3. Fallback: Jika tidak ada format yang cocok, biarkan Carbon mencoba menebaknya
        try {
            return \Carbon\Carbon::parse($tanggal)->format('Y-m-d');
        } catch (\Exception $e) {
            // Jika format benar-benar hancur dan tidak bisa dibaca, kembalikan null
            return null;
        }
    }
    // ... biarkan fungsi create, store, edit, update, destroy tetap ada di bawahnya ...
}
