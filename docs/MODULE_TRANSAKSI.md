# Modul Transaksi

## Tujuan

Modul transaksi penjualan untuk mencatat semua transaksi penjualan barang, mengelola keranjang belanja, menghitung total pembayaran, dan mencatat mutasi stok.

## Flow

1. User membuka halaman daftar transaksi
2. User klik "Buat Transaksi" untuk membuat transaksi baru
3. Sistem generate nomor invoice otomatis (format: TRX-YYYYMMDD-XXXX)
4. User input tanggal transaksi dan customer (opsional)
5. User tambah barang ke keranjang:
   - Input kode barang (scan/ketik)
   - Tekan Enter untuk mencari barang
   - Sistem tampilkan nama barang dan stok
   - Satuan pertama otomatis dipilih (prioritas konversi=1)
   - Input quantity
   - Sistem hitung harga berdasarkan satuan
   - Sistem hitung subtotal
   - Sistem hitung qty dalam pcs
   - Tekan Enter pada qty untuk tambah ke keranjang
6. User bisa edit atau hapus item dari keranjang
7. User input nominal pembayaran
8. Sistem hitung kembalian
9. User simpan transaksi
10. Sistem validasi stok tersedia
11. Sistem simpan data transaksi (header dan detail)
12. Sistem catat mutasi stok (keluar)
13. Sistem kurangi stok barang
14. User diarahkan ke halaman daftar transaksi

## Route

```
/transaksi              -> transaksi-list
/transaksi/create       -> transaksi-create
/transaksi/{id}         -> transaksi-show
/transaksi/{id}/edit    -> transaksi-edit
```

Semua route menggunakan middleware `auth` dan `Route::livewire()`.

## Livewire Components

### transaksi-list
- Lokasi: `resources/views/pages/transaksi/⚡transaksi-list/`
- File: `transaksi-list.php`, `transaksi-list.blade.php`
- Fungsi: Menampilkan daftar semua transaksi dengan filter dan pagination

### transaksi-create
- Lokasi: `resources/views/pages/transaksi/⚡transaksi-create/`
- File: `transaksi-create.php`, `transaksi-create.blade.php`
- Fungsi: Form untuk membuat transaksi baru dengan keranjang belanja

### transaksi-show
- Lokasi: `resources/views/pages/transaksi/⚡transaksi-show/`
- File: `transaksi-show.php`, `transaksi-show.blade.php`
- Fungsi: Menampilkan detail transaksi yang sudah disimpan

### transaksi-edit
- Lokasi: `resources/views/pages/transaksi/⚡transaksi-edit/`
- File: `transaksi-edit.php`, `transaksi-edit.blade.php`
- Fungsi: Mengedit transaksi yang sudah tersimpan dengan stock adjustment

## Property

### transaksi-list

```php
public string $searchKeyword = '';
public string $dateFrom = '';
public string $dateTo = '';

protected $casts = [
    'dateFrom' => 'date',
    'dateTo' => 'date',
];
```

### transaksi-create

```php
// Header
public string $transNoInvoice = '';
public string $transTanggal = '';
public string $transCustomer = '';
public $transGrandTotal = 0;

// Cart
public array $cartItems = [];

// Single Item Form
public string $itemKodeBarang = '';
public int $itemBarangId = 0;
public string $itemNamaBarang = '';
public int $itemStok = 0;
public array $itemSatuanList = [];
public int $itemBarangSatuanId = 0;
public int $itemQty = 1;
public $itemHarga = 0;
public $itemDiskon = 0;
public $itemSubtotal = 0;

// Payment
public $bayarNominal = 0;
public $kembaliNominal = 0;
```

Note: `transCustomer` is used (string) instead of `transCustomerId` to match the actual database schema where customer is a string field, not a foreign key.

### transaksi-show

```php
public int $transaksiId;
public Transaksi $transaksi;
```

### transaksi-edit

