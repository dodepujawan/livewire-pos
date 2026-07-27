<x-form.card>
    <x-slot:title>Create Menu</x-slot:title>

    <x-form.select label="Parent Menu" name="parent_id" wire:model="parent_id">
        <option value="">Root Menu</option>
        @foreach($parentMenus as $menu)
            <option value="{{ $menu->id }}">{{ $menu->title }}</option>
        @endforeach
    </x-form.select>

    <x-form.select label="Route" name="system_route_id" wire:model="system_route_id">
        <option value="">No Route</option>
        @foreach($systemRoutes as $route)
            <option value="{{ $route->id }}">{{ $route->route_name }}</option>
        @endforeach
    </x-form.select>

    <x-form.input label="Title" name="title" wire:model="title" />
    <x-form.input label="Icon" name="icon" wire:model="icon" />
    <x-form.input label="Sort Order" name="sort_order" type="number" wire:model="sort_order" />

    <div class="mb-6 flex items-center gap-3">
        <input id="is_sidebar" type="checkbox" wire:model="is_sidebar" class="h-4 w-4 rounded border-gray-300">
        <label for="is_sidebar">Show in Sidebar</label>
    </div>

    <div class="flex justify-end gap-3">
        <a href="{{ route('menu-list') }}" class="rounded-lg bg-gray-200 px-4 py-2">Cancel</a>
        <button type="button" class="rounded-lg bg-blue-600 px-4 py-2 text-white">Save</button>
    </div>
</x-form.card>
