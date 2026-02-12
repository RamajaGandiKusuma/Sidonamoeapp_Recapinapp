<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Balasan DPRD Kabupaten Sidoarjo</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
            margin: 40px;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .header h3,
        .header h4 {
            margin: 2px 0;
        }

        .header-line {
            border: none;
            border-top: 3px double #333;
            margin: 10px 0 20px 0;
        }

        .info-surat {
            margin-bottom: 20px;
        }

        .info-surat table td {
            vertical-align: top;
            padding: 2px 5px;
        }

        .tujuan {
            margin-bottom: 15px;
        }

        .content {
            text-align: justify;
            margin-bottom: 10px;
        }

        .content p {
            margin: 8px 0;
            text-indent: 40px;
        }

        .detail-box {
            border: 1px solid #999;
            padding: 10px 15px;
            margin: 15px 0;
            background-color: #f9f9f9;
        }

        .detail-box table td {
            padding: 3px 8px;
            vertical-align: top;
        }

        .status-diterima {
            color: #2e7d32;
            font-weight: bold;
        }

        .status-ditolak {
            color: #c62828;
            font-weight: bold;
        }

        .footer {
            margin-top: 50px;
            text-align: right;
            width: 250px;
            float: right;
        }

        .footer .ttd-space {
            height: 60px;
        }
    </style>
</head>
<body>

    {{-- ===== HEADER SURAT ===== --}}
    <div class="header">
        <h3>DEWAN PERWAKILAN RAKYAT DAERAH</h3>
        <h4>KABUPATEN SIDOARJO</h4>
        <p style="font-size: 10px; margin: 2px 0;">
            Jl. Pahlawan No. 1 Sidoarjo &mdash; Jawa Timur
        </p>
    </div>
    <hr class="header-line">

    {{-- ===== INFO SURAT ===== --}}
    <div class="info-surat">
        <table>
            <tr>
                <td>Nomor</td>
                <td>:</td>
                <td>{{ $nomor_surat }}</td>
            </tr>
            <tr>
                <td>Lampiran</td>
                <td>:</td>
                <td>-</td>
            </tr>
            <tr>
                <td>Perihal</td>
                <td>:</td>
                <td><strong>Balasan Permohonan Kunjungan Kerja</strong></td>
            </tr>
        </table>
    </div>

    {{-- ===== TUJUAN ===== --}}
    <div class="tujuan">
        <p>
            Kepada Yth.<br>
            <strong>{{ $nama }}</strong><br>
            {{ $instansi }}
        </p>
    </div>

    {{-- ===== ISI SURAT ===== --}}
    <div class="content">
        <p>
            Dengan hormat,
        </p>

        <p>
            Sehubungan dengan surat permohonan kunjungan kerja yang Saudara ajukan
            melalui sistem <strong>SIDONAMOE</strong> (Sistem Informasi Dokumentasi Kunjungan
            DPRD Kabupaten Sidoarjo), dengan ini kami sampaikan bahwa pengajuan tersebut
            berstatus:
        </p>

        {{-- Detail status --}}
        <div class="detail-box">
            <table>
                <tr>
                    <td><strong>Status</strong></td>
                    <td>:</td>
                    <td>
                        <span class="{{ $status === 'DITERIMA' ? 'status-diterima' : 'status-ditolak' }}">
                            {{ $status }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td><strong>Jumlah Peserta</strong></td>
                    <td>:</td>
                    <td>{{ $jumlah }} orang</td>
                </tr>
                <tr>
                    <td><strong>Keterangan</strong></td>
                    <td>:</td>
                    <td>{{ $catatan }}</td>
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

        <p>
            Demikian surat balasan ini kami sampaikan. Atas perhatian dan kerja sama
            Saudara, kami ucapkan terima kasih.
        </p>
    </div>

    {{-- ===== TTD ===== --}}
    <div class="footer">
        <p>Sidoarjo, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
        <p>
            Sekretariat DPRD<br>
            Kabupaten Sidoarjo
        </p>
        <div class="ttd-space"></div>
        <p>
            <strong>___________________________</strong><br>
            <em>Pejabat Berwenang</em>
        </p>
    </div>

</body>
</html>
