<?php
// v_inbox.php — Inbox Kaprodi
$notifUrl = '/notifikasi';
require_once APPPATH . 'Views/partials/status_helpers.php';
ob_start();
?>

<!-- Tab Navigation -->
<div style="display:flex;gap:.5rem;margin-bottom:1.25rem;border-bottom:1px solid var(--border);padding-bottom:.75rem">
    <a href="/dashboard/kaprodi/inbox"   class="btn <?= $tab==='menunggu'?'btn-primary':'btn-outline' ?> btn-sm">Menunggu Persetujuan</a>
    <a href="/dashboard/kaprodi/riwayat" class="btn <?= $tab==='riwayat'?'btn-primary':'btn-outline' ?> btn-sm">Diteruskan ke Dekan</a>
</div>

<form action="/dashboard/kaprodi/batch-approve" method="post" id="form-batch">
    <?= csrf_field() ?>

    <div class="card">
        <div class="card-header" style="flex-wrap:wrap;gap:1rem;">
            <div>
                <div class="card-title"><?= esc($title) ?></div>
                <div class="card-sub"><?= count($daftar) ?> pengajuan terdaftar</div>
            </div>
            <!-- Live Search Bar -->
            <?php if (!empty($daftar)): ?>
            <div style="position:relative;min-width:260px;">
                <span style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:var(--muted);font-size:.85rem;">🔍</span>
                <input type="text" onkeyup="filterInbox(this)" placeholder="Cari mahasiswa / NIM / surat..." style="width:100%;padding:.45rem .65rem .45rem 2rem;border:1px solid var(--border);border-radius:8px;font-size:.82rem;outline:none;background:#ffffff;">
            </div>
            <?php endif ?>
        </div>

        <?php if (empty($daftar)): ?>
            <div class="empty-state">
                <div class="empty-icon">📭</div>
                <div class="empty-title">Tidak ada pengajuan</div>
                <div class="empty-sub">Semua berkas pada kategori ini telah diproses.</div>
            </div>
        <?php else: ?>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <?php if ($tab === 'menunggu'): ?>
                                <th style="width:38px;text-align:center;">
                                    <input type="checkbox" id="check-all" onchange="toggleSelectAll(this)" style="cursor:pointer;">
                                </th>
                            <?php else: ?>
                                <th style="width:40px">#</th>
                            <?php endif ?>
                            <th>Mahasiswa</th>
                            <th>NIM</th>
                            <th>Jenis Surat</th>
                            <th>Tanggal & SLA</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="inbox-tbody">
                        <?php foreach ($daftar as $i => $p): ?>
                        <tr>
                            <?php if ($tab === 'menunggu'): ?>
                                <td style="text-align:center;">
                                    <input type="checkbox" name="ids[]" value="<?= esc($p['id']) ?>" class="batch-chk" onchange="updateBatchBar()" style="cursor:pointer;">
                                </td>
                            <?php else: ?>
                                <td style="color:var(--muted)"><?= $i + 1 ?></td>
                            <?php endif ?>
                            <td style="font-weight:600;color:var(--text-dark)"><?= esc($p['mahasiswa_nama']) ?></td>
                            <td style="color:var(--muted);font-family:monospace;"><?= esc($p['mahasiswa_nim']) ?></td>
                            <td><?= esc(str_replace('_',' ',$p['jenis_surat'])) ?></td>
                            <td>
                                <div style="display:flex;flex-direction:column;gap:3px;">
                                    <span style="font-size:.78rem;color:var(--muted);"><?= date('d M Y, H:i', strtotime($p['created_at'])) ?></span>
                                    <div><?= slaBadge($p['created_at']) ?></div>
                                </div>
                            </td>
                            <td><?= statusBadge($p['status']) ?></td>
                            <td>
                                <div style="display:flex;gap:.4rem;align-items:center;">
                                    <button type="button" class="btn btn-outline btn-sm" onclick="showQuickPreview(this)"
                                        data-id="<?= esc($p['id']) ?>"
                                        data-nama="<?= esc($p['mahasiswa_nama']) ?>"
                                        data-nim="<?= esc($p['mahasiswa_nim']) ?>"
                                        data-prodi="<?= esc(str_replace('_', ' ', $p['mahasiswa_program'] ?? '-')) ?>"
                                        data-jenis="<?= esc(str_replace('_', ' ', $p['jenis_surat'])) ?>"
                                        data-tgl="<?= date('d M Y, H:i', strtotime($p['created_at'])) ?>"
                                        data-kep="<?= esc($p['keperluan']) ?>"
                                        data-lampiran="<?= esc($p['file_lampiran'] ?? '') ?>"
                                        data-canact="<?= ($p['status'] === 'MENUNGGU_KAPRODI') ? '1' : '0' ?>"
                                        data-url="/dashboard/kaprodi/detail/<?= esc($p['id']) ?>"
                                        title="Pratinjau Cepat Berkas">
                                        👁️ Intip
                                    </button>
                                    <a href="/dashboard/kaprodi/detail/<?= esc($p['id']) ?>" class="btn <?= $tab === 'menunggu' ? 'btn-primary' : 'btn-outline' ?> btn-sm">
                                        <?= $tab === 'menunggu' ? 'Proses' : 'Detail' ?>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        <?php endif ?>
    </div>

    <!-- Floating Batch Approval Action Bar -->
    <div id="batch-bar" class="batch-bar">
        <div style="display:flex;align-items:center;gap:.75rem;">
            <span style="font-size:1.25rem;">⚡</span>
            <div>
                <span id="selected-count" style="font-weight:800;font-size:.95rem;">0</span> berkas pengajuan terpilih
                <div style="font-size:.72rem;color:rgba(255,255,255,0.75);">Setujui sekaligus untuk diteruskan ke Dekan</div>
            </div>
        </div>
        <button type="submit" class="btn btn-accent btn-sm" style="font-weight:700;">
            ✅ Setujui Terpilih Sekaligus
        </button>
    </div>
