<?php

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

new class extends Component
{
    public string $roleName = '';

    public array $selectedPermissions = [];

    public function save()
    {
        $this->validate([
            'roleName' => [
                'required',
                'string',
                'max:255',
                'unique:roles,name',
            ],
        ]);

        $role = Role::create([
            'name' => $this->roleName,
            'guard_name' => 'web',
        ]);

        $role->syncPermissions(
            $this->selectedPermissions
        );

        session()->flash(
            'success',
            'Role berhasil dibuat.'
        );

        return $this->redirectRoute(
            'system.role.list',
            navigate: true
        );
    }

    public function render()
    {
        $permissions = Permission::query()
            ->orderBy('name')
            ->get()
            ->groupBy(function ($permission) {

                $segments = explode('.', $permission->name);

                return strtoupper($segments[0] ?? 'LAINNYA');

            });

        return $this->view([
            'permissionGroups' => $permissions,
        ])
        ->layout('layouts::app')
        ->title('Tambah Role');
    }
};
