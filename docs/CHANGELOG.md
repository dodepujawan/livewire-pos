# Changelog

All notable changes to this project will be documented here.

---

## 2026-07-08

### Module: Transaksi

#### Milestone 1 - transaksi-list.php

Status: ✅ Completed

Changes:
- Added searchKeyword property.
- Added dateFrom and dateTo filter properties (kept as strings).
- Added date range validation rules.
- Added pagination support.
- Added filter reset method.
- Added search query for transaction list.
- Added date range filtering.
- Added component title "Transaksi Penjualan".

Files:
- resources/views/pages/transaksi/⚡transaksi-list/transaksi-list.php

Reviewed:
- Self review completed.
- Human review approved.

Next Milestone:
- Implement transaksi-list.blade.php

#### Milestone 2 - transaksi-list.blade.php

Status: ✅ Completed

Changes:
- Implemented desktop-first UI following barang-list design language.
- Added page header with title "Transaksi Penjualan" and "Buat Transaksi" button (wire:navigate).
- Added filter section with search invoice, date from, date to, and reset filter button.
- Implemented transaction table with columns: No, Nomor Transaksi, Tanggal, Customer, Grand Total, Action.
- Added empty state message when no transactions exist.
- Added pagination support.
- Added loading state with spinner indicator during Livewire requests.
- Used wire:model.live.debounce.300ms for search input.
- Used wire:model.live for date filter inputs.
- Used wire:navigate for navigation links.
- Implemented responsive design with TailwindCSS (mobile-first, desktop-optimized).
- Filter inputs stack vertically on mobile, horizontally on desktop.
- Table has horizontal scroll overflow for mobile.

Files:
- src/resources/views/pages/transaksi/⚡transaksi-list/transaksi-list.blade.php

Reviewed:
- Self review completed.
- Human review approved.

Next Milestone:
- Implement transaksi-create component

#### Milestone 3 - transaksi-create component

Status: ✅ Completed

Changes:
- Added properties: transNoInvoice, transTanggal, transCustomer, transGrandTotal, cartItems, itemBarangId, itemBarangSatuanId, itemQty, itemHarga, itemDiskon, itemSubtotal, bayarNominal, kembaliNominal.
- Implemented mount() method to generate invoice number (TRX-YYYYMMDD-XXXX) and set default date.
- Implemented dynamic satuan loading when barang is selected (updatedItemBarangId).
- Implemented price auto-fill from satuan selection (updatedItemBarangSatuanId).
- Implemented real-time subtotal calculation (updatedItemQty, updatedItemDiskon).
- Implemented addToCart() with stock validation and duplicate item handling.
- Implemented removeFromCart() to delete items from cart.
- Implemented grand total calculation.
- Implemented payment calculation (kembalian) on bayarNominal update.
- Implemented saveTransaksi() with DB transaction for atomic operations.
- Implemented stock mutation recording (StokMutasi) for each transaction item.
- Implemented stock deduction from barang table.
- Implemented render() method with eager loading for barang and satuan.
- Created blade template with desktop-first UI following transaksi-list design language.
- Added header section (invoice readonly, date picker, customer input).
- Added grand total display section.
- Added cart table with item list and remove action.
- Added add item form with barang dropdown, dynamic satuan dropdown, qty, harga readonly, diskon, subtotal readonly.
- Added payment section with total tagihan, bayar input, kembalian readonly.
- Added success/error flash messages.
- Used wire:model.live for real-time updates.
- Used wire:navigate for navigation.
- Implemented responsive design with TailwindCSS.
- Note: Used transCustomer (string) instead of transCustomerId to match actual database schema.

Files:
- src/resources/views/pages/transaksi/⚡transaksi-create/transaksi-create.php
- src/resources/views/pages/transaksi/⚡transaksi-create/transaksi-create.blade.php

Reviewed:
- Self review completed against PROJECT_RULE.md.

Next Milestone:
- Implement transaksi-show component

#### Milestone 4 - transaksi-create.blade.php Desktop UX Refactor

Status: ✅ Completed

Changes:
- Refactored layout to achieve zero vertical scrolling at 1920x1080 resolution.
- Changed grid layout from 3-6-3 to 3-7-2 columns for better space utilization.
- Cart now occupies ~58% of viewport width (7 out of 12 columns).
- Payment section compacted to 2 columns for space efficiency.
- Reduced all padding and font sizes to minimize card height.
- Changed form height calculation from calc(100vh-120px) to calc(100vh-80px).
- Reduced main container padding from p-4 to p-3.
- Reduced gap between grid columns from gap-3 to gap-2.
- Reduced card padding from p-3 to p-2.
- Reduced input padding from py-1.5 to py-1.
- Changed font sizes from text-sm to text-xs for labels and inputs.
- Made Grand Total visually dominant with gradient background (bg-gradient-to-r from-blue-50 to-blue-100).
- Increased Grand Total font size to text-2xl font-black.
- Added border-t-2 border-blue-200 for visual emphasis.
- Changed Grand Total label to uppercase "GRAND TOTAL" with font-semibold.
- All wire:model, wire:click, wire:submit directives remain unchanged.
- All element IDs (kode-barang-input, qty-input) remain unchanged.
- All Livewire events (focus-qty, focus-kode-barang) remain unchanged.
- Keyboard workflow (Enter on kode barang, Enter on qty) remains unchanged.
- Input barang remains in single horizontal row with 5 columns.

Files:
- src/resources/views/pages/transaksi/⚡transaksi-create/transaksi-create.blade.php

Reviewed:
- Self review completed against PROJECT_RULE.md Desktop UI Rules and POS UI Rules.
- Verified zero vertical scrolling at 1920x1080.
- Verified cart occupies ~60% viewport.
- Verified Grand Total visually dominant.
- Verified keyboard workflow unchanged.

Next Milestone:
- Implement transaksi-show component
