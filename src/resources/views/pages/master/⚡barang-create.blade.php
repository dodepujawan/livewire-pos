<?php

use App\Models\Barang;
use App\Models\BarangSatuan;
use Livewire\Component;

new class extends Component
{
    public string $createBarangKode = '';
    public string $createBarangNama = '';
    public int $createBarangStok = 0;
    public array $createBarangSatuanRows = [
        [
            'nama_satuan' => '',
            'konversi' => 1,
            'harga_jual' => 0,
        ]
    ];

    public function addBarangSatuanRow()
    {
        $this->createBarangSatuanRows[] = [
            'nama_satuan' => '',
            'konversi' => 1,
            'harga_jual' => 0,
        ];
    }

    public function removeBarangSatuanRow(int $rowIndex)
    {
        if (count($this->createBarangSatuanRows) <= 1) {
            return;
        }
        unset(
            $this->createBarangSatuanRows[$rowIndex]
        );
        $this->createBarangSatuanRows = array_values(
            $this->createBarangSatuanRows
        );
    }

    public function saveBarang()
    {
        $this->validate([
            'createBarangKode' => 'required|unique:barang,kode_barang',
            'createBarangNama' => 'required|min:3',
            'createBarangStok' => 'required|numeric|min:0',

            'createBarangSatuanRows.*.nama_satuan' => 'required',
            'createBarangSatuanRows.*.konversi' => 'required|numeric|min:1',
            'createBarangSatuanRows.*.harga_jual' => 'required|numeric|min:0',
        ]);

        $barang = Barang::create([
            'kode_barang' => strtoupper($this->createBarangKode),
            'nama_barang' => $this->createBarangNama,
            'stok' => $this->createBarangStok,
        ]);

        foreach ($this->createBarangSatuanRows as $satuanRow) {
            BarangSatuan::create([
                'barang_id'   => $barang->id,
                'nama_satuan' => strtoupper($satuanRow['nama_satuan']),
                'konversi'    => $satuanRow['konversi'],
                'harga_jual'  => $satuanRow['harga_jual'],
            ]);
        }

        session()->flash(
            'success',
            'Barang berhasil ditambahkan'
        );

        return $this->redirect(
            route('barang-list'),
            navigate: true
        );
    }

    public function render()
    {
        return $this->view([])
            ->layout('layouts::app')
            ->title('Tambah Barang');
    }
};

?>
<div class="max-w-7xl mx-auto p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold">Tambah Barang</h1>
        <p class="text-gray-500 text-sm">Tambahkan master barang baru.</p>
    </div>

    <form wire:submit="saveBarang">
        <div class="bg-white rounded-xl shadow p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Kode --}}
                <div>
                    <label class="block mb-2 text-sm font-medium">Kode Barang</label>
                    <input type="text" wire:model="createBarangKode" class="w-full border rounded-lg px-3 py-2">
                    @error('createBarangKode')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Nama --}}
                <div>
                    <label class="block mb-2 text-sm font-medium">Nama Barang</label>
                    <input type="text" wire:model="createBarangNama" class="w-full border rounded-lg px-3 py-2">
                    @error('createBarangNama')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6">
                <label class="block mb-2 text-sm font-medium">Stok Awal</label>
                <input type="number" wire:model="createBarangStok" class="w-full md:w-64 border rounded-lg px-3 py-2">
                @error('createBarangStok')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
        {{-- Quantity --}}
        <div class="mt-6">
            <h3 class="font-semibold mb-3">Satuan Barang</h3>

            @foreach($createBarangSatuanRows as $rowIndex => $row)
                <div class="grid grid-cols-12 gap-3 mb-3">
                    <div class="col-span-4">
                        <input type="text" wire:model="createBarangSatuanRows.{{ $rowIndex }}.nama_satuan" class="w-full border rounded-lg px-3 py-2" placeholder="Nama Satuan">
                    </div>
                    <div class="col-span-3">
                        <input type="number" wire:model="createBarangSatuanRows.{{ $rowIndex }}.konversi" class="w-full border rounded-lg px-3 py-2" placeholder="Konversi">
                    </div>
                    <div class="col-span-3">
                        <input type="number" wire:model="createBarangSatuanRows.{{ $rowIndex }}.harga_jual" class="w-full border rounded-lg px-3 py-2" placeholder="Harga">
                    </div>
                    <div class="col-span-2">
                    <button type="button" wire:click="removeBarangSatuanRow({{ $rowIndex }})" @disabled(count($createBarangSatuanRows) <= 1) class="w-full px-3 py-2 bg-red-600 text-white rounded-lg disabled:opacity-50">Hapus</button>

                </div>
                </div>
            @endforeach
            <div class="mt-4">
                <button type="button" wire:click="addBarangSatuanRow" class="px-4 py-2 bg-green-600 text-white rounded-lg">+ Tambah Satuan</button>
            </div>
        </div>
        <div class="flex gap-3 mt-6">
            <a href="{{ route('barang-list') }}" wire:navigate class="px-4 py-2 border rounded-lg">Kembali</a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Simpan Barang</button>
        </div>
    </form>
</div>
