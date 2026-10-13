<div class="container-fluid">
    <!-- Header RAP -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Edit RAP</h6>
        </div>
        <div class="card-body">
            <?php echo form_open('rap/save', ['id' => 'rapForm']); ?>
            <input type="hidden" name="id" value="<?php echo $rap['rap_id']; ?>">

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="warehouse_id" class="form-label">Gudang Proyek</label>
                    <select class="form-control" id="warehouse_id" name="warehouse_id" required>
                        <option value="">-- Pilih Gudang Proyek --</option>
                        <?php foreach ($proyeks as $proyek): ?>
                            <option value="<?php echo $proyek['warehouse_id']; ?>"
                                <?php echo ($rap['warehouse_id'] == $proyek['warehouse_id']) ? 'selected' : ''; ?>>
                                <?php echo $proyek['warehouse_name']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="name" class="form-label">Nama Proyek</label>
                    <input type="text" class="form-control" id="name" name="name"
                        value="<?php echo $rap['rap_name']; ?>" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="contract_number" class="form-label">Nomor Kontrak</label>
                    <input type="text" class="form-control" id="contract_number" name="contract_number"
                        value="<?php echo $rap['contract_number']; ?>" required>
                </div>
            </div>

            <div class="mb-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i> <?= $wording['save']; ?>
                </button>
                <a href="<?php echo site_url('rap'); ?>" class="btn btn-secondary">
                    <i class="fas fa-times me-2"></i> <?= $wording['cancel']; ?>
                </a>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>

    <!-- Kategori & Sub Kategori -->
   <div class="card shadow mb-4">
    <div class="card-header py-3 d-flex align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Kategori RAP</h6>
        <button type="button" class="btn btn-primary btn-sm" id="btnAddCategory" data-parent="">
            <i class="fas fa-plus fa-sm"></i> Tambah Kategori
        </button>
    </div>
    <div class="card-body">
        <ul class="list-group" id="categoryTree">
            <?php if (empty($categories)): ?>
                <li class="list-group-item text-muted">Belum ada kategori.</li>
            <?php else: ?>
                <?php
                function render_category_tree($items, $rapId)
                {
                    foreach ($items as $item) {
                        echo '<li class="list-group-item">';
                        echo '<div class="d-flex justify-content-between align-items-center">';
                        echo '<span>' . htmlspecialchars($item['category_name']) . '</span>';
                        echo '<span>';
                        echo '<button type="button" class="btn btn-info btn-sm btnAddSub" '
                            . 'data-parent="' . $item['category_id'] . '" title="Tambah Sub Kategori">'
                            . '<i class="fas fa-plus"></i></button> ';
                        echo '<button type="button" class="btn btn-danger btn-sm btnDeleteCategory" '
                            . 'data-id="' . $item['category_id'] . '" data-rap="' . $rapId . '" '
                            . 'data-name="' . htmlspecialchars($item['category_name']) . '" title="Hapus">'
                            . '<i class="fas fa-trash"></i></button>';
                        echo '</span></div>';

                        if (!empty($item['sub_category'])) {
                            echo '<ul class="list-group mt-2 ms-4">';
                            render_category_tree($item['sub_category'], $rapId);
                            echo '</ul>';
                        }
                        echo '</li>';
                    }
                }
                render_category_tree($categories, $rap['rap_id']);
                ?>
            <?php endif; ?>
        </ul>
    </div>
</div>
</div>

