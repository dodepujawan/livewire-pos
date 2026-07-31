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
    public bool $titleCustomized = false;
    public bool $showRootMenuModal = false;
    public string $rootTitle = '';
    public ?string $rootIcon = null;
    public int $rootSortOrder = 0;
    public bool $rootIsSidebar = true;

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

    public function updatedTitle(): void
    {
        $this->titleCustomized = true;
    }

    public function updatedSystemRouteId($value): void
    {
        if (blank($value)) {
            return;
        }

        $route = SystemRoute::find($value);

        if (!$route) {
            return;
        }

        if (!$this->titleCustomized) {
            $this->title = $route->display_name;
        }
    }

    public function save(): void{
        $validated = $this->validate();

        Menu::create($validated);

        session()->flash(
            'success',
            'Menu berhasil ditambahkan.'
        );

        $this->redirectRoute('menu-list');
    }

    public function openRootMenuModal(): void
    {
        $this->reset([
            'rootTitle',
            'rootIcon',
        ]);

        $this->rootSortOrder = 0;
        $this->rootIsSidebar = true;

        $this->showRootMenuModal = true;
    }

    public function saveRootMenu(): void
    {
        $this->validate([
            'rootTitle' => 'required|max:100',
            'rootIcon' => 'nullable|max:100',
            'rootSortOrder' => 'required|integer',
        ]);

        $menu = Menu::create([
            'parent_id'       => null,
            'system_route_id' => null,
            'title'           => $this->rootTitle,
            'icon'            => $this->rootIcon,
            'sort_order'      => $this->rootSortOrder,
            'is_sidebar'      => $this->rootIsSidebar,
        ]);

        $this->parent_id = $menu->id;

        $this->showRootMenuModal = false;

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Root menu created successfully.',
        ]);
    }

    public function render()
    {
        return $this->view([
            'parentMenus' => Menu::query()
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get(),
            'systemRoutes' => SystemRoute::orderBy('route_name')->get(),
        ])
        ->layout('layouts::app')
        ->title('Create Menu');
    }
};
