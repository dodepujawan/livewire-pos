<div class="space-y-2">
    @foreach($menus as $menu)
        <div>

            @if($menu->children->isNotEmpty())

                <div wire:click="toggleMenu({{ $menu->id }})"
                    @class([
                        'flex items-center justify-between h-11 px-3 rounded-lg cursor-pointer transition-colors duration-200',
                        'bg-indigo-100 text-indigo-700 font-semibold' => $this->isActive($menu) || $this->hasActiveChild($menu),
                        'hover:bg-gray-100' => !$this->isActive($menu) && !$this->hasActiveChild($menu),
                    ])
                >
                    <div class="flex items-center gap-3">
                        @if($menu->icon)
                            <i class="{{ $menu->icon }} text-lg"></i>
                        @endif
                        <span class="text-sm font-medium">
                            {{ $menu->title }}
                        </span>
                    </div>
                    <span class="text-xs transition-transform duration-200">
                        <i class="ti {{ in_array($menu->id, $openedMenus) ? 'ti-chevron-down' : 'ti-chevron-right' }}"></i>
                    </span>
                </div>

                @if(in_array($menu->id, $openedMenus))
                    <div class="mt-1 space-y-1">
                        @foreach($menu->children as $child)
                            <a
                                @if($child->systemRoute) href="{{ route($child->systemRoute->route_name) }}" @endif
                                @class([
                                    'ml-6 h-10 flex items-center rounded-lg px-3 transition-colors duration-200',
                                    'bg-indigo-100 text-indigo-700 font-medium' => $this->isActive($child),
                                    'hover:bg-gray-50' => !$this->isActive($child),
                                ])
                            >
                                {{ $child->title }}
                            </a>
                        @endforeach
                    </div>
                @endif
                @else

                <a
                    href="{{ route($menu->systemRoute->route_name) }}"
                    @class([
                        'flex items-center gap-3 h-11 px-3 rounded-lg transition-colors duration-200',
                        'bg-indigo-100 text-indigo-700 font-semibold' => $this->isActive($menu),
                        'hover:bg-gray-100' => !$this->isActive($menu),
                    ])
                >
                    @if($menu->icon)
                        <i class="{{ $menu->icon }} text-lg"></i>
                    @endif
                    <span class="text-sm font-medium">{{ $menu->title }}</span>
                </a>
            @endif

        </div>
    @endforeach
</div>
