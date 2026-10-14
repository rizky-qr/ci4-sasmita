<?php
// v_form_pengajuan.php — Form pengajuan surat (semua 7 jenis)
$notifUrl = '/dashboard/mahasiswa/notifikasi';

ob_start(); ?>

<div style="max-width:700px">
    <!-- Breadcrumb -->
    <div style="display:flex;align-items:center;gap:.5rem;font-size:.8rem;color:var(--muted);margin-bottom:1.25rem">
        <a href="/dashboard/mahasiswa" style="color:var(--muted);text-decoration:none">Dashboard</a>
        <span>›</span>
        <span style="color:var(--text)"><?= esc($title) ?></span>
    </div>

    <div class="card">
        <div class="card-header">
            <div>
                <div class="card-title"><?= esc($title) ?></div>
                <div class="card-sub">Isi formulir di bawah dengan lengkap dan benar</div>
            </div>
        </div>

        <form action="/dashboard/mahasiswa/pengajuan/simpan" method="post" enctype="multipart/form-data" id="form-pengajuan">
            <?= csrf_field() ?>
            <input type="hidden" name="jenis_surat" id="jenis_surat" value="<?= esc($jenis) ?>">

            <!-- Info Mahasiswa (read-only dari session) -->
            <div style="background:var(--surface2);border:1px solid var(--border);border-radius:8px;padding:1rem;margin-bottom:1.25rem">
                <div style="font-size:.78rem;color:var(--muted);margin-bottom:.6rem;font-weight:600">DATA PEMOHON</div>
                <div class="form-grid">
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" id="draft-nama" value="<?= esc(session('nama')) ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label>NIM</label>
                        <input type="text" id="draft-nim" value="<?= esc(session('nim') ?? '-') ?>" disabled>
                    </div>
                    <div class="form-group full">
                        <label>Program Studi</label>
                        <input type="text" id="draft-prodi" value="<?= esc(str_replace('_', ' ', session('program') ?? '-')) ?>" disabled>
                    </div>
                </div>
            </div>

            <?php if (!empty($mitra)): ?>
            <!-- Pilih Tempat Mitra (untuk penelitian / PLP / observasi) -->
            <div class="form-group" style="margin-bottom:1rem">
                <label for="mitra_id">Tempat <?= in_array($jenis, ['PERMOHONAN_PLP_MAGANG','PERMOHONAN_OBSERVASI']) ? 'Magang / Observasi' : 'Penelitian' ?></label>
                <select name="mitra_id" id="mitra_id">
                    <option value="">— Pilih Tempat —</option>
                    <?php foreach ($mitra as $m): ?>
                        <option value="<?= esc($m['id']) ?>"><?= esc($m['nama']) ?> — <?= esc($m['alamat']) ?></option>
                    <?php endforeach ?>
                </select>
                <span class="form-hint">Jika tempat tidak ada dalam daftar, hubungi Staf TU.</span>
            </div>
            <?php endif ?>

            <!-- Keperluan -->
            <div class="form-group" style="margin-bottom:1rem">
                <label for="keperluan">Keperluan / Keterangan <span style="color:var(--danger)">*</span></label>
                <textarea name="keperluan" id="keperluan" rows="5" placeholder="Jelaskan keperluan pengajuan surat ini secara singkat dan jelas..." required><?= esc(old('keperluan')) ?></textarea>
                <span class="form-hint">Minimal 10 karakter. Contoh: "Surat ini diperlukan untuk keperluan kelengkapan beasiswa / instansi terkait."</span>
            </div>

            <!-- Upload Lampiran Pendukung -->
            <div class="form-group" style="margin-bottom:1.25rem">
                <label for="file_lampiran">Upload Lampiran Pendukung (Opsional)</label>
                <input type="file" name="file_lampiran" id="file_lampiran" accept=".pdf,.jpg,.jpeg,.png" style="padding:.55rem; border:1px dashed var(--border); border-radius:8px; width:100%; background:var(--surface); font-size:.84rem;">
                <span class="form-hint">Format didukung: PDF, JPG, PNG (Maksimal 2MB). Contoh: KRS, Kartu Mahasiswa, Surat Rekomendasi/Pengantar.</span>
            </div>

            <div style="display:flex;gap:.75rem;justify-content:flex-end;margin-top:1.5rem;flex-wrap:wrap;">
                <button type="button" class="btn btn-outline" onclick="openDraftModal()" style="font-weight:600">
                    👁️ Pratinjau Draft Surat
                </button>
                <a href="/dashboard/mahasiswa" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary" id="btn-submit">
                    📤 Kirim Pengajuan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Pratinjau Draft Surat -->