```php
public int $transaksiId;

// Header
public string $transNoInvoice = '';
public string $transTanggal = '';
public string $transCustomer = '';
public $transGrandTotal = 0;

// Cart
public array $cartItems = [];

// Single Item Form
public string $itemKodeBarang = '';
public int $itemBarangId = 0;
public string $itemNamaBarang = '';
public int $itemStok = 0;
public array $itemSatuanList = [];
public int $itemBarangSatuanId = 0;
public $itemQty = 1;
public $itemHarga = 0;
public $itemDiskon = 0;
public $itemSubtotal = 0;

// Payment
public $bayarNominal = 0;
public $kembaliNominal = 0;
```

## Query

### transaksi-list

```php
Transaksi::query()
    ->when($this->searchKeyword, function ($query) {
        $query->where('nomor_transaksi', 'like', '%' . $this->searchKeyword . '%');
    })
    ->when($this->dateFrom, function ($query) {
        $query->whereDate('tanggal', '>=', $this->dateFrom);
    })
    ->when($this->dateTo, function ($query) {
        $query->whereDate('tanggal', '<=', $this->dateTo);
    })
    ->latest('tanggal')
    ->paginate(15);
```

### transaksi-create

```php
// Generate invoice number
$lastInvoice = Transaksi::whereDate('tanggal', today())
    ->orderBy('id', 'desc')
    ->first();

// Search barang by kode_barang
Barang::where('kode_barang', strtoupper($kodeBarang))
    ->with('satuan')
    ->first();

// Check stock availability
Barang::where('id', $barangId)
    ->where('stok', '>=', $qtyPcs)
    ->exists();
```

### transaksi-show

```php
Transaksi::with(['details.barang', 'details.satuan'])
    ->findOrFail($transaksiId);
```

### transaksi-edit

```php
// Load transaksi with details
Transaksi::with(['details.barang', 'details.satuan'])
    ->findOrFail($transaksiId);

// Lock affected barang for stock adjustment
Barang::whereIn('id', $allBarangIds)->lockForUpdate()->get();

// Restore old stock
TransaksiDetail::where('transaksi_id', $transaksiId)->get();
foreach ($oldDetails as $detail) {
    Barang::where('id', $detail->barang_id)
        ->increment('stok', $detail->qty_pcs);
}

// Update stock mutation
StokMutasi::where('transaksi_id', $transaksiId)->delete();
```

## Validation

### transaksi-list

```php
protected $rules = [
    'dateFrom' => 'nullable|date',
    'dateTo' => 'nullable|date|after_or_equal:dateFrom',
];
```

### transaksi-create

```php
// Header
'transNoInvoice' => 'required|string|unique:transaksi,nomor_transaksi',
'transTanggal' => 'required|date',
'transCustomer' => 'nullable|string',

// Item
'itemKodeBarang' => 'nullable|string',
'itemBarangId' => 'required|exists:barang,id',
'itemBarangSatuanId' => 'required|exists:barang_satuan,id',
'itemQty' => 'required|integer|min:1',
'itemHarga' => 'required|numeric|min:0',
'itemDiskon' => 'nullable|numeric|min:0',

// Payment
'bayarNominal' => 'required|numeric|min:0',
```

### transaksi-edit

```php
// Header
'transTanggal' => 'required|date',
'transCustomer' => 'nullable|string',

// Item
'itemKodeBarang' => 'nullable|string',
'itemBarangId' => 'required|exists:barang,id',
'itemBarangSatuanId' => 'required|exists:barang_satuan,id',
'itemQty' => 'required|integer|min:1',
'itemHarga' => 'required|numeric|min:0',
'itemDiskon' => 'nullable|numeric|min:0',

// Payment
'bayarNominal' => 'required|numeric|min:0',
```

## UI

### transaksi-list

- Header: "Transaksi Penjualan"
- Filter section:
  - Input search (nomor invoice)
  - Input date from
  - Input date to
  - Button reset filter
- Table:
  - No
  - Nomor Transaksi
  - Tanggal
  - Customer
  - Grand Total
  - Action (View)
- Pagination
- Button "Buat Transaksi"

### transaksi-create

