<x-form.select
    label="Role"
    name="selectedRoleId"
    wire:model.live="selectedRoleId"
>

    @foreach($roles as $role)

        <option
            value="{{ $role->id }}"
        >
            {{ $role->name }}
        </option>

    @endforeach

</x-form.select>
<table class="min-w-full">

    <thead>

        <tr>

            <th>Menu</th>

            <th>View</th>

            <th>Create</th>

            <th>Update</th>

            <th>Delete</th>

            <th>Print</th>

            <th>Export</th>

        </tr>

    </thead>

    <tbody>

    @foreach($permissionMatrix as $resource)

        <tr>

            <td>

                <div class="font-medium">

                    {{ $resource['label'] }}

                </div>

                <div class="text-xs text-gray-400">

                    {{ $resource['resource'] }}

                </div>

            </td>

            @foreach([
                'view',
                'create',
                'update',
                'delete',
                'print',
                'export'
            ] as $action)

                <td class="text-center">

                    @if(isset($resource['actions'][$action]))

                        <input
                            type="checkbox"
                            wire:model="selectedPermissions"
                            value="{{ $resource['actions'][$action] }}"
                        >

                    @endif

                </td>

            @endforeach

        </tr>

    @endforeach

    </tbody>

</table>
<div class="mt-6 flex justify-end">

    <button
        wire:click="save"
        class="rounded-lg bg-blue-600 px-5 py-2 text-white"
    >

        Simpan Permission

    </button>

</div>
