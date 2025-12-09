<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">Nama Pengaturan</label>
                    <input type="text" class="form-control" name="nama_pengaturan"
                        value="<?= $item['nama_pengaturan']; ?>" readonly>
                </div>
                
                <?php if ($item['is_image'] == 'true'): ?>
                    <!-- Current Image Preview -->
                    <div class="mb-3">
                        <label class="form-label">Gambar Saat Ini</label>
                        <div>
                            <?php 
                            $image_path = base_url($item['value']);
                            $image_info = @getimagesize($item['value']);
                            
                            if ($image_info !== false): 
                            ?>
                                <img src="<?= $image_path; ?>" alt="<?= $item['nama_pengaturan']; ?>" 
                                     class="img-thumbnail" style="max-height: 150px;" 
                                     onerror="this.onerror=null; this.src='<?= base_url('assets/images/no-image.png'); ?>';">
                            <?php else: ?>
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle me-2"></i> Gambar tidak ditemukan atau path tidak valid
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Upload New Image -->
                    <div class="mb-3">
                        <label class="form-label">Upload Gambar Baru</label>
                        <input type="file" class="form-control" name="image" accept="image/*">
                        <small class="text-muted">Kosongkan jika tidak ingin mengubah gambar</small>
                    </div>
                    
                    <!-- Hidden field for current image path -->
                    <input type="hidden" name="current_image" value="<?= $item['value']; ?>">
                <?php else: ?>
                    <!-- Text Value -->
                    <div class="mb-3">
                        <label class="form-label">Value</label>
                        <input type="text" class="form-control" name="value" value="<?= $item['value']; ?>" required>
                    </div>
                <?php endif; ?>
                
                <button class="btn btn-primary" type="submit"><?= $wording['save']; ?></button>
                <a href="<?= site_url('pengaturan'); ?>" class="btn btn-secondary"><?= $wording['back']; ?></a>
            </form>
        </div>
    </div>
</div>