</form>

<!-- Modal Quick Preview -->
<div id="modal-quick" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.65);z-index:200;align-items:center;justify-content:center;padding:1rem;">
    <div style="background:#ffffff;border:1px solid var(--border);border-radius:14px;max-width:540px;width:100%;box-shadow:0 12px 30px rgba(0,0,0,0.2);">
        <div style="padding:1.15rem 1.4rem;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;background:#f8fafc;border-radius:14px 14px 0 0;">
            <div style="font-weight:700;color:var(--text-dark);font-size:.9rem;display:flex;align-items:center;gap:.5rem;">
                <span>👁️</span> Pratinjau Cepat Pengajuan
            </div>
            <button type="button" onclick="closeQuickPreview()" style="background:none;border:none;font-size:1.2rem;cursor:pointer;color:var(--muted);">&times;</button>
        </div>

        <div style="padding:1.4rem;font-size:.84rem;">
            <table style="width:100%;border-collapse:collapse;margin-bottom:1rem;">
                <tr><td style="width:120px;padding:.3rem 0;color:var(--muted);font-weight:600;">Pemohon</td><td>: <strong id="qp-nama" style="color:var(--text-dark)">-</strong></td></tr>
                <tr><td style="padding:.3rem 0;color:var(--muted);font-weight:600;">NIM</td><td>: <span id="qp-nim">-</span></td></tr>
                <tr><td style="padding:.3rem 0;color:var(--muted);font-weight:600;">Program Studi</td><td>: <span id="qp-prodi">-</span></td></tr>
                <tr><td style="padding:.3rem 0;color:var(--muted);font-weight:600;">Jenis Surat</td><td>: <span id="qp-jenis" style="font-weight:700;color:var(--primary);">-</span></td></tr>
                <tr><td style="padding:.3rem 0;color:var(--muted);font-weight:600;">Tanggal Ajuan</td><td>: <span id="qp-tgl">-</span></td></tr>
            </table>

            <div style="font-size:.76rem;color:var(--muted);font-weight:700;margin-bottom:.3rem;text-transform:uppercase;">Keperluan / Keterangan:</div>
            <div id="qp-kep" style="background:#f8fafc;padding:.75rem 1rem;border-radius:8px;border:1px solid var(--border);line-height:1.5;color:var(--text);margin-bottom:1rem;max-height:140px;overflow-y:auto;">
                -
            </div>

            <div id="qp-lampiran-container" style="display:none;margin-bottom:1rem;">
                <div style="font-size:.76rem;color:var(--muted);font-weight:700;margin-bottom:.3rem;text-transform:uppercase;">Lampiran Mahasiswa:</div>
                <a id="qp-lampiran-link" href="#" target="_blank" class="btn btn-outline btn-sm" style="display:inline-flex;align-items:center;gap:.4rem;">
                    <span>📎</span> Buka Lampiran Berkas
                </a>
            </div>
        </div>

        <div style="padding:.85rem 1.4rem;border-top:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;background:#f8fafc;border-radius:0 0 14px 14px;gap:.5rem;flex-wrap:wrap;">
            <button type="button" class="btn btn-outline btn-sm" onclick="closeQuickPreview()">Tutup</button>
            <div style="display:flex;gap:.5rem;">
                <form id="qp-direct-approve-form" action="" method="post" style="display:inline;">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-primary btn-sm" id="qp-direct-approve-btn">
                        ✅ Setujui & Teruskan
                    </button>
                </form>
                <a id="qp-action-btn" href="#" class="btn btn-outline btn-sm">Buka Halaman Lengkap &raquo;</a>
            </div>
        </div>
    </div>
