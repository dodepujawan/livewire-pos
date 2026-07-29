<x-form.card>
    <x-slot:title>
        <div class="flex items-center justify-between">
            <div class="flex flex-col">
                <span class="text-lg font-semibold">Create Menu</span>
                <span class="text-sm text-gray-500">Add a new navigation/menu item for your application</span>
            </div>
        </div>
    </x-slot:title>

    @if (session('success'))
        <div class="mb-5 rounded-lg bg-green-100 px-4 py-3 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-2 bg-white p-4 rounded-lg shadow-sm">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
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

                    <div class="flex items-center gap-3">
                        <input id="is_sidebar" type="checkbox" wire:model="is_sidebar" class="h-4 w-4 rounded border-gray-300">
                        <label for="is_sidebar" class="text-sm">Show in Sidebar</label>
                    </div>
                </div>
            </div>

            <div class="md:col-span-1">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-sm font-medium text-gray-700 mb-3">Preview</h3>
                    <div class="border rounded p-3 bg-white">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded flex items-center justify-center bg-blue-50 text-blue-600 text-xl">
                                @if(!empty($icon))
                                    <i class="{{ $icon }}"></i>
                                @else
                                    <!-- simple placeholder icon -->
                                    <svg class="w-5 h-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                    </svg>
                                @endif
                            </div>
                            <div>
                                <div class="font-semibold text-gray-800 text-lg">{{ $title ?? 'Menu Title' }}</div>
                                <div class="text-sm text-gray-500">Parent: {{ optional($parentMenus->firstWhere('id', $parent_id))->title ?? 'Root' }}</div>
                            </div>
                        </div>

                        <div class="mt-3 text-sm text-gray-500">Route: {{ optional($systemRoutes->firstWhere('id', $system_route_id))->route_name ?? 'No Route' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('menu-list') }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                Cancel
            </a>
            <button type="button" wire:click="save" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm text-white hover:bg-blue-700">
                Save
            </button>
        </div>
    </div>
</x-form.card>
