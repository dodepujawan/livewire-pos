<?php

use App\Models\Menu;
use Livewire\Component;

new class extends Component
{
    public string $searchMenuKeyword = '';

    public function render()
    {
        $menuData = Menu::query()
            ->with([
                'parent',
                'systemRoute',
            ])
            ->when(
                $this->searchMenuKeyword,
                fn ($query) => $query->where(
                    'title',
                    'like',
                    "%{$this->searchMenuKeyword}%"
                )
            )
            ->orderBy('parent_id')
            ->orderBy('sort_order')
            ->get();

        return $this->view([
            'menuData' => $menuData,
        ])
        ->layout('layouts::app')
        ->title('Menu Management');
    }
};
