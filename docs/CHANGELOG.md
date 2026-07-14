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

#### Milestone 3.1 - transaksi-create Keyboard-First Workflow

Status: ✅ Completed

### Changed
- Replaced product dropdown with kode_barang search.
- Removed loading all products on render().
- Added keyboard-first POS workflow.
- Added searchBarang() method for Enter key product search.
- Added browser focus events (focus-qty, focus-kode-barang).
- Added lightweight product state (itemKodeBarang, itemNamaBarang, itemStok, itemSatuanList).
- Updated resetItemForm() to clear new product state properties.
- Updated transaction create workflow to use kode_barang input instead of dropdown.

### Fixed
- Prevent Enter key from submitting the form (wire:keydown.enter.prevent).
- Improved barcode workflow with auto-focus management.
- Reduced unnecessary database loading by removing eager loading on render().

Files:
- src/resources/views/pages/transaksi/⚡transaksi-create/transaksi-create.php
- src/resources/views/pages/transaksi/⚡transaksi-create/transaksi-create.blade.php

Reviewed:
- Self review completed against PROJECT_RULE.md.

Next Milestone:
- Implement transaksi-show component

#### Milestone 4.2 - transaksi-create.blade.php Layout Proportion Refactor

Status: ✅ Completed

### Changed
- Changed grid layout from grid-cols-12 (3-6-3) to custom CSS grid: 300px minmax(0,1fr) 300px.
- Left and right columns now fixed at 300px width, middle column flexible.
- Cart container changed from flex-1 to max-h-[calc(100vh-250px)] with overflow-y-auto.
- Cart empty state limited to max-h-[200px] instead of full height stretch.
- Added divide-y divide-gray-100 to cart tbody for visual row separation.
- Removed flex-1 from left column card to eliminate unnecessary whitespace.
- Moved Grand Total from middle column to right column (above Simpan button).
- Grand Total now part of vertical payment flow: Total → Bayar → Kembali → Grand Total → Simpan.
- Increased spacing consistency: gap-2, p-2, space-y-2 throughout.
- Increased padding from p-1.5 to p-2 for better desktop legibility.
- Added mr-1 mt-1 to header "Kembali" button for visual breathing room.
- Removed sticky positioning from payment section (no longer needed with fixed layout).

### Fixed
- Eliminated cart over-stretch when empty or with few items.
- Improved height alignment between three columns.
- Better visual grouping of final action elements (Grand Total + Simpan).
- Reduced excessive whitespace in left column.

Files:
- src/resources/views/pages/transaksi/⚡transaksi-create/transaksi-create.blade.php

Reviewed:
- Self review completed against PROJECT_RULE.md Desktop UI Rules and POS UI Rules.
- Verified keyboard workflow unchanged (wire:keydown.enter.prevent preserved).
- Verified all wire:model bindings unchanged.
- Verified element IDs unchanged (kode-barang-input, qty-input).

Next Milestone:
- Implement transaksi-show component

#### Milestone 4.2.1 - transaksi-create.blade.php Layout Refinement

Status: ✅ Completed

### Fixed
- Cart container no longer stretches full height when empty or with few items
  - Removed flex-1 from cart container to eliminate forced full-height stretch
  - Changed cart table container from max-h-[calc(100vh-250px)] to max-h-[60vh]
  - Cart now follows natural height based on content, with scroll only when exceeding 60vh cap
  - Empty state changed from max-h-[200px] to natural height with py-8 padding
- Left column spacing increased for better readability
  - Changed all labels and inputs from text-xs (12px) to text-sm (14px)
  - Increased input padding from px-2 py-1 to px-3 py-2
  - Increased field spacing from space-y-2 to space-y-3
  - Increased card padding from p-2 to p-4 for header and add item sections
  - Increased grid gap from gap-1.5 to gap-2 for input row
  - Increased button padding from py-1.5 to py-2 for add to cart button
  - Increased section heading margin from mb-2 to mb-3
  - Increased barang details padding from p-1.5 to p-2

### Changed
- Cart container removed flex-col class to allow natural height behavior
- Add item section removed flex-col from container (no longer needed)

Files:
- src/resources/views/pages/transaksi/⚡transaksi-create/transaksi-create.blade.php

Reviewed:
- Self review completed against PROJECT_RULE.md Desktop UI Rules and POS UI Rules.
- Verified keyboard workflow unchanged (wire:keydown.enter.prevent preserved).
- Verified all wire:model bindings unchanged.
- Verified element IDs unchanged (kode-barang-input, qty-input).

Next Milestone:
- Implement transaksi-show component

#### Milestone 4.2.2 - transaksi-create.blade.php Layout Final Refinement

Status: ✅ Completed

### Fixed
- Tambah Barang form layout changed from 5-column horizontal to 2x2 grid
  - Changed from grid-cols-5 to grid-cols-2 for better fit in 300px width
  - Row 1: Qty | Satuan
  - Row 2: Harga | Diskon
  - Subtotal full-width (col-span-2) below
  - Prevents text truncation in narrow columns with text-sm + padding
