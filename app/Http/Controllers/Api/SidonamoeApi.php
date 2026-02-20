<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use App\Models\Persetujuan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class SidonamoeApi extends Controller
{
    public function inputPengajuan(Request $request)
    {
        try {
            $validated = $request->validate([
                'email' => 'required|email',
                'instansi' => 'required|string|max:255',
                'alamat' => 'required|string',
                'tanggal' => 'required|date',
                'jam_kunjungan' => 'required',
                'materi' => 'required|string|max:255',
                'pimpinan' => 'required|string|max:255',
                'jumlah' => 'required|integer|min:1',
                'no_wa' => 'required|string|max:20',
                'dokumen' => 'required|file|mimes:pdf|max:2048',
            ]);

            $pathDokumen = $request->file('dokumen')->store(
                'dokumen-pengajuan',
                'public'
            );

            $pengajuan = Pengajuan::create([
                'email' => $validated['email'],
                'instansi' => $validated['instansi'],
                'alamat' => $validated['alamat'],
                'tanggal' => $validated['tanggal'],
                'jam_kunjungan' => $validated['jam_kunjungan'],
                'materi' => $validated['materi'],
                'pimpinan' => $validated['pimpinan'],
                'jumlah' => $validated['jumlah'],
                'no_wa' => $validated['no_wa'],
                'dokumen' => $pathDokumen,
                'created_at' => now(),
            ]);

            // Simulasi pengecekan jumlah peserta (nanti diganti hasil model deep learning)
            $batasPeserta = 50;

            if ($validated['jumlah'] == $batasPeserta) {
                $status  = 'belum dikonfirmasi';
                $catatan = 'Pengajuan kunjungan kerja diterima sesuai ketentuan.';
            } else {
                $status  = 'belum sesuai';
                $catatan = 'Jumlah peserta melebihi batas maksimal (' . $batasPeserta . ' orang).';
            }

            Persetujuan::create([
                'id_pengajuan' => $pengajuan->id_pengajuan,
                'status' => $status,
                'catatan' => $catatan,
                'created_at' => now(),
                'updated_at' => null,
            ]);

            // Jika tidak sesuai, generate PDF dan kirim notifikasi via n8n
            if ($status === 'belum sesuai') {
                $this->chatBotTotalPeserta($pengajuan, $status, $catatan);
            }

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

    public function chatBotTotalPeserta(Pengajuan $pengajuan, string $status, string $catatan)
    {
        try {
            $nomorSurat = 'SB-' . date('YmdHis');

            // Generate PDF surat balasan
            $pdf = Pdf::loadView('pdf.surat-balasan', [
                'nama'        => $pengajuan->instansi,
                'instansi'    => $pengajuan->instansi,
                'status'      => strtoupper($status),
                'catatan'     => $catatan,
                'jumlah'      => $pengajuan->jumlah,
                'nomor_surat' => $nomorSurat,
            ]);

            // Simpan PDF ke storage
            $fileName = 'surat_balasan_' . time() . '.pdf';
            Storage::disk('public')->put('surat/' . $fileName, $pdf->output());

            // Render email body dari view email khusus (terpisah dari PDF)
            $emailBody = view('email.surat-balasan', [
                'nama'        => $pengajuan->instansi,
                'instansi'    => $pengajuan->instansi,
                'status'      => strtoupper($status),
                'catatan'     => $catatan,
                'jumlah'      => $pengajuan->jumlah,
                'nomor_surat' => $nomorSurat,
            ])->render();

            // Kirim ke n8n webhook
            // URL internal Docker agar n8n bisa download PDF via route
            $pdfUrl = 'http://web/surat-pdf/' . $fileName;

            $n8nUrl = 'http://n8n:5678/webhook-test/form-submit';
            Http::post($n8nUrl, [
                'nama'          => $pengajuan->instansi,
                'email'         => $pengajuan->email,
                'status'        => strtoupper($status),
                'catatan'       => $catatan,
                'jumlah'        => $pengajuan->jumlah,
                'email_subject' => 'Balasan Permohonan Kunjungan Kerja - DPRD Kab. Sidoarjo [' . $nomorSurat . ']',
                'email_body'    => $emailBody,
                'pdf_url'       => $pdfUrl,
                'pdf_filename'  => $fileName,
            ]);

        } catch (\Exception $e) {
            // Log error tapi jangan gagalkan proses pengajuan
            \Illuminate\Support\Facades\Log::error('Gagal mengirim ke n8n: ' . $e->getMessage());
        }
    }
    public function index()
    {
        return Pengajuan::with('persetujuan')->get();
    }

}
