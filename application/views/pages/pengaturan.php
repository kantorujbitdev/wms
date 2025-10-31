<div class="card">
    <div class="card-header">
        <h3 class="card-title">Pengaturan Sistem</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <?php echo form_open('pengaturan'); ?>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="api_base_url">API Base URL <span class="text-danger">*</span></label>
                    <input type="url" class="form-control" id="api_base_url" name="api_base_url"
                        value="<?php echo isset($settings['data']['api_base_url']) ? $settings['data']['api_base_url'] : set_value('api_base_url'); ?>"
                        required>
                    <?php echo form_error('api_base_url', '<div class="text-danger">', '</div>'); ?>
                    <small class="form-text text-muted">URL dasar untuk API eksternal</small>
                </div>

                <div class="form-group">
                    <label for="app_name">Nama Aplikasi <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="app_name" name="app_name"
                        value="<?php echo isset($settings['data']['app_name']) ? $settings['data']['app_name'] : set_value('app_name'); ?>"
                        required>
                    <?php echo form_error('app_name', '<div class="text-danger">', '</div>'); ?>
                </div>

                <div class="form-group">
                    <label for="app_logo">Logo Aplikasi <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="app_logo" name="app_logo"
                        value="<?php echo isset($settings['data']['app_logo']) ? $settings['data']['app_logo'] : set_value('app_logo'); ?>"
                        required>
                    <?php echo form_error('app_logo', '<div class="text-danger">', '</div>'); ?>
                    <small class="form-text text-muted">Path untuk logo aplikasi</small>
                </div>

                <div class="form-group">
                    <label for="items_per_page">Item per Halaman <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" id="items_per_page" name="items_per_page"
                        value="<?php echo isset($settings['data']['items_per_page']) ? $settings['data']['items_per_page'] : set_value('items_per_page'); ?>"
                        required>
                    <?php echo form_error('items_per_page', '<div class="text-danger">', '</div>'); ?>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="date_format">Format Tanggal</label>
                    <select class="form-control" id="date_format" name="date_format">
                        <option value="d-m-Y" <?php echo (isset($settings['data']['date_format']) && $settings['data']['date_format'] == 'd-m-Y') ? 'selected' : ''; ?>>DD-MM-YYYY</option>
                        <option value="m-d-Y" <?php echo (isset($settings['data']['date_format']) && $settings['data']['date_format'] == 'm-d-Y') ? 'selected' : ''; ?>>MM-DD-YYYY</option>
                        <option value="Y-m-d" <?php echo (isset($settings['data']['date_format']) && $settings['data']['date_format'] == 'Y-m-d') ? 'selected' : ''; ?>>YYYY-MM-DD</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="time_format">Format Waktu</label>
                    <select class="form-control" id="time_format" name="time_format">
                        <option value="H:i:s" <?php echo (isset($settings['data']['time_format']) && $settings['data']['time_format'] == 'H:i:s') ? 'selected' : ''; ?>>24 Jam (HH:MM:SS)</option>
                        <option value="h:i:s A" <?php echo (isset($settings['data']['time_format']) && $settings['data']['time_format'] == 'h:i:s A') ? 'selected' : ''; ?>>12 Jam (HH:MM:SS AM/PM)
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="timezone">Zona Waktu</label>
                    <select class="form-control" id="timezone" name="timezone">
                        <option value="Asia/Jakarta" <?php echo (isset($settings['data']['timezone']) && $settings['data']['timezone'] == 'Asia/Jakarta') ? 'selected' : ''; ?>>WIB (UTC+7)</option>
                        <option value="Asia/Makassar" <?php echo (isset($settings['data']['timezone']) && $settings['data']['timezone'] == 'Asia/Makassar') ? 'selected' : ''; ?>>WITA (UTC+8)</option>
                        <option value="Asia/Jayapura" <?php echo (isset($settings['data']['timezone']) && $settings['data']['timezone'] == 'Asia/Jayapura') ? 'selected' : ''; ?>>WIT (UTC+9)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="low_stock_threshold">Batas Stok Menipis</label>
                    <input type="number" class="form-control" id="low_stock_threshold" name="low_stock_threshold"
                        value="<?php echo isset($settings['data']['low_stock_threshold']) ? $settings['data']['low_stock_threshold'] : set_value('low_stock_threshold'); ?>">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input class="custom-control-input" type="checkbox" id="email_notification"
                            name="email_notification" <?php echo (isset($settings['data']['email_notification']) && $settings['data']['email_notification'] == 1) ? 'checked' : ''; ?>>
                        <label for="email_notification" class="custom-control-label">Aktifkan Notifikasi Email</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Simpan Pengaturan</button>
                <button type="button" class="btn btn-info" id="test-api">Test Koneksi API</button>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
    <!-- /.card-body -->
</div>
<!-- /.card -->

<!-- Test API Modal -->
<div class="modal fade" id="testApiModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Test Koneksi API</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="test_api_url">API URL</label>
                    <input type="text" class="form-control" id="test_api_url"
                        value="<?php echo isset($settings['data']['api_base_url']) ? $settings['data']['api_base_url'] : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="test_api_token">API Token</label>
                    <input type="text" class="form-control" id="test_api_token"
                        value="<?php echo $this->session->userdata('api_token'); ?>">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="execute-test">Test</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        // Test API button click
        $('#test-api').click(function () {
            $('#testApiModal').modal('show');
        });

        // Execute test button click
        $('#execute-test').click(function () {
            var api_url = $('#test_api_url').val();
            var api_token = $('#test_api_token').val();

            $.ajax({
                url: '<?php echo site_url('pengaturan/test_api'); ?>',
                type: 'POST',
                data: {
                    api_base_url: api_url,
                    api_token: api_token
                },
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message
                        });
                    }
                },
                error: function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Terjadi kesalahan saat melakukan test koneksi'
                    });
                }
            });
        });
    });
</script>