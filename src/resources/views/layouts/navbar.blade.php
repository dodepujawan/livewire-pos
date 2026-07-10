{{-- WRAPPER: memberi jarak dari tepi layar --}}
<div class="p-4 md:p-6 max-w-7xl mx-auto">

    {{-- NAVBAR CARD --}}
    <nav class="bg-white rounded-2xl shadow-lg px-5 py-3 flex items-center justify-between flex-wrap gap-3">

        {{-- LEFT: Toggle + Logo + Title --}}
        <div class="flex items-center gap-3">
            {{-- Toggle Sidebar --}}
            <button @click="sidebarOpen = true" class="text-2xl leading-none text-gray-600 hover:text-gray-900">
                ☰
            </button>

            {{-- Logo / Icon --}}
            <div class="w-9 h-9 bg-blue-600 rounded-xl flex items-center justify-center text-white font-bold text-sm shadow-sm">
                LA
            </div>

            <div class="leading-tight">
                <div class="text-[10px] text-gray-400 font-medium tracking-wide">PT LINTAS ANUGRAH</div>
                <div class="font-semibold text-gray-800 text-sm">Dashboard Operasional</div>
            </div>
        </div>

        {{-- CENTER: Date Filter --}}
        <!-- <div class="flex items-center gap-2 text-sm bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-200 shadow-inner">
            <span class="text-gray-400">📅</span>
            <input type="date" value="{{ now()->format('Y-m-d') }}" 
                   class="bg-transparent border-none outline-none w-28 text-gray-700 text-sm">
            <span class="text-gray-300">—</span>
            <input type="date" value="{{ now()->format('Y-m-d') }}"
                   class="bg-transparent border-none outline-none w-28 text-gray-700 text-sm">
        </div> -->

        {{-- RIGHT: Kantor + User Profile --}}
        <div class="flex items-center gap-4 text-sm">

            {{-- Kantor --}}
            <div class="flex items-center gap-1.5 text-gray-600 bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-200">
                <span>📍</span>
                <span class="font-medium">Kantor Utama</span>
            </div>

            {{-- User Profile with Dropdown --}}
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" 
                        class="flex items-center gap-2 px-3 py-1.5 rounded-full hover:bg-gray-100 transition border border-transparent hover:border-gray-200">
                    <span class="w-7 h-7 rounded-full bg-blue-500 text-white flex items-center justify-center text-xs font-bold">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </span>
                    <span class="font-medium text-gray-700">{{ auth()->user()->name }}</span>
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                {{-- Dropdown --}}
                <div x-show="open" @click.away="open = false"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl py-1 z-50 border border-gray-100">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" 
                                class="w-full text-left px-4 py-2.5 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-b-xl">
                            🚪 Log Out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
</div>
<!-- <nav class="bg-white shadow px-4 py-3 flex items-center justify-between">

    <div class="flex items-center gap-4 ml-5">

        {{-- TOGGLE --}}
        <button
            @click="sidebarOpen = true"
            class="text-3xl leading-none"
        >
            ☰
        </button>

        <span class="font-semibold">POS SPA</span>
    </div>

    <div class="flex items-center gap-4">
        <span>{{ auth()->user()->name }}</span>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="text-red-500">Logout</button>
        </form>
    </div>

</nav> -->