- Layout: 3-column grid (300px fixed left, flexible middle, 300px fixed right)
- Header section:
  - Nomor Invoice (readonly)
  - Tanggal (date picker)
  - Customer (input)
- Cart section (middle column):
  - Table item yang ditambahkan
  - Column: No, Barang, Satuan, Qty, Harga, Diskon, Subtotal, Action
  - Max-height dengan overflow scroll untuk item banyak
- Add item section (left column):
  - Input kode barang (scan/ketik, Enter untuk cari)
  - Display nama barang dan stok (setelah search)
  - Input qty (Enter untuk tambah ke keranjang)
  - Dropdown satuan (dynamic, auto-select satuan pertama)
  - Input harga (readonly)
  - Input diskon
  - Subtotal (readonly)
  - Button "Tambah ke Keranjang"
- Payment section (right column):
  - Total tagihan
  - Input bayar nominal
  - Kembalian (readonly)
  - Grand Total display (di atas tombol Simpan)
  - Button "Simpan"
  - Button "Kembali"

### transaksi-show

- Header section:
  - Nomor Invoice
  - Tanggal
  - Customer
  - Grand Total
- Detail section:
  - Table semua item
  - Column: No, Barang, Satuan, Qty, Harga, Diskon, Subtotal
- Payment section:
  - Total
  - Bayar
  - Kembalian
- Button "Kembali"

### transaksi-edit

- Layout: 3-column grid (300px fixed left, flexible middle, 300px fixed right) - sama dengan transaksi-create
- Header section:
  - Nomor Invoice (readonly)
  - Tanggal (date picker, editable)
  - Customer (input, editable)
- Cart section (middle column):
  - Table item yang ditambahkan
  - Column: No, Barang, Satuan (dropdown editable), Qty (editable), Harga (readonly), Diskon (editable), Subtotal (readonly), Action
  - Max-height dengan overflow scroll untuk item banyak
- Add item section (left column):
  - Input kode barang (scan/ketik, Enter untuk cari)
  - Display nama barang dan stok (setelah search)
  - Input qty (Enter untuk tambah ke keranjang)
  - Dropdown satuan (dynamic, auto-select satuan pertama)
  - Input harga (readonly)
  - Input diskon
  - Subtotal (readonly)
  - Button "Tambah ke Keranjang"
- Payment section (right column):
  - Total tagihan
  - Input bayar nominal
  - Kembalian (readonly)
  - Grand Total display (di atas tombol Simpan)
  - Button "Simpan"
  - Button "Kembali"

## Transaction State

Setiap transaksi memiliki status yang menggambarkan kondisi transaksi dalam lifecycle:

### Draft
- **Deskripsi**: Transaksi yang sedang dibuat tetapi belum disimpan
- **Karakteristik**:
  - Data tersimpan sementara di session/local storage
  - Belum ada record di database
  - Stok belum dikurangi
  - Dapat di-edit atau dihapus sepenuhnya
- **Transisi**: Draft → Completed (saat disimpan)

### Completed
- **Deskripsi**: Transaksi yang sudah disimpan dan selesai
- **Karakteristik**:
  - Record tersimpan di database (transaksi dan transaksi_detail)
  - Stok sudah dikurangi
  - Mutasi stok tercatat
  - Tidak dapat di-edit (hanya view)
  - Dapat di-void dengan alasan
- **Transisi**: Completed → Voided (saat di-void)

### Voided
- **Deskripsi**: Transaksi yang dibatalkan setelah selesai
- **Karakteristik**:
  - Record masih ada di database (soft delete atau flag status)
  - Stok dikembalikan (reverse mutasi)
  - Tidak dapat di-edit
  - Hanya dapat dilihat untuk audit
  - Ditandai dengan alasan void dan timestamp
- **Transisi**: Tidak ada transisi lanjutan (terminal state)

### Database Schema
```php
// Tambahkan field di tabel transaksi
$status ENUM('draft', 'completed', 'voided') DEFAULT 'completed';
$voided_at TIMESTAMP NULL;
$voided_reason TEXT NULL;
$voided_by INT NULL; // user_id yang melakukan void
```

