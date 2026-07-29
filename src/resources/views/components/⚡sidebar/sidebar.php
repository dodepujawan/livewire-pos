<?php

use App\Models\Menu;
use Livewire\Component;

new class extends Component
{
    public array $openedMenus = [];

    public function render()
    {
        return $this->view([
            'menus' => Menu::query()
                ->with('children')
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
};
