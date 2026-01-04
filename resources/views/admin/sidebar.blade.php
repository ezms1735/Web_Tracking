<aside class="w-64 h-screen bg-white shadow fixed">
    {{-- LOGO --}}
    <div class="flex items-center gap-3 px-6 py-5 border-b">
        <img src="{{ asset('img/logo.png') }}" alt="MOYA KRISTAL"
             class="w-10 h-10 object-contain">
        <span class="font-bold text-lg">MOYA KRISTAL</span>
    </div>

    {{-- MENU --}}
    <nav class="px-4 py-4 space-y-2 text-gray-700">
        <a href="{{ route('admin.dashboard') }}"
           class="flex items-center gap-3 px-4 py-2 rounded hover:bg-blue-100">
            🏠 Dashboard
        </a>

        <a href="{{ route('admin.driver.index') }}"
           class="flex items-center gap-3 px-4 py-2 rounded hover:bg-blue-100">
            🚚 Driver
        </a>

        <a href="{{ route('admin.pelanggan.index') }}"
           class="flex items-center gap-3 px-4 py-2 rounded hover:bg-blue-100">
            👤 Pelanggan
        </a>

        <a href="{{ route('admin.pesanan.index') }}"
           class="flex items-center gap-3 px-4 py-2 rounded hover:bg-blue-100">
            🧾 Pesanan
        </a>

        <a href="{{ route('admin.pengiriman.index') }}"
           class="flex items-center gap-3 px-4 py-2 rounded hover:bg-blue-100">
            📦 Pengiriman
        </a>

        <a href="{{ route('admin.laporan.index') }}"
           class="flex items-center gap-3 px-4 py-2 rounded hover:bg-blue-100">
            📊 Laporan
        </a>
    </nav>

    {{-- LOGOUT --}}
    <div class="absolute bottom-4 w-full px-4">
        <form action="{{ route('admin.logout') }}" method="POST">
            @csrf
            <button type="submit"
                class="w-full bg-red-500 text-white py-2 rounded hover:bg-red-600">
                🚪 Logout
            </button>
        </form>
    </div>
</aside>
