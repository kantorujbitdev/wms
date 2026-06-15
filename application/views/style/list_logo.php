<div id="modalPilihLogo" class="modal-logo">
    <div class="modal-content-custom">
        <div class="modal-header-custom">
            <h3>Pilih Logo Perusahaan</h3>
            <button type="button" class="btn-close-modal" onclick="hideLogoModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="logo-grid">
            <?php foreach ($logo_list as $item): ?>
                <div class="logo-card">
                    <div class="logo-preview">
                        <img src="<?= base_url($item['logo']) ?>" alt="<?= $item['nama_pt'] ?>">
                    </div>
                    <div class="logo-info">
                        <?= $item['nama_pt'] ?>
                    </div>
                    <button type="button" class="btn-pilih-logo" onclick="pilihLogo('<?= base_url($item['logo']) ?>',
                            '<?= htmlspecialchars($item['nama_pt'], ENT_QUOTES) ?>',
                            <?= $item['id_logo'] ?>
                        )">
                        <i class="fas fa-check"></i>
                        Pilih
                    </button>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>