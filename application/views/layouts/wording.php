<?php
// Common wording and labels used throughout the application
$wording = [
    // General
    'dashboard' => 'Dashboard',
    'save' => 'Simpan',
    'cancel' => 'Batal',
    'edit' => 'Edit',
    'delete' => 'Hapus',
    'add' => 'Tambah',
    'search' => 'Cari',
    'back' => 'Kembali',
    'submit' => 'Kirim',
    'close' => 'Tutup',
    'yes' => 'Ya',
    'no' => 'Tidak',
    'confirm' => 'Konfirmasi',
    'success' => 'Berhasil',
    'error' => 'Error',
    'warning' => 'Peringatan',
    'info' => 'Informasi',

    // Auth
    'login' => 'Login',
    'logout' => 'Logout',
    'username' => 'Username',
    'password' => 'Password',
    'remember_me' => 'Ingat saya',

    // Tipe Barang
    'tipe' => 'Tipe',
    'tipe_produk' => 'Tipe Barang',
    'barang_tipe_list' => 'Daftar Tipe Barang',
    'barang_tipe_add' => 'Tambah Tipe Barang',

    // Satuan
    'unit' => 'Satuan',
    'tipe_satuan' => 'Tipe Satuan',
    'satuan_list' => 'Daftar Tipe Satuan',
    'satuan_add' => 'Tambah Tipe Satuan',

    // Barang
    'barang' => 'Barang',
    'master_barang' => 'Master Barang',
    'barang_list' => 'Daftar Barang',
    'barang_add' => 'Tambah Barang',
    'barang_edit' => 'Edit Barang',
    'barang_name' => 'Nama Barang',
    'barang_code' => 'Kode Barang',
    'barang_category' => 'Kategori',
    'barang_unit' => 'Satuan',
    'barang_price' => 'Harga',
    'barang_description' => 'Deskripsi',
    // Master Pengguna
    // Customer
    'customer' => 'Master Pengguna',
    'customer_list' => 'Daftar Master Pengguna',
    'customer_add' => 'Tambah Pengguna',
    'customer_edit' => 'Edit Pengguna',
    'customer_form' => 'Form Pengguna',

    // Supplier
    'supplier' => 'Master Supplier',
    'supplier_list' => 'DaftarSupplier',
    'supplier_add' => 'Tambah Supplier',
    'supplier_edit' => 'Edit Supplier',
    'supplier_form' => 'Form Supplier',

    // Penerimaan
    'penerimaan' => 'Penerimaan',
    'penerimaan_list' => 'Daftar Penerimaan Barang',

    // Pengiriman
    'pengiriman' => 'Pengiriman',
    'pengiriman_list' => 'Daftar Pengiriman Barang',

    // Gudang
    'gudang' => 'Master Gudang',
    'gudang_list' => 'Daftar Master Gudang',
    'gudang_add' => 'Tambah Master Gudang',
    'gudang_edit' => 'Edit Master Gudang',
    'gudang_name' => 'Nama Master Gudang',
    'gudang_form_utama' => 'Form Gudang Utama',
    'gudang_code' => 'Kode Master Gudang',
    'gudang_address' => 'Alamat',
    'gudang_capacity' => 'Kapasitas',
    'gudang_stock' => 'Stok Gudang',


    'gudang_project' => 'Master Gudang Project',
    'gudang_list_project' => 'Daftar Master Gudang Project',
    'gudang_add_project' => 'Tambah Master Gudang Project',
    'gudang_edit_project' => 'Edit Master Gudang Project',
    'gudang_form_project' => 'Form Gudang Project',

    // Transaksi
    'transaksi' => 'Transaksi',
    'transaksi_list' => 'Daftar Transaksi',
    'transaksi_masuk' => 'Barang Masuk',
    'transaksi_keluar' => 'Barang Keluar',
    'transaksi_transfer' => 'Transfer Stok',
    'transaksi_date' => 'Tanggal',
    'transaksi_type' => 'Jenis',
    'transaksi_quantity' => 'Jumlah',
    'transaksi_notes' => 'Catatan',
    'transaksi_from' => 'Dari',
    'transaksi_to' => 'Ke',

    // Laporan
    'laporan' => 'Laporan',
    'laporan_stock' => 'Laporan Stok',
    'laporan_transaksi' => 'Laporan Transaksi',
    'laporan_barang' => 'Laporan Barang',
    'laporan_generate' => 'Generate Laporan',
    'laporan_export' => 'Export',

    // User
    'user' => 'Master User',
    'user_management' => 'Kelola User',
    'user_list' => 'Daftar User',
    'user_add' => 'Tambah User',
    'user_edit' => 'Edit User',
    'user_name' => 'Nama Lengkap',
    'user_email' => 'Email',
    'user_role' => 'Role',
    'user_status' => 'Status',

    //Stok 
    'stok' => 'Stok Gudang',
    'gudang_stok' => 'Stok Gudang',
    'stok_add' => 'Tambah Stok Gudang',
    'stok_list' => 'Daftar Stok Gudang',

    // Pengaturan
    'pengaturan' => 'Pengaturan',
    'pengaturan_list' => 'Daftar Pengaturan',
    'pengaturan_api' => 'Pengaturan API',
    'pengaturan_base_url' => 'Base URL',
    'pengaturan_token' => 'Token',

    // Roles
    'role_admin' => 'admin',
    'role_supervisor' => 'Supervisor',
    'role_staff' => 'Staff',

    // Messages
    'msg_save_success' => 'Data berhasil disimpan!',
    'msg_delete_success' => 'Data berhasil dihapus!',
    'msg_delete_confirm' => 'Apakah Anda yakin ingin menghapus data ini?',
    'msg_login_success' => 'Login berhasil!',
    'msg_login_failed' => 'Username atau password salah!',
    'msg_logout_success' => 'Anda telah berhasil logout!',
    'msg_required_field' => 'Field ini harus diisi!',
    'msg_invalid_format' => 'Format tidak valid!',

    // Validation
    'validation_required' => 'Field %s harus diisi',
    'validation_min_length' => 'Field %s minimal %s karakter',
    'validation_max_length' => 'Field %s maksimal %s karakter',
    'validation_valid_email' => 'Field %s harus berisi email yang valid',
    'validation_matches' => 'Field %s tidak cocok dengan field %s',
    'validation_is_unique' => 'Field %s sudah ada',
    'validation_numeric' => 'Field %s harus berisi angka',
    'validation_greater_than' => 'Field %s harus lebih besar dari %s',
];
?>