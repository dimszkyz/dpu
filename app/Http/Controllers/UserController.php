<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Menampilkan daftar semua pengguna.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Fitur pencarian berdasarkan NIP atau Nama
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%");
        }

        // Ambil data terbaru (bisa diubah menjadi paginate() jika data sangat banyak)
        $users = $query->orderBy('created_at', 'desc')->get();

        return view('admin.managementuser', compact('users'));
    }


    /**
     * Menyimpan data pengguna baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nip'      => 'required|unique:users,nip|numeric',
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role'     => 'required|in:superadmin,admin,user',
        ], [
            'nip.unique' => 'NIP ini sudah terdaftar di sistem.',
            'email.unique' => 'Email ini sudah digunakan.',
            'password.min' => 'Password minimal harus 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        DB::beginTransaction();
        try {
            User::create([
                'nip'      => $request->nip,
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password), // Password wajib di-hash
                'role'     => $request->role,
            ]);

            DB::commit();
            return redirect()->route('admin.user.index')->with('success', 'Pengguna baru berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Menampilkan form tambah pengguna baru (Mode Tambah User).
     */
    public function create()
    {
        return view('admin.formuser'); // Menggunakan view gabungan
    }

    /**
     * Menampilkan form edit untuk pengguna tertentu (Mode Edit Pengguna).
     */
    public function edit($nip)
    {
        $user = User::where('nip', $nip)->firstOrFail();
        
        return view('admin.formuser', compact('user')); // Mengirim data user ke view gabungan
    }

    /**
     * Memperbarui data pengguna di database.
     */
    public function update(Request $request, $nip)
    {
        $user = User::where('nip', $nip)->firstOrFail();

        $request->validate([
            'nip'      => ['required', 'numeric', Rule::unique('users')->ignore($user->id)],
            'name'     => 'required|string|max:255',
            'email'    => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role'     => 'required|in:superadmin,admin,user',
            // Password tidak required saat update, hanya jika diisi saja
            'password' => 'nullable|string|min:6|confirmed', 
        ]);

        DB::beginTransaction();
        try {
            $updateData = [
                'nip'   => $request->nip,
                'name'  => $request->name,
                'email' => $request->email,
                'role'  => $request->role,
            ];

            // Jika user mengisi kolom password, maka perbarui password-nya
            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            $user->update($updateData);

            DB::commit();
            return redirect()->route('admin.user.index')->with('success', 'Data pengguna berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Menghapus data pengguna dari database.
     */
    /**
     * Menghapus data pengguna dari database.
     */
    public function destroy($nip)
    {
        $user = User::where('nip', $nip)->firstOrFail();

        // PERBAIKAN: Mencegah user menghapus akunnya sendiri dengan mencocokkan ID secara langsung
        if (\Illuminate\Support\Facades\Auth::id() == $user->id) {
            return redirect()->route('admin.user.index')->with('error', 'Anda tidak dapat menghapus akun yang sedang Anda gunakan.');
        }

        try {
            $user->delete();
            return redirect()->route('admin.user.index')->with('success', 'Pengguna berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('admin.user.index')->with('error', 'Gagal menghapus pengguna karena memiliki relasi data (misal: terkait dengan penugasan/laporan).');
        }
    }
}