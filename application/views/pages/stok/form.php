<?php
$role_id = $this->session->userdata('role');
$session_warehouse_id = $this->session->userdata('warehouse_id');
?>


<div class="container-fluid">
    <h3 class="mb-4">Tambah Stok</h3>

    <div class="card shadow">
        <div class="card-body">

            <form action="<?= site_url('gudang_stok/store') ?>" method="post">

                <!-- Jika superadmin, tampilkan dropdown -->
                <?php if ($role_id === 'superadmin'): ?>
                    <div class="mb-3">
                        <label class="form-label">Nama Gudang</label>
                        <select name="warehouse_id" class="form-control select2-gudang" required>
                            <option value="">-- Pilih Gudang --</option>
                            <?php foreach ($warehouses as $w): ?>
                                <option value="<?= $w['warehouse_id'] ?>">
                                    <?= $w['warehouse_name'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Jika bukan superadmin, sembunyikan dropdown -->
                <?php else: ?>
                    <input type="hidden" name="warehouse_id" value="<?= $session_warehouse_id ?>">
                    <div class="mb-3">
                        <label class="form-label">Gudang</label>
                        <input type="text" class="form-control" value="<?= $this->session->userdata('warehouse_name'); ?>"
                            readonly style="background:#e9ecef;">
                    </div>
                <?php endif; ?>

                <div class="mb-3">
                    <label class="form-label">Produk</label>
                    <select name="product_id" class="form-control select2-produk" required>
                        <option value="">-- Pilih Produk --</option>
                        <?php foreach ($products as $p): ?>
                            <option value="<?= $p['product_id'] ?>">
                                <?= $p['product_name'] . ' || ' . $p['product_code'] . ' (' . $p['bos_code'] . ')' ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Jumlah Stok</label>
                    <input type="number" name="current_stock" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> <?= $wording['save']; ?>
                </button>
                <a href="<?= site_url('gudang_stok') ?>" class="btn btn-secondary"><?= $wording['back']; ?></a>

            </form>

        </div>
    </div>
</div>

<!-- Tambahkan script Select2 -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Inisialisasi Select2 untuk Gudang
        $('.select2-gudang').select2({
            placeholder: '-- Pilih Gudang --',
            allowClear: true,
            width: '100%'
        });

        // Inisialisasi Select2 untuk Produk dengan pencarian
        $('.select2-produk').select2({
            placeholder: '-- Pilih Produk --',
            allowClear: true,
            width: '100%',
            minimumInputLength: 3, // Minimal karakter untuk mulai mencari
            language: {
                noResults: function () {
                    return "Produk tidak ditemukan";
                },
                searching: function () {
                    return "Mencari...";
                }
            }
        });
    });
</script>

<style>
    /* Styling untuk Select2 */
    .select2-container--default .select2-selection--single {
        height: 38px;
        padding: 5px;
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 26px;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px;
    }
</style>