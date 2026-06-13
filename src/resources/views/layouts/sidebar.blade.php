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

    {{-- HEADER --}}
    <div class="p-4 border-b flex justify-between items-center">
        <span class="font-bold">POS SPA</span>
        <button @click="sidebarOpen = false">✖</button>
    </div>

    {{-- MENU (SCROLLABLE) --}}
    <nav class="flex-1 overflow-y-auto p-4 space-y-2 text-sm">

        <!-- DASHBOARD -->
        <a href="{{ route('dashboard') }}" wire:navigate
           @click="setActive('dashboard')"
           :class="activeLink === 'dashboard'
               ? 'text-blue-600 font-semibold bg-blue-50'
               : 'text-gray-700 hover:bg-gray-100'"
           class="block px-2 py-2 rounded">
            🏠 Dashboard
        </a>

        <!-- USER -->
        <div x-data="{ open: activeLink === 'register-list' || activeLink === 'register' }">
            <button
                @click="open = !open"
                class="w-full flex justify-between px-2 py-2 hover:bg-gray-100 rounded text-gray-700"
            >
                <span>👤 User</span>
                <span x-text="open ? '-' : '+'"></span>
            </button>

            <div x-show="open" x-transition class="ml-4 mt-1 space-y-1">
                <a href="{{ route('register-list') }}" wire:navigate
                   @click="setActive('register-list')"
                   :class="activeLink === 'register-list'
                       ? 'text-blue-600 font-semibold bg-blue-50'
                       : 'text-gray-700 hover:bg-gray-100'"
                   class="block px-2 py-1 rounded">
                    List User
                </a>
                <a href="{{ route('register') }}" wire:navigate
                   @click="setActive('register')"
                   :class="activeLink === 'register'
                       ? 'text-blue-600 font-semibold bg-blue-50'
                       : 'text-gray-700 hover:bg-gray-100'"
                   class="block px-2 py-1 rounded">
                    Register User
                </a>
            </div>
        </div>

        <!-- BARANG -->
        <div x-data="{ open: activeLink === 'barang-list' }">
            <button
                @click="open = !open"
                class="w-full flex justify-between px-2 py-2 hover:bg-gray-100 rounded text-gray-700"
            >
                <span>📦 Barang</span>
                <span x-text="open ? '-' : '+'"></span>
            </button>

            <div x-show="open" x-transition class="ml-4 mt-1 space-y-1">
                <a href="{{ route('barang-list') }}" wire:navigate
                   @click="setActive('barang-list')"
                   :class="activeLink === 'barang-list'
                       ? 'text-blue-600 font-semibold bg-blue-50'
                       : 'text-gray-700 hover:bg-gray-100'"
                   class="block px-2 py-1 rounded">
                    List Barang
                </a>
            </div>
        </div>

        <!-- TRANSAKSI -->
        <div x-data="{ open: activeLink === 'pos-kasir' || activeLink === 'pos-admin' }">
            <button
                @click="open = !open"
                class="w-full flex justify-between px-2 py-2 hover:bg-gray-100 rounded text-gray-700"
            >
                <span>🛒 Transaksi</span>
                <span x-text="open ? '-' : '+'"></span>
            </button>
            <div x-show="open" x-transition class="ml-4 mt-1 space-y-1">
                <a href="#"
                   @click="setActive('pos-kasir')"
                   :class="activeLink === 'pos-kasir'
                       ? 'text-blue-600 font-semibold bg-blue-50'
                       : 'text-gray-700 hover:bg-gray-100'"
                   class="block px-2 py-1 rounded">
                    POS Kasir
                </a>
                <a href="#"
                   @click="setActive('pos-admin')"
                   :class="activeLink === 'pos-admin'
                       ? 'text-blue-600 font-semibold bg-blue-50'
                       : 'text-gray-700 hover:bg-gray-100'"
                   class="block px-2 py-1 rounded">
                    POS Admin
                </a>
            </div>
        </div>
    </nav>
</div>
