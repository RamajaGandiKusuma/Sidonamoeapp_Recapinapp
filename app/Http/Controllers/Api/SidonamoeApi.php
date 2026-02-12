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

            if ($validated['jumlah'] <= $batasPeserta) {
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

            // Buat body email HTML formal
            $tanggalSurat = \Carbon\Carbon::now()->translatedFormat('d F Y');
            $emailBody = "
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;'>
                    <div style='border-bottom: 2px solid #1a3a5c; padding-bottom: 10px; margin-bottom: 20px;'>
                        <h2 style='color: #1a3a5c; margin: 0;'>DPRD Kabupaten Sidoarjo</h2>
                        <p style='color: #666; margin: 5px 0 0;'>Sistem Informasi Dokumentasi Kunjungan (SIDONAMOE)</p>
                    </div>

                    <p>Yth. <strong>{$pengajuan->instansi}</strong>,</p>

                    <p>Dengan hormat,</p>

                    <p>Sehubungan dengan permohonan kunjungan kerja yang Saudara ajukan melalui
                    sistem <strong>SIDONAMOE</strong>, dengan ini kami sampaikan bahwa pengajuan tersebut
                    berstatus:</p>

                    <div style='background-color: #fff3f3; border-left: 4px solid #c62828; padding: 15px; margin: 15px 0;'>
                        <table style='width: 100%;'>
                            <tr>
                                <td style='padding: 5px 0; width: 140px;'><strong>Nomor Surat</strong></td>
                                <td style='padding: 5px 0;'>: {$nomorSurat}</td>
                            </tr>
                            <tr>
                                <td style='padding: 5px 0;'><strong>Status</strong></td>
                                <td style='padding: 5px 0;'>: <span style='color: #c62828; font-weight: bold;'>" . strtoupper($status) . "</span></td>
                            </tr>
                            <tr>
                                <td style='padding: 5px 0;'><strong>Jumlah Peserta</strong></td>
                                <td style='padding: 5px 0;'>: {$pengajuan->jumlah} orang</td>
                            </tr>
                            <tr>
                                <td style='padding: 5px 0;'><strong>Keterangan</strong></td>
                                <td style='padding: 5px 0;'>: {$catatan}</td>
                            </tr>
                        </table>
                    </div>

                    <p>Kami mohon maaf atas ketidaknyamanan ini. Saudara dapat mengajukan ulang
                    permohonan kunjungan kerja dengan menyesuaikan jumlah peserta sesuai
                    ketentuan yang berlaku (maksimal 50 orang).</p>

                    <p>Terlampir surat balasan resmi dalam format PDF untuk arsip Saudara.</p>

                    <p>Demikian pemberitahuan ini kami sampaikan. Atas perhatian dan kerja sama
                    Saudara, kami ucapkan terima kasih.</p>

                    <div style='margin-top: 30px; border-top: 1px solid #ddd; padding-top: 15px;'>
                        <p style='margin: 0;'>Hormat kami,</p>
                        <p style='margin: 5px 0;'><strong>Sekretariat DPRD Kabupaten Sidoarjo</strong></p>
                        <p style='color: #999; font-size: 12px; margin-top: 15px;'>
                            Email ini dikirim secara otomatis oleh sistem SIDONAMOE.<br>
                            Mohon untuk tidak membalas email ini.
                        </p>
                    </div>
                </div>
            ";

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
