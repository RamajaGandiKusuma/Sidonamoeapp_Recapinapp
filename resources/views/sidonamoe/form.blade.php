<script>
    window.flashSuccess = @json(session('success'));
    window.flashError   = @json(session('error'));
</script>

@extends('layouts.app')

@section('title', 'Form Kunjungan')

@section('content')
<div class="container">
    <div class="form-section">
        <h2 class="section-title">Form Janji Namoe</h2>

        <form  id="form-pengajuan" action="{{ route('pengajuan.inputPengajuan') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <div class="form-row">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
            </div>

            <div class="form-group">
                <div class="form-row">
                    <label for="nama_instansi">Nama Instansi</label>
                    <input type="text" id="nama_instansi" name="nama_instansi" required>
                </div>
            </div>

            <div class="form-group">
                <div class="form-row">
                    <label for="alamat_instansi">Alamat Instansi</label>
                    <textarea id="alamat_instansi" name="alamat_instansi" required></textarea>
                </div>
            </div>

            <div class="form-group">
                <div class="form-row">
                    <label for="tanggal_kunjungan">Tanggal Kunjungan</label>
                    <input type="date" id="tanggal" name="tanggal" required>
                </div>
            </div>

            <div class="form-group">
                <div class="form-row">
                    <label for="jam_kunjung">Jam Kunjung</label>
                    <input type="time" id="jam_kunjung" name="jam_kunjung" required>
                </div>
            </div>

            <div class="form-group">
                <div class="form-row">
                    <label for="materi_tema">Materi/Tema Kunjungan</label>
                    <input type="text" id="materi" name="materi" required>
                </div>
            </div>

            <div class="form-group">
                <div class="form-row">
                    <label for="pimpinan_rombongan">Pimpinan Rombongan</label>
                    <input type="text" id="pimpinan_rombongan" name="pimpinan_rombongan" required>
                </div>
            </div>

            <div class="form-group">
                <div class="form-row">
                    <label for="jumlah_rombongan">Jumlah Rombongan</label>
                    <div class="people-count">
                        <input type="number" id="jumlah" name="jumlah" min="1" required>
                        <span>Orang</span>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="form-row">
                    <label for="whatsapp">Whatsapp</label>
                    <input type="text" id="no_wa" name="no_wa" required>
                </div>
            </div>

            <div class="form-group">
                <div class="form-row">
                    <label for="dokumen">Dokumen Pendukung</label>
                    <div class="file-upload">
                        <input type="file" id="dokumen" name="dokumen" accept=".pdf">
                        <span class="file-info">max. 2 MB | format .pdf</span>
                    </div>
                </div>
            </div>

            {{-- CAPTCHA boleh dikomentari dulu --}}
            {{--
            <div class="form-group">
                <div class="form-row">
                    <label for="captcha">Captcha</label>
                    <div class="captcha-group">
                        <img src="{{ captcha_src() }}" alt="captcha">
                        <input type="text" name="captcha" required>
                    </div>
                </div>
            </div>
            --}}

            <div class="form-group">
                <div class="form-row">
                    <label></label>
                    <button type="submit" class="submit-btn">Submit</button>
                </div>
            </div>
        </form>
    </div>

    <div class="schedule-section">
        <h2 class="section-title">Jadwal Kunjungan</h2>
        <p>Jadwal kunjungan akan ditampilkan di sini.</p>
    </div>
</div>
@endsection