</div>

<?php
$pageContent = ob_get_clean();
$extraScript = '
<script>
function filterInbox(input) {
    var query = input.value.toLowerCase().trim();
    var rows = document.querySelectorAll("#inbox-tbody tr");
    rows.forEach(function(row) {
        var text = row.innerText.toLowerCase();
        row.style.display = text.indexOf(query) > -1 ? "" : "none";
    });
}

function toggleSelectAll(master) {
    var chks = document.querySelectorAll(".batch-chk");
    chks.forEach(function(c) { c.checked = master.checked; });
    updateBatchBar();
}

function updateBatchBar() {
    var checked = document.querySelectorAll(".batch-chk:checked");
    var bar = document.getElementById("batch-bar");
    var counter = document.getElementById("selected-count");
    if (bar && counter) {
        counter.innerText = checked.length;
        bar.style.display = checked.length > 0 ? "flex" : "none";
    }
}

function showQuickPreview(btn) {
    document.getElementById("qp-nama").innerText = btn.getAttribute("data-nama");
    document.getElementById("qp-nim").innerText = btn.getAttribute("data-nim");
    document.getElementById("qp-prodi").innerText = btn.getAttribute("data-prodi");
    document.getElementById("qp-jenis").innerText = btn.getAttribute("data-jenis");
    document.getElementById("qp-tgl").innerText = btn.getAttribute("data-tgl");
    document.getElementById("qp-kep").innerText = btn.getAttribute("data-kep");
    document.getElementById("qp-action-btn").href = btn.getAttribute("data-url");

    var id = btn.getAttribute("data-id");
    var canAct = btn.getAttribute("data-canact");
    var directForm = document.getElementById("qp-direct-approve-form");
    if (canAct === "1") {
        directForm.action = "/dashboard/kaprodi/approve/" + id;
        directForm.style.display = "inline";
    } else {
        directForm.style.display = "none";
    }

    var lampiran = btn.getAttribute("data-lampiran");
    var lContainer = document.getElementById("qp-lampiran-container");
    var lLink = document.getElementById("qp-lampiran-link");
    if (lampiran) {
        lLink.href = "/uploads/lampiran/" + lampiran;
        lContainer.style.display = "block";
    } else {
        lContainer.style.display = "none";
    }

    document.getElementById("modal-quick").style.display = "flex";
}

function closeQuickPreview() {
    document.getElementById("modal-quick").style.display = "none";
}
</script>';
require APPPATH . 'Views/layouts/main.php';
