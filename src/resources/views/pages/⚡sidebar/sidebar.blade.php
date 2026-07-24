<div>
    @forelse ($menuTree as $menu)
        <div class="mb-2">
            @if (empty($menu['children']))
                <!-- Single menu item -->
                <a href="{{ route($menu['route_name']) }}"
                   wire:navigate
                   class="flex items-center px-2 py-2 rounded hover:bg-gray-100 transition text-gray-700">
                    @if (!empty($menu['icon']))
                        <span class="mr-2">{{ $menu['icon'] }}</span>
                    @endif
                    <span>{{ $menu['display_name'] }}</span>
                </a>
            @else
                <div x-data="{ open: false }">
                    <button
                        @click="open = !open"
                        class="w-full flex justify-between items-center px-2 py-2 hover:bg-gray-100 rounded text-gray-700"
                    >
                        <div class="flex items-center">
                            @if (!empty($menu['icon']))
                                <span class="mr-2">{{ $menu['icon'] }}</span>
                            @endif
                            <span>{{ $menu['display_name'] }}</span>
                        </div>
                        <span x-text="open ? '-' : '+'"></span>
                    </button>

                    <div x-show="open" x-transition class="ml-4 mt-1 space-y-1">
                        @foreach ($menu['children'] as $child)
                            <a href="{{ route($child['route_name']) }}"
                               wire:navigate
                               class="flex items-center px-2 py-1 rounded hover:bg-gray-100 transition text-gray-700 text-sm">
                                @if (!empty($child['icon']))
                                    <span class="mr-2">{{ $child['icon'] }}</span>
                                @endif
                                <span>{{ $child['display_name'] }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    @empty
        <div class="text-gray-400 text-sm">
            Tidak ada menu tersedia
        </div>
    @endforelse
</div>