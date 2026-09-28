<?php
include_once "config/Database.php";
require_once "models/Bobot.php";

$db = new Database();
$conn = $db->connect();

$bobotModel = new Bobot($conn);
$settings   = $bobotModel->get();
?>

<div class="col-lg-9 mt-2">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold text-primary"><i class="fa-solid fa-sliders me-2"></i>Pengaturan Pembagian Bobot Penilaian STS</h5>
        </div>
        <div class="card-body">
            <div class="alert alert-light border small text-muted mb-4 py-2">
                <i class="fa-solid fa-calculator text-primary me-1"></i>
                Nilai Akhir Rapor STS dihitung secara otomatis menggunakan rumus:
                <br>
                <code>Nilai Akhir = ((Rata-rata Sumatif 1, 2, 3 &times; Bobot Sumatif) + (Nilai STS &times; Bobot STS)) / (Bobot Sumatif + Bobot STS)</code>
            </div>

            <form action="controllers/bobot.php" method="POST" class="col-lg-8">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Bobot Rata-rata Sumatif Materi (%)</label>
                        <div class="input-group">
                            <input type="number" step="0.1" min="0" max="100" class="form-control" name="bobot_sumatif" value="<?= (float)$settings['bobot_sumatif'] ?>" required>
                            <span class="input-group-text">%</span>
                        </div>
                        <small class="text-muted">Akumulasi Nilai Sumatif 1, 2, dan 3</small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Bobot Asesmen STS (%)</label>
                        <div class="input-group">
                            <input type="number" step="0.1" min="0" max="100" class="form-control" name="bobot_sts" value="<?= (float)$settings['bobot_sts'] ?>" required>
                            <span class="input-group-text">%</span>
                        </div>
                        <small class="text-muted">Asesmen Tengah Semester</small>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Kriteria Ketercapaian Tujuan Pembelajaran (KKM/KKTP)</label>
                        <input type="number" step="0.1" min="0" max="100" class="form-control" name="kkm" value="<?= (float)$settings['kkm'] ?>" required>
                        <small class="text-muted">Siswa dengan nilai akhir &ge; KKM dinyatakan "Tercapai"</small>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary px-4">
                    <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Pengaturan
                </button>
            </form>
        </div>
    </div>
</div>