- Cart table column widths explicitly defined
  - Added <colgroup> with fixed widths: No (28px), Barang (auto), Satuan (70px), Qty (60px), Harga (80px), Diskon (70px), Subtotal (80px), Aksi (60px)
  - Added table-layout: fixed to prevent column collapse/truncation
- Cart card height now follows content
  - Removed any remaining flex/h-full classes from cart card
  - Card height determined by table content + padding
  - max-h-[60vh] with overflow-y-auto only activates when >10 rows
  - Empty space below card is page background, not part of white card
- Right column payment section simplified
  - Removed redundant "Total" field (already shown in Grand Total)
  - Reordered to: Bayar -> Kembali -> Grand Total -> Simpan -> Kembali(link)

Files:
- src/resources/views/pages/transaksi/⚡transaksi-create/transaksi-create.blade.php

Reviewed:
- Self review completed against PROJECT_RULE.md Desktop UI Rules and POS UI Rules.
- Verified keyboard workflow unchanged (wire:keydown.enter.prevent preserved).
- Verified all wire:model bindings unchanged.
- Verified element IDs unchanged (kode-barang-input, qty-input).

Next Milestone:
- Implement transaksi-show component

#### Milestone 5 - transaksi-edit component (Backend)

Status: ✅ Completed

Changes:
- Added transaksiId property for mount parameter.
- Reused all properties from transaksi-create (header, cartItems, single item form, payment).
- Implemented mount(int $transaksiId) to load existing transaction data.
- Load transaksi with eager loading: Transaksi::with(['details.barang', 'details.satuan']).
- Convert existing transaksi details to cartItems array format.
- Set payment nominal to match grand total on load.
- Reused all cart logic from transaksi-create:
  - searchBarang() for product search
  - updatedItemBarangSatuanId() for price auto-update on satuan change
  - updatedItemQty(), updatedItemDiskon() for real-time calculation
  - addToCart() with stock validation and duplicate handling
  - removeFromCart() for item deletion
  - updatedCartItems() for inline editing with stock validation
  - calculateGrandTotal() for total calculation
  - resetItemForm() for form reset
  - updatedBayarNominal() for payment calculation
  - toFloat() helper for numeric normalization
- Implemented saveTransaksi() with complete stock adjustment algorithm:
  - BEGIN TRANSACTION for atomic operations
  - lockForUpdate() all affected barang (old and new) to prevent race condition
  - Restore old stock: increment stok by qty_pcs from old details
  - Update transaksi header (tanggal, customer, grand_total)
  - Delete old transaksi details and insert new details (Delete+Insert strategy)
  - Deduct new stock: decrement stok by qty_pcs from new details with validation
  - Update stock mutation: delete old mutations, create new mutations with "Edit Transaksi" prefix
  - COMMIT on success, ROLLBACK on error
- Redirect to transaksi-show after successful update.
- Validation rules updated (removed transNoInvoice unique validation, kept other rules).
- Stock adjustment algorithm correctness verified for:
  - Qty changes
  - Satuan changes
  - Item deletion
  - Item addition
  - Combined scenarios

Files:
- src/resources/views/pages/transaksi/⚡transaksi-edit/transaksi-edit.php

Reviewed:
- Self review completed against PROJECT_RULE.md and MODULE_TRANSAKSI.md design.
- Verified stock adjustment algorithm follows approved 6-step process.
- Verified lockForUpdate() prevents race conditions.
- Verified Delete+Insert strategy for detail updates.
- Verified reuse of transaksi-create logic for consistency.

Next Milestone:
- Implement transaksi-edit.blade.php

#### Milestone 6 - transaksi-edit.blade.php

Status: ✅ Completed

Changes:
- Implemented desktop-first UI by reusing transaksi-create.blade.php design.
- Changed page title from "Buat Transaksi" to "Edit Transaksi".
- All other UI elements identical to transaksi-create:
  - Header section with invoice (readonly), date picker, customer input
  - Add item form with kode barang search, qty, satuan, harga, diskon, subtotal
  - Cart table with inline editing for qty and diskon
  - Payment section with bayar, kembalian, grand total display
  - Action buttons (Simpan, Kembali)
- All wire:model bindings preserved from transaksi-create.
- Keyboard-first workflow preserved (Enter key handling, focus management).
- Layout proportions preserved (300px fixed left/right columns, flexible middle column).

Files:
- src/resources/views/pages/transaksi/⚡transaksi-edit/transaksi-edit.blade.php

Reviewed:
- Self review completed against PROJECT_RULE.md Desktop UI Rules and POS UI Rules.
- Verified UI consistency with transaksi-create.
- Verified no PHP modifications (as requested).

Next Milestone:
- Implement transaksi-show component