<div id="modal-draft" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.65);z-index:200;align-items:center;justify-content:center;padding:1rem;">
    <div style="background:#ffffff;border:1px solid var(--border);border-radius:14px;max-width:620px;width:100%;max-height:90vh;overflow-y:auto;box-shadow:0 12px 30px rgba(0,0,0,0.2);position:relative;">
        <div style="padding:1.25rem 1.5rem;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;background:#f8fafc;border-radius:14px 14px 0 0;">
            <div style="font-weight:700;color:var(--text-dark);font-size:.92rem;display:flex;align-items:center;gap:.5rem;">
                <span>📄</span> Pratinjau Draft Surat Resmi
            </div>
            <button type="button" onclick="closeDraftModal()" style="background:none;border:none;font-size:1.2rem;cursor:pointer;color:var(--muted);">&times;</button>
        </div>

        <div style="padding:2rem 2.2rem;font-family:'Times New Roman',Times,serif;color:#000000;position:relative;">
            <!-- Watermark Draft -->
            <div style="position:absolute;top:45%;left:50%;transform:translate(-50%,-50%) rotate(-30deg);font-size:38pt;font-weight:800;color:rgba(220,38,38,0.06);text-transform:uppercase;pointer-events:none;user-select:none;white-space:nowrap;">
                DRAFT PRATINJAU
            </div>

            <!-- Kop Surat -->
            <div style="text-align:center;border-bottom:2px solid #000;padding-bottom:8px;margin-bottom:18px;">
                <div style="font-size:10.5pt;font-weight:bold;">UNIVERSITAS IBN KHALDUN BOGOR</div>
                <div style="font-size:12.5pt;font-weight:bold;color:#1b5e20;">FAKULTAS KEGURUAN DAN ILMU PENDIDIKAN</div>
                <div style="font-size:8pt;color:#333;">Jl. KH. Sholeh Iskandar KM.2, Kedungbadak, Tanah Sereal, Kota Bogor 16162</div>
            </div>

            <!-- Judul Surat -->
            <div style="text-align:center;margin-bottom:16px;">
                <div style="font-size:12pt;font-weight:bold;text-decoration:underline;text-transform:uppercase;" id="modal-draft-title"><?= esc($title) ?></div>
                <div style="font-size:9.5pt;color:#555;margin-top:2px;">Nomor: [Akan diterbitkan otomatis setelah disahkan]</div>
            </div>

            <!-- Isi Paragraf -->
            <div style="font-size:10.5pt;line-height:1.6;margin-bottom:14px;">
                Yang bertanda tangan di bawah ini, Dekan Fakultas Keguruan dan Ilmu Pendidikan Universitas Ibn Khaldun Bogor menerangkan bahwa:
            </div>

            <table style="width:100%;font-size:10.5pt;margin-bottom:16px;border-collapse:collapse;">
                <tr>
                    <td style="width:130px;padding:3px 0;">Nama Lengkap</td>
                    <td style="width:15px;">:</td>
                    <td><strong id="modal-nama"><?= esc(session('nama')) ?></strong></td>
                </tr>
                <tr>
                    <td style="padding:3px 0;">NIM</td>
                    <td>:</td>
                    <td id="modal-nim"><?= esc(session('nim') ?? '-') ?></td>
                </tr>
                <tr>
                    <td style="padding:3px 0;">Program Studi</td>
                    <td>:</td>
                    <td id="modal-prodi"><?= esc(str_replace('_', ' ', session('program') ?? '-')) ?></td>
                </tr>
            </table>

            <div style="font-size:10.5pt;line-height:1.6;margin-bottom:12px;">
                Adalah benar mahasiswa FKIP UIKA Bogor. Surat ini diterbitkan untuk keperluan:
            </div>

            <div style="background:#f9fafb;border-left:3px solid #1b5e20;padding:.6rem 1rem;font-size:10pt;font-style:italic;margin-bottom:20px;" id="modal-keperluan">
                (Isi keperluan surat Anda...)
            </div>

            <!-- Tanda Tangan Simulasi -->
            <div style="display:flex;justify-content:flex-end;margin-top:25px;font-size:10pt;">
                <div style="text-align:center;min-width:180px;">
                    <div>Bogor, <?= date('d F Y') ?></div>
                    <div style="margin-top:4px;">Dekan FKIP UIKA Bogor,</div>
                    <div style="height:55px;display:flex;align-items:center;justify-content:center;color:#16a34a;font-size:.8rem;font-weight:bold;">
                        [TTD & QR Digital]
                    </div>
                    <div style="font-weight:bold;text-decoration:underline;">Pimpinan Fakultas</div>
                </div>
            </div>
        </div>

        <div style="padding:1rem 1.5rem;border-top:1px solid var(--border);display:flex;justify-content:flex-end;background:#f8fafc;border-radius:0 0 14px 14px;">
            <button type="button" class="btn btn-primary btn-sm" onclick="closeDraftModal()">
                Tutup Pratinjau
            </button>
        </div>
    </div>
</div>

<?php
$pageContent = ob_get_clean();
$extraScript = '
<script>
document.getElementById("form-pengajuan").addEventListener("submit", function() {
    document.getElementById("btn-submit").textContent = "⏳ Mengirim...";
    document.getElementById("btn-submit").disabled = true;
});

function openDraftModal() {
    var kep = document.getElementById("keperluan").value.trim();
    document.getElementById("modal-keperluan").innerText = kep || "(Belum ada keperluan diisi. Tuliskan keperluan pada formulir pengajuan.)";
    document.getElementById("modal-draft").style.display = "flex";
}

function closeDraftModal() {
    document.getElementById("modal-draft").style.display = "none";
}
</script>';
require APPPATH . 'Views/layouts/main.php';
