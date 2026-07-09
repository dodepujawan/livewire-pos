# PROJECT RULES

---

# 1. Framework

- Laravel 12
- Livewire 4
- Livewire MFC
- Volt
- TailwindCSS

---

# 2. Folder Structure

- Gunakan Livewire MFC.
- Satu component = satu folder.
- Jangan membuat Single File Component.
- Ikuti struktur folder project yang sudah ada.

---

# 3. Database Rules

- Jangan membuat migration tanpa approval.
- Jangan mengubah struktur database.
- Gunakan relationship Eloquent.
- Hindari Query Builder kecuali memang diperlukan.

---

# 4. Query Rules

- Hindari N+1 Query.
- Gunakan eager loading hanya jika memang dibutuhkan.
- Jangan eager loading pada halaman list jika relationship tidak digunakan.
- Gunakan pagination.

---

# 5. Property Naming

Header transaksi:

transNoInvoice

transTanggal

transCustomer

transGrandTotal

transItems

Detail transaksi:

itemBarangId

itemSatuanId

itemKodeBarang

itemQty

itemHarga

itemDiskon

itemSubtotal

Payment:

bayarNominal

kembaliNominal

Search:

searchKeyword

Edit:

editNama

Create:

createNama

Jangan gunakan nama generic seperti:

nama

harga

qty

total

---

# 6. Coding Style

- Gunakan camelCase.
- Gunakan type hint jika diperlukan.
- Method harus memiliki satu tanggung jawab.
- Hindari duplicate code.
- Ikuti coding style project yang sudah ada.

---

# 7. Livewire Rules

- Gunakan wire:navigate.
- Gunakan WithPagination jika diperlukan.
- Gunakan debounce untuk search.
- Jangan gunakan protected \$casts pada Livewire Component.
- Gunakan lifecycle method Livewire sesuai kebutuhan.
## Livewire Input Binding Rules
wire:model.live
- Search
- Barcode
- Autocomplete

wire:model.live.debounce.300ms
- Qty
- Diskon
- Pembayaran
- Input numerik yang perlu realtime

wire:model.blur
- Input yang tidak membutuhkan update realtime

Readonly field
- Jangan gunakan wire:model

## Input Numerik Livewire
Seluruh input numerik yang berasal dari wire:model dianggap sebagai input user.
Aturan:
- Input numerik dapat berupa string selama proses mengetik.
- Empty string ("") dianggap sebagai 0.
- Jangan melakukan operasi matematika langsung terhadap property Livewire.
- Selalu lakukan normalisasi terlebih dahulu.
Apabila terdapat lebih dari satu perhitungan numerik di dalam component,
buat helper private agar tidak mengulang casting.
Contoh:
private function toFloat($value): float
{
    return $value === '' || $value === null
        ? 0.0
        : (float) $value;
}
Gunakan helper tersebut pada seluruh proses perhitungan.
---

# 7.5 Keyboard Rules

- Semua input yang menggunakan Enter wajib memakai `.prevent`.
- Enter pada field transaksi tidak boleh men-submit form kecuali tombol Simpan.
- Enter pada Kode Barang hanya menjalankan searchBarang().
- Enter pada Qty hanya menjalankan addToCart().

# 8. UI / UX Rules

# Desktop UI Rules
Seluruh halaman POS menggunakan desktop-first layout.
Prioritas:
- Scroll seminimal mungkin.
- Header selalu terlihat.
- Area cart selalu terlihat.
- Payment selalu terlihat.
- Add item cukup satu baris.
- Hindari form bertumpuk ke bawah.
- Gunakan Grid daripada flex-column.
- Target resolusi utama:
  1920x1080
  1600x900
  1366x768
 
 # POS UI Rules
Jangan membuat layout seperti form CRUD Laravel.
Prioritaskan:
Desktop First.
Semua informasi penting harus terlihat tanpa scroll.
Usahakan:
Header
Input Barang
Cart
Payment
berada dalam satu viewport pada resolusi 1920x1080.
Gunakan CSS Grid.
Hindari card yang terlalu tinggi.
Input item dibuat horizontal, bukan vertikal.
Table cart mengambil ruang terbesar.
Target pengguna adalah kasir desktop, bukan mobile.

# 9. Performance Rules

- Jangan melakukan query di Blade.
- Hindari query di dalam loop.
- Gunakan eager loading hanya jika diperlukan.
- Gunakan pagination.
- Optimalkan query sebelum menambah cache.

---

# 10. AI Rules
Sebelum coding:
- Analisa project.
- Analisa database.
- Analisa model.
- Analisa component serupa.
- Buat Mega Plan.
- Tunggu approval.
- Implement per milestone.
- Self review.
- Tunggu approval.
Saat diminta melakukan review atau analisa:
- Gunakan Bahasa Indonesia.
- Jangan langsung menulis kode.
- Jangan langsung mengubah file.
- Jelaskan akar masalah terlebih dahulu.
- Jelaskan dampaknya.
- Berikan solusi konseptual.
- Tunggu approval sebelum implementasi.

# 11. Documentation Rules
Every completed milestone must update:

- docs/CHANGELOG.md
- Related MODULE_*.md

Never rely on chat history.

Project knowledge must always be stored inside the repository.



