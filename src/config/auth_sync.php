<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Role Configuration
    |--------------------------------------------------------------------------
    |
    | Role yang mendapat semua permission baru secara otomatis saat sync.
    | Dapat di-override melalui environment variable AUTH_SYNC_DEFAULT_ROLE.
    |
    */
    'default_role' => env('AUTH_SYNC_DEFAULT_ROLE', 'admin'),

    /*
    |--------------------------------------------------------------------------
    | Auto Assign to Default Role
    |--------------------------------------------------------------------------
    |
    | Apakah permission baru otomatis di-assign ke default role.
    | Set ke false jika ingin manual assignment untuk semua permission baru.
    |
    */
    'auto_assign_to_default_role' => true,

    /*
    |--------------------------------------------------------------------------
    | Role Blacklist
    |--------------------------------------------------------------------------
    |
    | Role yang tidak boleh mendapat permission baru secara otomatis.
    | Role yang ada di list ini akan di-skip saat auto-assign.
    |
    */
    'role_blacklist' => [],

    /*
    |--------------------------------------------------------------------------
    | Route to Permission Mapping
    |--------------------------------------------------------------------------
    |
    | Mapping eksplisit antara route name dan permission name.
    | Permission merepresentasikan kemampuan terhadap modul, bukan nama route.
    |
    | Contoh:
    | 'transaksi.list' => 'transaksi.view'
    | 'transaksi.show' => 'transaksi.view'
    | 'transaksi.create' => 'transaksi.create'
    |
    */
    'route_permission_map' => [
        // Modul Transaksi
        'transaksi.list' => 'transaksi.view',
        'transaksi.show' => 'transaksi.view',
        'transaksi.create' => 'transaksi.create',
        'transaksi.edit' => 'transaksi.edit',
        'transaksi.delete' => 'transaksi.delete',

        // Modul Barang
        'barang.list' => 'barang.view',
        'barang.show' => 'barang.view',
        'barang.create' => 'barang.create',
        'barang.edit' => 'barang.edit',
        'barang.delete' => 'barang.delete',

        // Modul Satuan
        'satuan.list' => 'satuan.view',
        'satuan.show' => 'satuan.view',
        'satuan.create' => 'satuan.create',
        'satuan.edit' => 'satuan.edit',
        'satuan.delete' => 'satuan.delete',

        // Modul Customer
        'customer.list' => 'customer.view',
        'customer.show' => 'customer.view',
        'customer.create' => 'customer.create',
        'customer.edit' => 'customer.edit',
        'customer.delete' => 'customer.delete',

        // Modul User
        'user.list' => 'user.view',
        'user.show' => 'user.view',
        'user.create' => 'user.create',
        'user.edit' => 'user.edit',
        'user.delete' => 'user.delete',

        // Modul Role
        'role.list' => 'role.view',
        'role.show' => 'role.view',
        'role.create' => 'role.create',
        'role.edit' => 'role.edit',
        'role.delete' => 'role.delete',

        // Modul Laporan
        'laporan.penjualan' => 'laporan.view',
        'laporan.export' => 'laporan.export',
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Mapping Pattern
    |--------------------------------------------------------------------------
    |
    | Mapping default menggunakan konvensi nama jika route tidak ada
    | di route_permission_map. Gunakan {module} sebagai placeholder
    | untuk nama modul (prefix sebelum titik).
    |
    | Contoh:
    | '*.list' => '{module}.view' akan mengubah 'barang.list' menjadi 'barang.view'
    |
    */
    'default_mapping' => [
        '*.list' => '{module}.view',
        '*.show' => '{module}.view',
        '*.index' => '{module}.view',
        '*.create' => '{module}.create',
        '*.edit' => '{module}.edit',
        '*.update' => '{module}.edit',
        '*.delete' => '{module}.delete',
        '*.destroy' => '{module}.delete',
        '*.export' => '{module}.export',
        '*.print' => '{module}.print',
    ],

    /*
    |--------------------------------------------------------------------------
    | Ignorable Routes
    |--------------------------------------------------------------------------
    |
    | Route yang diabaikan dan tidak akan disinkronkan ke tabel menus.
    | Gunakan wildcard (*) untuk pattern matching.
    |
    */
    'ignorable_routes' => [
        'login',
        'logout',
        'password.*',
        'sanctum.*',
        'filament.*',
        'livewire.*',
        'ignition.*',
        'telescope.*',
        'horizon.*',
        'vapor.*',
        'api.*',
    ],

    /*
    |--------------------------------------------------------------------------
    | Group Auto-Generation Rules
    |--------------------------------------------------------------------------
    |
    | Aturan untuk auto-generate group berdasarkan prefix route_name.
    | Jika prefix tidak ada di list, group akan di-generate dari prefix
    | dengan format title case.
    |
    */
    'group_rules' => [
        'transaksi' => 'Transaksi',
        'barang' => 'Master Barang',
        'satuan' => 'Master Satuan',
        'customer' => 'Master Customer',
        'user' => 'Manajemen User',
        'role' => 'Manajemen Role',
        'laporan' => 'Laporan',
    ],

    /*
    |--------------------------------------------------------------------------
    | Display Name Auto-Generation Rules
    |--------------------------------------------------------------------------
    |
    | Aturan untuk auto-generate display_name dari route_name.
    | Format default: "{Module} {Action}" dengan title case.
    |
    */
    'display_name_rules' => [
        // Custom rules bisa ditambahkan di sini
    ],
];
