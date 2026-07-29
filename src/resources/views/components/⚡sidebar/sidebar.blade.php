<div class="space-y-2">
    @foreach($menus as $menu)
        <div>

            @if($menu->children->isNotEmpty())

                <div
                    wire:click="toggleMenu({{ $menu->id }})"
                    class="cursor-pointer flex justify-between"
                >
                    <span>{{ $menu->title }}</span>

                    <span>
                        {{ in_array($menu->id, $openedMenus) ? '▼' : '▶' }}
                    </span>
                </div>

                @if(in_array($menu->id, $openedMenus))
                    @foreach($menu->children as $child)
                        <div class="ml-6">
                            {{ $child->title }}
                        </div>
                    @endforeach
                @endif

            @else

                <a href="#">
                    {{ $menu->title }}
                </a>

            @endif

        </div>
    @endforeach
</div>
