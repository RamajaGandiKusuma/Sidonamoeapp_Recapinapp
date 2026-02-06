<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use App\Models\Persetujuan;
use Illuminate\Http\Request;

class SidonamoeApi extends Controller
{
    public function inputPengajuan(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'nama_instansi' => 'required|string|max:255',
                'alamat_instansi' => 'required|string',
                'tanggal' => 'required|date',
                'jam_kunjung' => 'required',
                'materi' => 'required|string|max:255',
                'pimpinan_rombongan' => 'required|string|max:255',
                'jumlah' => 'required|integer|min:1',
                'no_wa' => 'required|string|max:20',
                'dokumen' => 'required|file|mimes:pdf|max:2048',
            ]);

            $pathDokumen = $request->file('dokumen')->store(
                'dokumen-pengajuan',
                'public'
            );

            $pengajuan = Pengajuan::create([
                'email' => $request->email,
                'nama_instansi' => $request->nama_instansi,
                'alamat_instansi' => $request->alamat_instansi,
                'tanggal' => $request->tanggal,
                'jam_kunjung' => $request->jam_kunjung,
                'materi' => $request->materi,
                'pimpinan_rombongan' => $request->pimpinan_rombongan,
                'jumlah' => $request->jumlah,
                'no_wa' => $request->no_wa,
                'dokumen' => $pathDokumen,
            ]);

            Persetujuan::create([
                'id_pengajuan' => $pengajuan->id_pengajuan,
                'status' => 'belum dikonfirmasi',
                'catatan' => null,
                'created_at' => now(),
            ]);

            return redirect()
                ->route('sidonamoe.form')
                ->with('success', 'Pengajuan berhasil dikirim');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function updateData(Request $request, $id_pengajuan)
    {
        try {
            $persetujuan = Persetujuan::where('id_pengajuan', $id_pengajuan)->firstOrFail();

            $validated = $request->validate([
                'status' => 'required|in:disetujui,ditolak',
                'catatan' => 'nullable|string',
            ]);

            $persetujuan->update([
                'status' => $validated['status'],
                'catatan' => $validated['catatan'] ?? null,
                'updated_at' => now(),
            ]);

            return redirect()
                ->route('sidonamoe.form')
                ->with('success', 'Data Pengajuan Berhasil Diperbarui');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    public function index()
    {
        return Pengajuan::with('persetujuan')->get();
    }

}
