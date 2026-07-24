<?php

use Livewire\Component;

new class extends Component
{
    public array $menuTree = [];

    public function mount(): void
    {
        // TODO: Implement new sidebar logic
        $this->menuTree = [];
    }

    public function render()
    {
        return $this->view();
    }
};