<!-- Modal Tambah Kategori/Sub Kategori -->
<div class="modal fade" id="modalAddCategory" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAddCategoryTitle">Tambah Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="parentCategoryId" value="">
                <div id="categoryInputList">
                    <div class="input-group mb-2">
                        <input type="text" class="form-control categoryNameInput" placeholder="Nama kategori">
                        <button type="button" class="btn btn-outline-danger btnRemoveInput" style="display:none;">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <button type="button" class="btn btn-outline-primary btn-sm" id="btnAddInputRow">
                    <i class="fas fa-plus"></i> Tambah baris
                </button>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btnSubmitCategory">Simpan</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        var rapId = <?php echo $rap['rap_id']; ?>;

        // Form header RAP
        $('#rapForm').on('submit', function (e) {
            e.preventDefault();
            showConfirmationModal({
                title: 'Konfirmasi Simpan',
                message: 'Apakah data yang Anda masukkan sudah benar?',
                confirmText: 'Ya, Simpan',
                confirmClass: 'btn-primary',
                onConfirm: function () {
                    $('#rapForm').unbind('submit').submit();
                }
            });
        });

        // Buka modal tambah kategori (parent kosong)
        $('#btnAddCategory').on('click', function () {
            openCategoryModal('', 'Tambah Kategori');
        });

        // Buka modal tambah sub kategori
        $(document).on('click', '.btnAddSub', function () {
            var parentId = $(this).data('parent');
            openCategoryModal(parentId, 'Tambah Sub Kategori');
        });

        function openCategoryModal(parentId, title) {
            $('#parentCategoryId').val(parentId);
            $('#modalAddCategoryTitle').text(title);
            $('#categoryInputList').html(
                '<div class="input-group mb-2">' +
                '<input type="text" class="form-control categoryNameInput" placeholder="Nama kategori">' +
                '<button type="button" class="btn btn-outline-danger btnRemoveInput" style="display:none;">' +
                '<i class="fas fa-times"></i></button></div>'
            );
            $('#modalAddCategory').modal('show');
        }

        // Tambah baris input nama kategori (multi input)
        $('#btnAddInputRow').on('click', function () {
            $('#categoryInputList').append(
                '<div class="input-group mb-2">' +
                '<input type="text" class="form-control categoryNameInput" placeholder="Nama kategori">' +
                '<button type="button" class="btn btn-outline-danger btnRemoveInput">' +
                '<i class="fas fa-times"></i></button></div>'
            );
            $('.btnRemoveInput').show();
        });

        $(document).on('click', '.btnRemoveInput', function () {
            $(this).closest('.input-group').remove();
        });

        // Submit tambah kategori/sub kategori
        $('#btnSubmitCategory').on('click', function () {
            var parentId = $('#parentCategoryId').val();
            var names = [];
            $('.categoryNameInput').each(function () {
                var val = $(this).val().trim();
                if (val) names.push(val);
            });

            if (names.length === 0) {
                alert('Isi minimal 1 nama kategori.');
                return;
            }

            $.ajax({
                url: '<?php echo site_url('rap/category_save'); ?>',
                method: 'POST',
                data: {
                    rap_id: rapId,
                    parent_category_id: parentId,
                    names: names
                },
                success: function (res) {
                    if (res.success) {
                        location.reload();
                    } else {
                        alert(res.message || 'Gagal menyimpan kategori.');
                    }
                },
                error: function () {
                    alert('Terjadi kesalahan saat menyimpan kategori.');
                }
            });
        });

        // Hapus kategori/sub kategori
        $(document).on('click', '.btnDeleteCategory', function () {
            var categoryId = $(this).data('id');
            var rapIdBtn = $(this).data('rap');
            var name = $(this).data('name');

            showConfirmationModal({
                title: 'Konfirmasi Hapus',
                message: 'Hapus kategori "' + name + '"? Sub kategori di dalamnya juga akan terhapus.',
                confirmText: 'Ya, Hapus',
                confirmClass: 'btn-danger',
                onConfirm: function () {
                    $.ajax({
                        url: '<?php echo site_url('rap/category_delete'); ?>',
                        method: 'POST',
                        data: { rap_id: rapIdBtn, category_id: categoryId },
                        success: function (res) {
                            if (res.success) {
                                location.reload();
                            } else {
                                alert(res.message || 'Gagal menghapus kategori.');
                            }
                        },
                        error: function () {
                            alert('Terjadi kesalahan saat menghapus kategori.');
                        }
                    });
                }
            });
        });
    });
</script>