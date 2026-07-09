<div class="h-screen p-3 overflow-hidden">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-2">
        <div>
            <h1 class="text-lg font-bold">Buat Transaksi</h1>
        </div>
        <a href="{{ route('transaksi-list') }}" wire:navigate class="px-3 py-1.5 border rounded hover:bg-gray-50 text-sm">Kembali</a>
    </div>

    @if (session('success'))
        <div class="mb-2 p-2 rounded bg-green-100 text-green-800 text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-2 p-2 rounded bg-red-100 text-red-800 text-sm">
            {{ session('error') }}
        </div>
    @endif

    <form wire:submit.prevent="saveTransaksi" class="h-[calc(100vh-80px)]">
        {{-- Main Grid Layout --}}
        <div class="grid grid-cols-12 gap-2 h-full">
            
            {{-- Left Column: Header & Add Item (3 cols) --}}
            <div class="col-span-3 flex flex-col gap-2">
                {{-- Header Section --}}
                <div class="bg-white rounded shadow p-2">
                    <div class="space-y-2">
                        {{-- Nomor Invoice --}}
                        <div>
                            <label class="block text-xs font-medium mb-1">No. Invoice</label>
                            <input type="text" wire:model="transNoInvoice" readonly class="w-full border rounded px-2 py-1 bg-gray-100 text-xs">
                            @error('transNoInvoice')
                                <p class="text-red-500 text-xs mt-0.5">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tanggal --}}
                        <div>
                            <label class="block text-xs font-medium mb-1">Tanggal</label>
                            <input type="date" wire:model="transTanggal" class="w-full border rounded px-2 py-1 text-xs">
                            @error('transTanggal')
                                <p class="text-red-500 text-xs mt-0.5">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Customer --}}
                        <div>
                            <label class="block text-xs font-medium mb-1">Customer</label>
                            <input type="text" wire:model="transCustomer" class="w-full border rounded px-2 py-1 text-xs" placeholder="Opsional">
                            @error('transCustomer')
                                <p class="text-red-500 text-xs mt-0.5">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Add Item Section --}}
                <div class="bg-white rounded shadow p-2 flex-1 flex flex-col">
                    <h3 class="font-semibold mb-2 text-xs">Tambah Barang</h3>
                    <div class="space-y-1.5 flex-1 flex flex-col">
                        {{-- Kode Barang --}}
                        <div>
                            <label class="block text-xs font-medium mb-1">Kode Barang</label>
                            <input 
                                type="text" 
                                wire:model.live="itemKodeBarang" 
                                wire:keydown.enter.prevent="searchBarang"
                                id="kode-barang-input"
                                class="w-full border rounded px-2 py-1 text-xs" 
                                placeholder="Scan/ketik kode..."
                            >
                            @error('itemKodeBarang')
                                <p class="text-red-500 text-xs mt-0.5">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Found Barang Details --}}
                        @if($itemNamaBarang)
                            <div class="text-xs bg-blue-50 p-1.5 rounded">
                                <strong>{{ $itemNamaBarang }}</strong>
                                <span class="ml-2 text-gray-500">Stok: {{ $itemStok }}</span>
                            </div>
                        @endif

                        <div class="grid grid-cols-5 gap-1.5">
                            {{-- Qty --}}
                            <div>
                                <label class="block text-xs font-medium mb-1">Qty</label>
                                <input 
                                    type="number" 
                                    wire:model.live.debounce.500ms="itemQty" 
                                    wire:keydown.enter.prevent="addToCart"
                                    id="qty-input"
                                    min="1" 
                                    class="w-full border rounded px-2 py-1 text-xs"
                                    @if(!$itemNamaBarang) disabled @endif
                                >
                                @error('itemQty')
                                    <p class="text-red-500 text-xs mt-0.5">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Satuan --}}
                            <div>
                                <label class="block text-xs font-medium mb-1">Satuan</label>
                                <select wire:model.live.debounce.500ms="itemBarangSatuanId" class="w-full border rounded px-2 py-1 text-xs" @disabled(empty($itemSatuanList))>
                                    <option value="0">Pilih</option>
                                    @foreach($itemSatuanList as $satuan)
                                        <option value="{{ $satuan['id'] }}">{{ $satuan['nama_satuan'] }}</option>
                                    @endforeach
                                </select>
                                @error('itemBarangSatuanId')
                                    <p class="text-red-500 text-xs mt-0.5">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Harga --}}
                            <div>
                                <label class="block text-xs font-medium mb-1">Harga</label>
                                <input type="text" value="{{ number_format($itemHarga) }}" readonly class="w-full border rounded px-2 py-1 bg-gray-100 text-xs">
                            </div>

                            {{-- Diskon --}}
                            <div>
                                <label class="block text-xs font-medium mb-1">Diskon</label>
                                <input type="number" wire:model.live.debounce.500ms="itemDiskon" min="0" class="w-full border rounded px-2 py-1 text-xs">
                                @error('itemDiskon')
                                    <p class="text-red-500 text-xs mt-0.5">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Subtotal --}}
                            <div>
                                <label class="block text-xs font-medium mb-1">Subtotal</label>
                                <input type="text" readonly class="w-full border rounded px-2 py-1 bg-gray-100 text-xs" value="{{ number_format($itemSubtotal, 0, ',', '.') }}">
                            </div>
                        </div>

                        {{-- Button Tambah --}}
                        <button type="button" wire:click="addToCart" class="w-full px-3 py-1.5 bg-green-600 text-white rounded hover:bg-green-700 text-xs" @if(!$itemNamaBarang) disabled @endif>+ Tambah ke Keranjang</button>
                    </div>
                </div>
            </div>

            {{-- Middle Column: Cart (7 cols) --}}
            <div class="col-span-7 bg-white rounded shadow p-2 flex flex-col">
                <h3 class="font-semibold mb-2 text-xs">Keranjang Belanja</h3>
                
                @if(count($cartItems) > 0)
                    <div class="flex-1 overflow-auto">
                        <table class="w-full text-xs">
                            <thead class="bg-gray-100 sticky top-0">
                                <tr>
                                    <th class="px-2 py-1.5 text-left text-xs">No</th>
                                    <th class="px-2 py-1.5 text-left text-xs">Barang</th>
                                    <th class="px-2 py-1.5 text-left text-xs">Satuan</th>
                                    <th class="px-2 py-1.5 text-right text-xs">Qty</th>
                                    <th class="px-2 py-1.5 text-right text-xs">Harga</th>
                                    <th class="px-2 py-1.5 text-right text-xs">Diskon</th>
                                    <th class="px-2 py-1.5 text-right text-xs">Subtotal</th>
                                    <th class="px-2 py-1.5 text-center text-xs w-14">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cartItems as $index => $item)
                                    <tr class="border-t">
                                        <td class="px-2 py-1.5 text-xs">{{ $index + 1 }}</td>
                                        <td class="px-2 py-1.5 text-xs">{{ $item['nama_barang'] }}</td>
                                        <td class="px-2 py-1.5 text-xs">{{ $item['nama_satuan'] }}</td>
                                        <td class="px-2 py-1.5 text-right text-xs">{{ $item['qty'] }}</td>
                                        <td class="px-2 py-1.5 text-right text-xs">{{ number_format($item['harga'], 0, ',', '.') }}</td>
                                        <td class="px-2 py-1.5 text-right text-xs">{{ number_format($item['diskon'], 0, ',', '.') }}</td>
                                        <td class="px-2 py-1.5 text-right text-xs">{{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                                        <td class="px-2 py-1.5 text-center">
                                            <button type="button" wire:click="removeFromCart({{ $index }})" class="px-1.5 py-0.5 bg-red-500 text-white rounded hover:bg-red-600 text-xs">Hapus</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="flex-1 flex items-center justify-center text-gray-500 text-xs">
                        Keranjang belanja kosong
                    </div>
                @endif

                {{-- Grand Total Display --}}
                <div class="mt-2 pt-2 border-t-2 border-blue-200 bg-gradient-to-r from-blue-50 to-blue-100 rounded p-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-semibold text-blue-800">GRAND TOTAL</span>
                        <span class="text-2xl font-black text-blue-600">{{ number_format($transGrandTotal, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            {{-- Right Column: Payment (2 cols) --}}
            <div class="col-span-2 flex flex-col gap-2">
                {{-- Payment Section --}}
                <div class="bg-white rounded shadow p-2">
                    <h3 class="font-semibold mb-2 text-xs">Pembayaran</h3>
                    <div class="space-y-2">
                        {{-- Grand Total --}}
                        <div>
                            <label class="block text-xs font-medium mb-1">Total</label>
                            <input type="text" readonly class="w-full border rounded px-2 py-1 bg-gray-100 text-xs" value="{{ number_format($transGrandTotal, 0, ',', '.') }}">
                        </div>

                        {{-- Bayar Nominal --}}
                        <div>
                            <label class="block text-xs font-medium mb-1">Bayar</label>
                            <input type="number" wire:model.live.debounce.500ms="bayarNominal" min="0" class="w-full border rounded px-2 py-1 text-xs">
                            @error('bayarNominal')
                                <p class="text-red-500 text-xs mt-0.5">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Kembalian --}}
                        <div>
                            <label class="block text-xs font-medium mb-1">Kembali</label>
                            <input type="text" readonly class="w-full border rounded px-2 py-1 bg-gray-100 text-xs" value="{{ number_format($kembaliNominal, 0, ',', '.') }}">
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex flex-col gap-1.5 mt-auto">
                    <button type="submit" class="w-full px-3 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 font-semibold text-sm">Simpan</button>
                    <a href="{{ route('transaksi-list') }}" wire:navigate class="w-full px-3 py-1.5 border rounded hover:bg-gray-50 text-center text-xs">Kembali</a>
                </div>
            </div>
        </div>
    </form>
</div>
@script
<script>
// JavaScript for focus management 
document.addEventListener('livewire:init', () => {
    // Focus kode barang input on page load
    document.getElementById('kode-barang-input').focus();

    @this.on('focus-qty', () => {
        document.getElementById('qty-input').focus();
    });

    @this.on('focus-kode-barang', () => {
        document.getElementById('kode-barang-input').focus();
    });
});
</script>
@endscript