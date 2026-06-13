<?php

use App\Models\Barang;
use App\Models\BarangSatuan;
use Livewire\Component;

new class extends Component
{
    public int $editBarangId;
    public string $editBarangKode = '';
    public string $editBarangNama = '';
    public int $editBarangStok = 0;
    public array $editBarangSatuanRows = [];
    public array $deleteBarangSatuanIds = [];

    public function mount($id){
        $barang = Barang::findOrFail($id);
        $this->editBarangId = $barang->id;
        $this->editBarangKode = $barang->kode_barang;
        $this->editBarangNama = $barang->nama_barang;
        $this->editBarangStok = $barang->stok;
        $this->editBarangSatuanRows =
        $barang->satuan
            ->map(function ($satuan) {
                return [
                    'id' => $satuan->id,
                    'nama_satuan' => $satuan->nama_satuan,
                    'konversi' => $satuan->konversi,
                    'harga_jual' => (int) $satuan->harga_jual,
                ];
            })
            ->toArray();
    }

    public function addEditBarangSatuanRow(){
        $this->editBarangSatuanRows[] = [
            'id' => null,
            'nama_satuan' => '',
            'konversi' => 1,
            'harga_jual' => 0,
        ];
    }

    public function removeEditBarangSatuanRow(int $rowIndex){
        if (count($this->editBarangSatuanRows) <= 1) {
            return;
        }
        $row = $this->editBarangSatuanRows[$rowIndex];
        if (!empty($row['id'])) {
            $this->deleteBarangSatuanIds[] = $row['id'];
        }
        unset($this->editBarangSatuanRows[$rowIndex]);
        $this->editBarangSatuanRows = array_values($this->editBarangSatuanRows);
    }

    public function updateBarang(){
        $this->validate([
            'editBarangKode' => 'required|unique:barang,kode_barang,' . $this->editBarangId,
            'editBarangNama' => 'required|min:3',
            'editBarangStok' => 'required|numeric|min:0',
            'editBarangSatuanRows.*.nama_satuan' => 'required',
            'editBarangSatuanRows.*.konversi' => 'required|numeric|min:1',
            'editBarangSatuanRows.*.harga_jual' => 'required|numeric|min:0',
        ]);

        $barang = Barang::findOrFail(
            $this->editBarangId
        );
        $barang->update([
            'kode_barang' => strtoupper($this->editBarangKode),
            'nama_barang' => $this->editBarangNama,
            'stok' => $this->editBarangStok,
        ]);

        foreach ($this->deleteBarangSatuanIds as $deleteId) {
            BarangSatuan::where('id', $deleteId)->delete();
        }

        foreach ($this->editBarangSatuanRows as $satuanRow) {
            if ($satuanRow['id']) {
                BarangSatuan::findOrFail($satuanRow['id'])->update([
                    'nama_satuan' => strtoupper($satuanRow['nama_satuan']),
                    'konversi'    => $satuanRow['konversi'],
                    'harga_jual'  => $satuanRow['harga_jual'],
                ]);
            } else {
                BarangSatuan::create([
                    'barang_id'   => $this->editBarangId,
                    'nama_satuan' => strtoupper($satuanRow['nama_satuan']),
                    'konversi'    => $satuanRow['konversi'],
                    'harga_jual'  => $satuanRow['harga_jual'],
                ]);
            }
        }

        session()->flash(
            'success',
            'Barang berhasil diperbarui'
        );
        return $this->redirect(
            route('barang-list'),
            navigate: true
        );
    }

    public function render(){
        return $this->view([])
            ->layout('layouts::app')
            ->title('Edit Barang');
    }
};

?>
<div class="max-w-7xl mx-auto p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold">Edit Barang</h1>
        <p class="text-gray-500 text-sm">Ubah data master barang.</p>
    </div>

    <form wire:submit="updateBarang">
        <div class="bg-white rounded-xl shadow p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block mb-2 text-sm font-medium">Kode Barang</label>
                    <input type="text" wire:model="editBarangKode" class="w-full border rounded-lg px-3 py-2">
                    @error('editBarangKode')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label class="block mb-2 text-sm font-medium">Nama Barang</label>
                    <input type="text" wire:model="editBarangNama" class="w-full border rounded-lg px-3 py-2">
                    @error('editBarangNama')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mt-6">
                <label class="block mb-2 text-sm font-medium">Stok</label>
                <input type="number" wire:model="editBarangStok" class="w-64 border rounded-lg px-3 py-2">
                @error('editBarangStok')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mt-6">
            <h3 class="font-semibold mb-3">Satuan Barang</h3>
            @foreach($editBarangSatuanRows as $rowIndex => $row)
                <div class="grid grid-cols-12 gap-3 mb-3">
                    <div class="col-span-4">
                        <input type="text" wire:model="editBarangSatuanRows.{{ $rowIndex }}.nama_satuan" class="w-full border rounded-lg px-3 py-2">
                    </div>
                    <div class="col-span-3">
                        <input type="number" wire:model="editBarangSatuanRows.{{ $rowIndex }}.konversi" class="w-full border rounded-lg px-3 py-2">
                    </div>
                    <div class="col-span-3">
                        <input type="number" wire:model="editBarangSatuanRows.{{ $rowIndex }}.harga_jual" class="w-full border rounded-lg px-3 py-2">
                    </div>
                    <div class="col-span-2">
                        <button type="button" wire:click="removeEditBarangSatuanRow({{ $rowIndex }})" class="w-full px-3 py-2 bg-red-600 text-white rounded-lg">Hapus</button>
                    </div>
                </div>
            @endforeach
            <button type="button" wire:click="addEditBarangSatuanRow" class="px-4 py-2 bg-green-600 text-white rounded-lg">+ Tambah Satuan </button>
        </div>

        <div class="flex gap-3 mt-6">
            <a href="{{ route('barang-list') }}" wire:navigate class="px-4 py-2 border rounded-lg">Kembali</a>
            <button type="submit" class="px-4 py-2 bg-amber-500 text-white rounded-lg">Update Barang</button>
        </div>
    </form>
</div>
