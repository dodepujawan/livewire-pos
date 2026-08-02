<?php

use App\Models\Menu;
use App\Models\SystemRoute;
use Livewire\Component;

new class extends Component
{
    public int $menuId;

    public ?int $parent_id = null;
    public ?int $system_route_id = null;
    public string $title = '';
    public ?string $icon = null;
    public int $sort_order = 1;
    public bool $is_sidebar = true;

    protected function rules(): array
    {
        return [
            'parent_id' => [
                'nullable',
                'exists:menus,id',
            ],
            'system_route_id' => [
                'nullable',
                'exists:system_routes,id',
            ],
            'title' => [
                'required',
                'string',
                'max:255',
            ],
            'icon' => [
                'nullable',
                'string',
                'max:255',
            ],
            'sort_order' => [
                'required',
                'integer',
                'min:1',
            ],
            'is_sidebar' => [
                'boolean',
            ],

        ];
    }

    public function update(): void
    {
        $validated = $this->validate();
        Menu::findOrFail($this->menuId)
            ->update($validated);
        session()->flash(
            'success',
            'Menu berhasil diperbarui.'
        );
        $this->redirectRoute('master.menu.list');
    }

    public function mount(Menu $menu): void
    {
        $this->menuId = $menu->id;

        $this->parent_id = $menu->parent_id;
        $this->system_route_id = $menu->system_route_id;
        $this->title = $menu->title;
        $this->icon = $menu->icon;
        $this->sort_order = $menu->sort_order;
        $this->is_sidebar = $menu->is_sidebar;
    }

    public function render()
    {
        return $this->view([
            'parentMenus' => Menu::orderBy('title')->get(),
            'systemRoutes' => SystemRoute::orderBy('route_name')->get(),
        ])
        ->layout('layouts::app')
        ->title('Edit Menu');
    }
};