## Business Rules

### Aturan untuk Transaksi yang Sudah Tersimpan (Completed)

#### 1. **Edit Restrictions**
- Transaksi dengan status `completed` TIDAK BOLEH di-edit
- Hanya transaksi dengan status `draft` yang boleh di-edit
- Jika perlu mengubah transaksi completed, harus void terlebih dahulu

#### 2. **Void Rules**
- Void hanya boleh dilakukan oleh user dengan permission `transaksi.void`
- Void memerlukan alasan wajib (minimal 10 karakter)
- Void akan:
  - Mengubah status menjadi `voided`
  - Mengembalikan stok barang (reverse mutasi)
  - Mencatat user yang melakukan void dan timestamp
  - Tidak menghapus record dari database (audit trail)

#### 3. **Stock Management**
- Stok hanya dikurangi saat transaksi berubah dari Draft → Completed
- Stok dikembalikan saat transaksi berubah dari Completed → Voided
- Validasi stok dilakukan sebelum Draft → Completed
- Jika stok tidak cukup, transaksi tidak boleh disimpan

#### 4. **Invoice Number**
- Invoice number generate otomatis saat Draft → Completed
- Format: TRX-YYYYMMDD-XXXX (XXXX = urutan hari itu)
- Invoice number tidak boleh diubah setelah generate
- Invoice number unik per transaksi

#### 5. **Payment Validation**
- Pembayaran harus >= grand total saat Completed
- Kembalian dihitung otomatis
- Payment tidak boleh negatif

#### 6. **Date Restrictions**
- Tanggal transaksi tidak boleh diubah setelah Completed
- Tanggal transaksi default ke hari ini
- Tanggal tidak boleh di masa depan

### Aturan untuk Transaksi Draft

#### 1. **Cart Management**
- Item dapat ditambah, di-edit, dihapus
- Quantity minimal 1
- Diskon tidak boleh negatif
- Harga diambil dari master barang (tidak boleh diubah manual)

#### 2. **Auto-save**
- Draft dapat di-auto-save ke session/local storage
- Draft dapat di-resume kemudian
- Draft expire setelah 24 jam jika tidak dilanjutkan

#### 3. **Validation Before Save**
- Cart tidak boleh kosong
- Semua item harus valid (barang exists, satuan exists)
- Stok harus mencukupi
- Payment harus >= total

## Lifecycle Diagram

```
┌─────────────────────────────────────────────────────────────────┐
│                    TRANSACTION LIFECYCLE                         │
└─────────────────────────────────────────────────────────────────┘

┌──────────────┐
│   START      │
│  User opens  │
│ transaksi-   │
│    list      │
└──────┬───────┘
       │
       ▼
┌──────────────────┐
│  transaksi-list  │
│  - View list     │
│  - Filter        │
│  - Search        │
└──────┬───────────┘
       │
       ├─────────────────┐
       │                 │
       ▼                 ▼
┌──────────────┐  ┌──────────────┐
│ Click "Buat" │  │ Click "View" │
└──────┬───────┘  └──────┬───────┘
       │                 │
       ▼                 ▼
┌──────────────────┐  ┌──────────────┐
│ transaksi-create │  │ transaksi-show│
│  - Add items     │  │  - Read only  │
│  - Calculate     │  │  - View detail│
│  - Payment       │  └──────┬───────┘
└──────┬───────────┘         │
       │                     ├──────────────┐
       │                     │              │
       │                     ▼              ▼
       │              ┌──────────┐  ┌──────────────┐
       │              │ Click    │  │ Click "Void" │
       │              │ "Edit"   │  │ (if allowed) │
       │              └────┬─────┘  └──────┬───────┘
       │                   │                │
       │                   ▼                │
       │            ┌──────────────┐        │
       │            │ transaksi-   │        │
       │            │    edit      │        │
       │            │  - Edit items│        │
       │            │  - Adjust    │        │
       │            │    stock     │        │
       │            └──────┬───────┘        │
       │                   │                │
       └───────────────────┴────────────────┘
                           │
                           ▼
                  ┌────────────────┐
                  │   SAVE ACTION   │
                  └────────┬───────┘
                           │
           ┌───────────────┴───────────────┐
           │                               │
           ▼                               ▼
    ┌──────────────┐              ┌──────────────┐
    │   DRAFT      │              │  COMPLETED   │
    │  (optional)  │              │              │
    │  - Auto-save │              │  - Stock -   │
    │  - Resume     │              │    deducted  │
    └──────┬───────┘              │  - Mutation  │
           │                      │    recorded  │
           │                      └──────┬───────┘
           │                             │
           └─────────────┬───────────────┘
                         │
                         ▼
                  ┌──────────────┐
                  │   VOIDED     │
                  │              │
                  │  - Stock +   │
                  │    returned  │
                  │  - Audit log │
                  └──────┬───────┘
                         │
                         ▼
                  ┌──────────────┐
                  │    END       │
                  │  (Terminal)  │
                  └──────────────┘

STATE TRANSITIONS:
  Draft → Completed : Save transaction, deduct stock
  Completed → Voided : Void transaction, return stock
  Voided → (none) : Terminal state, no further changes

DATA FLOW:
  CREATE: List → Create → (Draft) → Completed → List
  VIEW: List → Show → List
  EDIT: List → Show → Edit → Completed → Show
  VOID: List → Show → Voided → Show
```

