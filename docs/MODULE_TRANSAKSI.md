# Modul Transaksi

## Tujuan

Modul transaksi penjualan untuk mencatat semua transaksi penjualan barang, mengelola keranjang belanja, menghitung total pembayaran, dan mencatat mutasi stok.

## Flow

1. User membuka halaman daftar transaksi
2. User klik "Buat Transaksi" untuk membuat transaksi baru
3. Sistem generate nomor invoice otomatis (format: TRX-YYYYMMDD-XXXX)
4. User input tanggal transaksi dan customer (opsional)
5. User tambah barang ke keranjang:
   - Pilih barang dari dropdown
   - Pilih satuan dari dropdown (dynamic berdasarkan barang)
   - Input quantity
   - Sistem hitung harga berdasarkan satuan
   - Sistem hitung subtotal
   - Sistem hitung qty dalam pcs
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
public float $transGrandTotal = 0;

// Cart
public array $cartItems = [];

// Single Item Form
public int $itemBarangId = 0;
public int $itemBarangSatuanId = 0;
public int $itemQty = 1;
public float $itemHarga = 0;
public float $itemDiskon = 0;
public float $itemSubtotal = 0;

// Payment
public float $bayarNominal = 0;
public float $kembaliNominal = 0;
```

Note: `transCustomer` is used (string) instead of `transCustomerId` to match the actual database schema where customer is a string field, not a foreign key.

### transaksi-show

```php
public int $transaksiId;
public Transaksi $transaksi;
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

// Get barang with satuan
Barang::with('satuan')->get();

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
'transCustomerId' => 'nullable|string',

// Item
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

- Header section:
  - Nomor Invoice (readonly)
  - Tanggal (date picker)
  - Customer (input)
- Grand total display (readonly)
- Cart section:
  - Table item yang ditambahkan
  - Column: No, Barang, Satuan, Qty, Harga, Diskon, Subtotal, Action
- Add item section:
  - Dropdown barang
  - Dropdown satuan (dynamic)
  - Input qty
  - Input harga (readonly)
  - Input diskon
  - Subtotal (readonly)
  - Button "Tambah"
- Payment section:
  - Grand total
  - Input bayar nominal
  - Kembalian (readonly)
  - Button "Simpan Transaksi"
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
   - Setup property
   - Implement generate invoice number
   - Implement add to cart logic
   - Implement edit/remove cart item
   - Implement payment calculation
   - Implement save transaction dengan DB transaction
   - Implement stock deduction
   - Create blade template

4. **Milestone 4**: transaksi-show component
   - Setup property
   - Implement query dengan eager loading
   - Create blade template
   - Test display detail

5. **Milestone 5**: Integration testing
   - Test full flow dari list → create → show
   - Test stock deduction
   - Test rollback scenario
   - Test validation

## Todo

- [x] Setup folder structure untuk transaksi components
- [x] Implement transaksi-list component
- [x] Implement transaksi-create component
- [ ] Implement transaksi-show component
- [x] Add routes ke web.php
- [ ] Test full transaction flow
- [ ] Test stock deduction accuracy
- [ ] Test database transaction rollback
- [ ] Performance testing untuk large dataset
