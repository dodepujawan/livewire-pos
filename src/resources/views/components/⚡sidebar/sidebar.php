<?php

use App\Models\Menu;
use Livewire\Component;

new class extends Component
{
    public array $openedMenus = [];
    public string $currentRoute = '';

    public function render()
    {
        return $this->view([
            'menus' => Menu::query()
                ->with([
                    'systemRoute',
                    'children.systemRoute',
                ])
                ->whereNull('parent_id')
                ->where('is_sidebar', true)
                ->orderBy('sort_order')
                ->get(),
        ]);
    }

    public function toggleMenu(int $menuId): void
    {
        if (in_array($menuId, $this->openedMenus)) {
            $this->openedMenus = array_diff(
                $this->openedMenus,
                [$menuId]
            );
            return;
        }
        $this->openedMenus[] = $menuId;
    }

    public function isActive(Menu $menu): bool
    {
        return optional($menu->systemRoute)->route_name === $this->currentRoute;
    }

    public function hasActiveChild(Menu $menu): bool
    {
        foreach ($menu->children as $child) {
            if ($this->isActive($child)) {
                return true;
            }
        }
        return false;
    }

    public function autoExpandParent(): void
    {
        $menus = Menu::query()
            ->with(['children.systemRoute'])
            ->whereNull('parent_id')
            ->get();
        foreach ($menus as $menu) {
            if ($this->hasActiveChild($menu)) {
                $this->openedMenus[] = $menu->id;
            }
        }
    }

    public function mount(): void
    {
        $this->currentRoute = request()->route()?->getName() ?? '';
        $this->autoExpandParent();
    }
};
