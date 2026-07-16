<?php

use App\Services\SidebarService;
use Livewire\Component;

new class extends Component
{
    public array $menuTree = [];

    public function mount(): void
    {
        $sidebarService = new SidebarService();
        $this->menuTree = $sidebarService->getMenuTree();
    }

    public function render()
    {
        return $this->view();
    }
};
