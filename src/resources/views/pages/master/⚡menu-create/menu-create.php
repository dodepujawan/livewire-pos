<?php

use App\Models\Menu;
use App\Models\SystemRoute;
use Livewire\Component;

new class extends Component
{
    public ?int $parent_id = null;
    public ?int $system_route_id = null;
    public string $title = '';
    public ?string $icon = null;
    public int $sort_order = 1;
    public bool $is_sidebar = true;

    public function render()
    {
        return $this->view([
            'parentMenus' => Menu::orderBy('title')->get(),
            'systemRoutes' => SystemRoute::orderBy('route_name')->get(),
        ])
        ->layout('layouts::app')
        ->title('Create Menu');
    }
};
