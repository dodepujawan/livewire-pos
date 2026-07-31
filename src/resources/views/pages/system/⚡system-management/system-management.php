<?php

use Livewire\Component;
use App\Services\PermissionMatrixService;
use Spatie\Permission\Models\Role;

new class extends Component
{
    public $roles;
    public ?int $selectedRoleId = null;
    public array $permissionMatrix = [];
    public array $selectedPermissions = [];

    public function mount()
    {
        $this->roles = Role::orderBy('name')->get();

        $this->selectedRoleId = $this->roles->first()?->id;

        $this->loadRole();
    }

    public function updatedSelectedRoleId()
    {
        $this->loadRole();
    }

    public function loadRole()
    {
        $this->permissionMatrix = PermissionMatrixService::build();

        $role = Role::find($this->selectedRoleId);

        $this->selectedPermissions = $role
            ? $role->permissions->pluck('name')->toArray()
            : [];
    }

    public function save()
    {
        Role::findOrFail(
            $this->selectedRoleId
        )->syncPermissions(
            $this->selectedPermissions
        );

        session()->flash(
            'success',
            'Permission berhasil disimpan.'
        );
    }
};