## Milestone

1. **Milestone 1**: transaksi-list.php (Backend)
   - ✅ Setup property dan trait
   - ✅ Implement query dengan filter
   - ✅ Test pagination dan filter

2. **Milestone 2**: transaksi-list.blade.php (UI)
   - ✅ Implement desktop-first UI following barang-list design language
   - ✅ Add page header with title and action button
   - ✅ Add filter section (search, date from, date to, reset)
   - ✅ Implement transaction table with required columns
   - ✅ Add empty state
   - ✅ Add pagination
   - ✅ Add loading state
   - ✅ Implement responsive design

3. **Milestone 3**: transaksi-create component
   - ✅ Setup property
   - ✅ Implement generate invoice number
   - ✅ Implement add to cart logic
   - ✅ Implement edit/remove cart item
   - ✅ Implement payment calculation
   - ✅ Implement save transaction dengan DB transaction
   - ✅ Implement stock deduction
   - ✅ Create blade template
   - ✅ Implement kode_barang search (Enter key)
   - ✅ Implement auto-select first satuan
   - ✅ Implement keyboard workflow
   - ✅ Implement focus management

4. **Milestone 4**: transaksi-show component
   - Setup property
   - Implement query dengan eager loading
   - Create blade template
   - Test display detail

5. **Milestone 5**: transaksi-edit component
   - Setup property (reuse from transaksi-create)
   - Implement mount dengan load existing data
   - Implement saveTransaksi dengan stock adjustment algorithm:
     - lockForUpdate() affected barang
     - Restore old stock
     - Update transaksi header
     - Delete old details, insert new details
     - Deduct new stock
     - Update stock mutation
   - Reuse cart logic dari transaksi-create
   - Create blade template
   - Test edit flow untuk berbagai kondisi:
     - Qty berubah
     - Satuan berubah
     - Item dihapus
     - Item baru ditambahkan

6. **Milestone 6**: Integration testing
   - Test full flow dari list → create → show → edit
   - Test stock deduction dan adjustment
   - Test rollback scenario
   - Test validation

## Todo

- [x] Setup folder structure untuk transaksi components
- [x] Implement transaksi-list component
- [x] Implement transaksi-create component
- [ ] Implement transaksi-show component
- [x] Implement transaksi-edit component (backend)
- [ ] Implement transaksi-edit component (blade)
- [x] Add routes ke web.php
- [ ] Test full transaction flow
- [ ] Test stock deduction accuracy
- [ ] Test database transaction rollback
- [ ] Performance testing untuk large dataset
