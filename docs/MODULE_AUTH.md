# MODULE AUTH

## Status

In Progress

## Tujuan

Dokumentasi sistem Authentication dan Authorization.

## Scope

- Login
- Logout
- Role
- Permission
- Menu
- Sidebar
- Route Permission
- Auto Route Sync
- Middleware
- Policy
- Gate

## Arsitektur

### Source of Truth
- Route Laravel adalah source of truth untuk component mapping
- Route hanya digunakan untuk menemukan halaman
- Permission merepresentasikan kemampuan terhadap modul, bukan nama route

### Permission vs Menu

**Permission:**
- Dibuat untuk semua Livewire Route
- Digunakan untuk otorisasi akses ke halaman
- Route dengan parameter tetap memiliki Permission
- Contoh: `barang-edit`, `transaksi-show`, `transaksi-edit` tetap memiliki Permission

**Menu:**
- Hanya dibuat untuk Route yang dapat diakses langsung tanpa parameter
- Digunakan untuk navigasi di Sidebar
- Route dengan parameter TIDAK menjadi Menu
- Contoh: `barang-list` menjadi Menu, `barang-edit` TIDAK menjadi Menu

**Alasan:**
- Route dengan parameter (seperti `{id}`) tidak dapat dipanggil menggunakan `route($routeName)`
- Menus di Sidebar harus dapat diakses langsung tanpa parameter
- Memisahkan Permission dan Menu memastikan Sidebar selalu valid

### Database Schema
- **menus** (custom table): Metadata menu dari route
- **roles, permissions, model_has_roles, role_has_permissions** (Spatie): Role dan Permission

### Mapping Route → Permission
Contoh:
- Route: transaksi.list, transaksi.show, transaksi.create
- Permission: transaksi.view, transaksi.create

### Configuration
- Satu file config: `config/auth_sync.php`
- Berisi: default_role, route_permission_map, default_mapping, ignorable_routes

### Workflow Developer
1. Buat Route::livewire()
2. Jalankan php artisan app:sync-auth
3. Sistem otomatis: buat menu, buat permission, assign ke default role

## Milestone

- [x] Milestone 1 - Database & Model Setup
- [x] Milestone 2 - Artisan Command Sync
- [x] Milestone 3 - Permission Sync to Spatie
- [x] Milestone 4 - Permission Matrix UI
- [x] Milestone 5 - Sidebar Builder
- [x] Milestone 6 - Middleware Authorization