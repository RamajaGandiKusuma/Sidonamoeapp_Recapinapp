<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>
    <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;">
        {{-- Header --}}
        <div style="border-bottom: 2px solid #1a3a5c; padding-bottom: 10px; margin-bottom: 20px;">
            <h2 style="color: #1a3a5c; margin: 0;">DPRD Kabupaten Sidoarjo</h2>
            <p style="color: #666; margin: 5px 0 0;">Sistem Informasi Dokumentasi Kunjungan (SIDONAMOE)</p>
        </div>

        <p>Yth. <strong>{{ $instansi }}</strong>,</p>

        <p>Dengan hormat,</p>

        <p>
            Sehubungan dengan permohonan kunjungan kerja yang Saudara ajukan melalui
            sistem <strong>SIDONAMOE</strong>, dengan ini kami sampaikan bahwa pengajuan tersebut
            berstatus:
        </p>

        {{-- Detail Status --}}
        <div style="background-color: {{ $status === 'DITERIMA' ? '#f0fff0' : '#fff3f3' }}; border-left: 4px solid {{ $status === 'DITERIMA' ? '#2e7d32' : '#c62828' }}; padding: 15px; margin: 15px 0;">
            <table style="width: 100%;">
                <tr>
                    <td style="padding: 5px 0; width: 140px;"><strong>Nomor Surat</strong></td>
                    <td style="padding: 5px 0;">: {{ $nomor_surat }}</td>
                </tr>
                <tr>
                    <td style="padding: 5px 0;"><strong>Status</strong></td>
                    <td style="padding: 5px 0;">: <span style="color: {{ $status === 'DITERIMA' ? '#2e7d32' : '#c62828' }}; font-weight: bold;">{{ $status }}</span></td>
                </tr>
                <tr>
                    <td style="padding: 5px 0;"><strong>Jumlah Peserta</strong></td>
                    <td style="padding: 5px 0;">: {{ $jumlah }} orang</td>
                </tr>
                <tr>
                    <td style="padding: 5px 0;"><strong>Keterangan</strong></td>
                    <td style="padding: 5px 0;">: {{ $catatan }}</td>
                </tr>
            </table>
        </div>

        @if($status === 'DITERIMA')
            <p>
                Kami mempersilakan rombongan Saudara untuk melaksanakan kunjungan kerja
                sesuai dengan jadwal yang telah diajukan. Mohon untuk tetap mematuhi
                protokol dan tata tertib yang berlaku di lingkungan DPRD Kabupaten Sidoarjo.
            </p>
        @else
            <p>
                Kami mohon maaf atas ketidaknyamanan ini. Saudara dapat mengajukan ulang
                permohonan kunjungan kerja dengan menyesuaikan jumlah peserta sesuai
                ketentuan yang berlaku (maksimal 50 orang).
            </p>
        @endif

        <p>Terlampir surat balasan resmi dalam format PDF untuk arsip Saudara.</p>

        <p>
            Demikian pemberitahuan ini kami sampaikan. Atas perhatian dan kerja sama
            Saudara, kami ucapkan terima kasih.
        </p>

        {{-- Footer --}}
        <div style="margin-top: 30px; border-top: 1px solid #ddd; padding-top: 15px;">
            <p style="margin: 0;">Hormat kami,</p>
            <p style="margin: 5px 0;"><strong>Sekretariat DPRD Kabupaten Sidoarjo</strong></p>
            <p style="color: #999; font-size: 12px; margin-top: 15px;">
                Email ini dikirim secara otomatis oleh sistem SIDONAMOE.<br>
                Mohon untuk tidak membalas email ini.
            </p>
        </div>
    </div>
</body>
</html>
