<!-- C:\xampp\htdocs\wms\application\views\pages\penerimaan\form.php -->
<div class="container-fluid">
    <?php 
    $back_url = 'penerimaan/dari_supplier';
    if ($from_status == '1') {
        $back_url = 'penerimaan/dari_pengguna';
    } elseif ($from_status == '3') {
        $back_url = 'penerimaan/antar_gudang';
    }
    
    // Prepare products data for JavaScript
    $products_js = [];
    foreach ($products as $product) {
        $products_js[] = [
            'product_id' => $product['product_id'],
            'product_code' => $product['product_code'],
            'product_name' => $product['product_name'],
            'display_name' => htmlspecialchars($product['product_code'] . ' - ' . $product['product_name'], ENT_QUOTES, 'UTF-8')
        ];
    }
    ?>
    
    <!-- Form -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <a href="<?= site_url($back_url) ?>" class="btn btn-secondary btn-sm mb-4">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i>
                <?= $wording['back']; ?>
            </a>
            <h6 class="m-0 fw-bold text-primary">Form <?= $title ?></h6>
        </div>
        <div class="card-body">
            <form id="penerimaanForm" action="<?= site_url('penerimaan/create') ?>" method="POST">
                <input type="hidden" name="from_status" value="<?= $from_status ?>">

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="stockin_date" class="form-label">Tanggal Penerimaan *</label>
                            <input type="date" class="form-control" id="stockin_date" name="stockin_date"
                                value="<?= isset($old_form_data['stockin_date']) ? $old_form_data['stockin_date'] : date('Y-m-d') ?>" 
                                max="<?= date('Y-m-d') ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="stockin_code" class="form-label">Kode Penerimaan *</label>
                            <?php
                            // Generate kode awal berdasarkan gudang default
                            $default_warehouse_code = 'WH';
                            $default_warehouse_name = 'Pilih Gudang';

                            if ($user_role != 'superadmin' && isset($user_warehouse_id)) {
                                // Untuk non-superadmin, gunakan gudang mereka
                                foreach ($warehouses as $wh) {
                                    if ($wh['warehouse_id'] == $user_warehouse_id) {
                                        $default_warehouse_code = $wh['warehouse_code'];
                                        $default_warehouse_name = $wh['warehouse_name'];
                                        break;
                                    }
                                }
                            } elseif (isset($old_form_data['to_warehouse_id']) && !empty($old_form_data['to_warehouse_id'])) {
                                // Jika ada data lama, gunakan gudang dari data lama
                                foreach ($warehouses as $wh) {
                                    if ($wh['warehouse_id'] == $old_form_data['to_warehouse_id']) {
                                        $default_warehouse_code = $wh['warehouse_code'];
                                        $default_warehouse_name = $wh['warehouse_name'];
                                        break;
                                    }
                                }
                            }
                            
                            // Tentukan kode prefix
                            $kode_prefix = 'RI/'; // Default dari supplier
                            if ($from_status == '3') {
                                $kode_prefix = 'TI/'; // Transfer in (antar gudang)
                            } elseif ($from_status == '1') {
                                $kode_prefix = 'RI/'; // Return in (dari pengguna)
                            }
                            
                            $romanMonth = monthToRoman(date('m'));
                            $stockin_code = $kode_prefix . $default_warehouse_code . '/' . $romanMonth . '/' . date('Y');

                            if (isset($old_form_data['stockin_code'])) {
                                $stockin_code = $old_form_data['stockin_code'];
                            }
                            ?>
                    
                            <input type="text" class="form-control bg-light" id="stockin_code" name="stockin_code"
                                value="<?= $stockin_code ?>" readonly>
                            <input type="hidden" id="warehouse_data"
                                value='<?= json_encode(array_column($warehouses, 'warehouse_code', 'warehouse_id')) ?>'>
                            
                        </div>
                    </div>
                </div>

                <!-- Form untuk Penerimaan dari Pengguna (from_status = 1) -->
                <?php if ($from_status == '1'): ?>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="customer_id" class="form-label">Dari Pengguna *</label>
                                <select class="form-control select2" id="customer_id" name="from_id" required>
                                    <option value="">Pilih Pengguna</option>
                                    <?php 
                                    $selected_customer = isset($old_form_data['from_id']) ? $old_form_data['from_id'] : '';
                                    foreach ($customers as $customer): 
                                    ?>
                                        <option value="<?= $customer['id'] ?>" 
                                            <?= ($selected_customer == $customer['id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($customer['name'], ENT_QUOTES, 'UTF-8') ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="stockin_invoice" class="form-label">No Referensi *</label>
                                <input type="text" class="form-control" id="stockin_invoice" name="stockin_invoice"
                                    value="<?= isset($old_form_data['stockin_invoice']) ? $old_form_data['stockin_invoice'] : '' ?>"
                                    placeholder="Masukkan nomor referensi" required>
                            </div>
                        </div>
                    </div>

                <!-- Form untuk Penerimaan dari Supplier (from_status = 2) -->
                <?php elseif ($from_status == '2'): ?>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="supplier_id" class="form-label">Dari Supplier *</label>
                                <select class="form-control select2" id="supplier_id" name="supplier_id" required>
                                    <option value="">Pilih Supplier</option>
                                    <?php 
                                    $selected_supplier = isset($old_form_data['supplier_id']) ? $old_form_data['supplier_id'] : '';
                                    foreach ($suppliers as $supplier): 
                                    ?>
                                        <option value="<?= $supplier['id'] ?>"
                                            <?= ($selected_supplier == $supplier['id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($supplier['name'], ENT_QUOTES, 'UTF-8') ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="stockin_invoice" class="form-label">No Invoice *</label>
                                <input type="text" class="form-control" id="stockin_invoice" name="stockin_invoice"
                                    value="<?= isset($old_form_data['stockin_invoice']) ? $old_form_data['stockin_invoice'] : '' ?>"
                                    placeholder="Masukkan nomor invoice" required>
                            </div>
                        </div>
                    </div>

                <!-- Form untuk Penerimaan Antar Gudang (from_status = 3) -->
                <?php elseif ($from_status == '3'): ?>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="from_warehouse_id" class="form-label">Dari Gudang *</label>
                                <select class="form-control select2" id="from_warehouse_id" name="from_id" required>
                                    <option value="">Pilih Gudang Asal</option>
                                    <?php 
                                    $selected_from_warehouse = isset($old_form_data['from_id']) ? $old_form_data['from_id'] : '';
                                    foreach ($warehouses as $warehouse): 
                                        // Untuk non-superadmin, jangan tampilkan gudang mereka sendiri
                                        if ($user_role != 'superadmin' && $warehouse['warehouse_id'] == $user_warehouse_id) {
                                            continue;
                                        }
                                    ?>
                                        <option value="<?= $warehouse['warehouse_id'] ?>"
                                            <?= ($selected_from_warehouse == $warehouse['warehouse_id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($warehouse['warehouse_name'], ENT_QUOTES, 'UTF-8') ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <?php if ($user_role != 'superadmin'): ?>
                                    <div class="form-text">Tidak bisa memilih gudang sendiri</div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="stockin_invoice" class="form-label">No Referensi *</label>
                                <input type="text" class="form-control" id="stockin_invoice" name="stockin_invoice"
                                    value="<?= isset($old_form_data['stockin_invoice']) ? $old_form_data['stockin_invoice'] : '' ?>"
                                    placeholder="Masukkan nomor referensi transfer" required>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="to_warehouse_id" class="form-label">Ke Gudang *</label>
                            <?php 
                            $selected_to_warehouse = '';
                            if (isset($old_form_data['to_warehouse_id'])) {
                                $selected_to_warehouse = $old_form_data['to_warehouse_id'];
                            } elseif ($user_role != 'superadmin') {
                                $selected_to_warehouse = $user_warehouse_id;
                            }
                            ?>
                            
                            <?php if ($user_role == 'superadmin'): ?>
                                <!-- Superadmin dapat memilih gudang tujuan -->
                                <select class="form-control select2" id="to_warehouse_id" name="to_warehouse_id" required>
                                    <option value="">Pilih Gudang Tujuan</option>
                                    <?php foreach ($warehouses as $warehouse): ?>
                                        <option value="<?= $warehouse['warehouse_id'] ?>" 
                                            <?= ($selected_to_warehouse == $warehouse['warehouse_id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($warehouse['warehouse_name'], ENT_QUOTES, 'UTF-8') ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            <?php else: ?>
                                <!-- Non-superadmin hanya bisa melihat gudang mereka sendiri -->
                                <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($user_warehouse_name, ENT_QUOTES, 'UTF-8') ?>" readonly>
                                <input type="hidden" name="to_warehouse_id" value="<?= $user_warehouse_id ?>">
                                <div class="form-text">Gudang tujuan berdasarkan login Anda</div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="stockin_note" class="form-label">Keterangan</label>
                            <textarea class="form-control" id="stockin_note" name="stockin_note"
                                placeholder="Masukkan keterangan tambahan" rows="2"><?= isset($old_form_data['stockin_note']) ? htmlspecialchars($old_form_data['stockin_note'], ENT_QUOTES, 'UTF-8') : '' ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Tipe Penerimaan</label>
                            <?php
                            $tipe_text = 'Dari Supplier';
                            if ($from_status == '1')
                                $tipe_text = 'Dari Pengguna';
                            elseif ($from_status == '3')
                                $tipe_text = 'Antar Gudang';
                            ?>
                            <input type="text" class="form-control bg-light" value="<?= $tipe_text ?>" readonly>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <!-- Items Section -->
                <div class="row mb-3">
                    <div class="col-12">
                        <h5 class="fw-bold">Detail Barang</h5>
                        <button type="button" id="addItem" class="btn btn-success btn-sm">
                            <i class="fas fa-plus"></i> Tambah Barang
                        </button>
                    </div>
                </div>

                <div id="itemsContainer">
                    <?php if (isset($old_form_data['items']) && !empty($old_form_data['items'])): ?>
                        <!-- Tampilkan item dari session jika ada -->
                        <?php foreach ($old_form_data['items'] as $index => $item): ?>
                            <div class="item-row row mb-3">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Produk *</label>
                                        <select class="form-control select2 product-select" name="product_id[]" required>
                                            <option value="">Pilih Produk</option>
                                            <?php foreach ($products as $product): 
                                                $display_name = htmlspecialchars($product['product_code'] . ' - ' . $product['product_name'], ENT_QUOTES, 'UTF-8');
                                            ?>
                                                <option value="<?= $product['product_id'] ?>"
                                                    <?= (isset($item['product_id']) && $item['product_id'] == $product['product_id']) ? 'selected' : '' ?>
                                                    data-display="<?= $display_name ?>">
                                                    <?= $display_name ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label class="form-label">Qty *</label>
                                        <input type="number" class="form-control qty-input" name="qty[]" step="0.01" min="0.01" 
                                            value="<?= isset($item['qty']) ? $item['qty'] : '' ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Keterangan Barang</label>
                                        <input type="text" class="form-control" name="detail_note[]"
                                            value="<?= isset($item['detail_note']) ? htmlspecialchars($item['detail_note'], ENT_QUOTES, 'UTF-8') : '' ?>"
                                            placeholder="Keterangan tambahan untuk barang ini">
                                    </div>
                                </div>
                                <div class="col-md-2 mt-4">
                                    <div class="mb-3">
                                        <label class="form-label">&nbsp;</label>
                                        <button type="button" class="btn btn-danger btn-block remove-item">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <!-- Default: satu row kosong -->
                        <div class="item-row row mb-3">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Produk *</label>
                                    <select class="form-control select2 product-select" name="product_id[]" required>
                                        <option value="">Pilih Produk</option>
                                        <?php foreach ($products as $product): 
                                            $display_name = htmlspecialchars($product['product_code'] . ' - ' . $product['product_name'], ENT_QUOTES, 'UTF-8');
                                        ?>
                                            <option value="<?= $product['product_id'] ?>" data-display="<?= $display_name ?>">
                                                <?= $display_name ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label class="form-label">Qty *</label>
                                    <input type="number" class="form-control qty-input" name="qty[]" step="0.01" min="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Keterangan Barang</label>
                                    <input type="text" class="form-control" name="detail_note[]"
                                        placeholder="Keterangan tambahan untuk barang ini">
                                </div>
                            </div>
                            <div class="col-md-2 mt-4">
                                <div class="mb-3">
                                    <label class="form-label">&nbsp;</label>
                                    <button type="button" class="btn btn-danger btn-block remove-item" disabled>
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <hr class="my-4">

                <div class="row">
                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Penerimaan
                        </button>
                        <a href="<?= site_url($back_url) ?>" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Batal
                        </a>
                        <?php if (isset($old_form_data)): ?>
                            <button type="button" id="clearForm" class="btn btn-warning">
                                <i class="fas fa-broom"></i> Bersihkan Form
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- form_script.php file is included separately for JS code -->
<?php $this->load->view('pages/penerimaan/form_script.php'); ?>