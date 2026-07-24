<div>
    {{-- OVERLAY --}}
    <div
        x-show="sidebarOpen"
        @click="sidebarOpen = false"
        x-transition.opacity
        class="fixed inset-0 bg-black/50 z-40"
    ></div>

    {{-- SIDEBAR --}}
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
            @livewire('pages::sidebar')
        </nav>

    </div>
</div>