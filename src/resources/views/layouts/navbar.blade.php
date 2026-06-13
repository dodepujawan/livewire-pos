<nav class="bg-white shadow px-4 py-3 flex items-center justify-between">

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

</nav>
