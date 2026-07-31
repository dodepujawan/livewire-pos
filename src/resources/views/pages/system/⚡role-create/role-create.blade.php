<div>
    <x-page.header title="Tambah Role">
        <x-slot:actions>
            <a href="{{ route('system.role.list') }}" class="text-blue-600">
                ← Kembali
            </a>
        </x-slot:actions>
    </x-page.header>

    <form wire:submit="save">
        <x-form.card>
            <x-form.input
                label="Nama Role"
                name="roleName"
                wire:model="roleName"
            />
        </x-form.card>

        @foreach($permissionGroups as $group => $permissions)
            <x-form.card class="mt-5">
                <h3 class="mb-4 text-lg font-semibold">{{ $group }}</h3>
                <div class="grid grid-cols-2 gap-3">
                    @foreach($permissions as $permission)
                        <label class="flex items-center gap-2">
                            <input
                                type="checkbox"
                                value="{{ $permission->name }}"
                                wire:model="selectedPermissions"
                            >
                            <span>{{ $permission->name }}</span>
                        </label>
                    @endforeach
                </div>
            </x-form.card>
        @endforeach

        <div class="mt-6">
            <button class="rounded-lg bg-blue-600 px-5 py-2 text-white">
                Simpan
            </button>
        </div>
    </form>
</div>
