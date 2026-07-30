<div>
    {{-- Overlay --}}
    <div
        x-show="sidebarOpen"
        x-transition.opacity
        @click="sidebarOpen = false"
        class="fixed inset-0 z-40 bg-black/40 backdrop-blur-sm lg:hidden"
    ></div>

    {{-- Sidebar --}}
    <aside
        class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-xl transform transition-transform duration-300 flex flex-col"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    >
        {{-- Header --}}
        <div class="flex items-center justify-between h-16 px-5 border-b">
            <h1 class="text-lg font-bold">POS SPA</h1>
            <button @click="sidebarOpen = false" class="p-2 rounded hover:bg-gray-100">✕</button>
        </div>

        {{-- Menu --}}
        <div class="flex-1 overflow-y-auto px-4 py-4">
            @livewire('components::sidebar')
        </div>

        {{-- Footer --}}
        <div class="border-t p-4">
            <div class="text-sm font-semibold">{{ auth()->user()->name ?? 'Guest' }}</div>
            <div class="text-xs text-gray-500">Administrator</div>
        </div>
    </aside>
</div>


{{-- <div>
    <div
        x-show="sidebarOpen"
        @click="sidebarOpen = false"
        x-transition.opacity
        class="fixed inset-0 bg-black/50 z-40"
    ></div>

    <div
        x-data="{
            activeLink: $persist('dashboard'),
            setActive(name) {
                this.activeLink = name;
                this.sidebarOpen = false;
            }
        }"
        class="fixed top-0 left-0 w-64 h-full bg-white shadow-lg z-50
               transform transition-transform duration-300 flex flex-col"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    >

        <div class="p-4 border-b flex justify-between items-center">
            <span class="font-bold">POS SPA</span>
            <button @click="sidebarOpen = false">✖</button>
        </div>

        <nav class="flex-1 overflow-y-auto p-4 space-y-2 text-sm">
            @livewire('components::sidebar')
        </nav>

    </div>
</div> --}}
