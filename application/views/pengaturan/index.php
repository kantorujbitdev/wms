<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Pengaturan</h1>
    </div>

    <!-- Form -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Pengaturan Sistem</h6>
        </div>
        <div class="card-body">
            <?php echo form_open('pengaturan/save'); ?>
            <div class="form-group">
                <label for="api_base_url">API Base URL</label>
                <input type="text" class="form-control" id="api_base_url" name="api_base_url"
                    value="<?php echo isset($settings['api_base_url']) ? $settings['api_base_url'] : set_value('api_base_url'); ?>"
                    required>
                <small class="form-text text-muted">URL dasar untuk API eksternal</small>
            </div>

            <div class="form-group">
                <label for="api_timeout">API Timeout (detik)</label>
                <input type="number" class="form-control" id="api_timeout" name="api_timeout" min="1"
                    value="<?php echo isset($settings['api_timeout']) ? $settings['api_timeout'] : set_value('api_timeout', 30); ?>"
                    required>
                <small class="form-text text-muted">Waktu maksimum untuk menunggu respon API</small>
            </div>

            <div class="form-group">
                <label for="items_per_page">Items Per Page</label>
                <input type="number" class="form-control" id="items_per_page" name="items_per_page" min="1"
                    value="<?php echo isset($settings['items_per_page']) ? $settings['items_per_page'] : set_value('items_per_page', 10); ?>"
                    required>
                <small class="form-text text-muted">Jumlah item per halaman di tabel</small>
            </div>

            <div class="form-group">
                <label for="app_name">Application Name</label>
                <input type="text" class="form-control" id="app_name" name="app_name"
                    value="<?php echo isset($settings['app_name']) ? $settings['app_name'] : set_value('app_name', 'Warehouse Management System'); ?>"
                    required>
                <small class="form-text text-muted">Nama aplikasi yang akan ditampilkan di header</small>
            </div>

            <div class="form-group">
                <label for="app_logo">Application Logo</label>
                <input type="text" class="form-control" id="app_logo" name="app_logo"
                    value="<?php echo isset($settings['app_logo']) ? $settings['app_logo'] : set_value('app_logo', 'assets/img/logo_warehouse.png'); ?>"
                    required>
                <small class="form-text text-muted">Path ke logo aplikasi</small>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="<?php echo site_url('pengaturan'); ?>" class="btn btn-secondary">Reset</a>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>