<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Produk') }}
            </h2>
            <a href="{{ route('products.create') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-gray-900 dark:bg-gray-100 text-white dark:text-gray-900 text-sm font-medium rounded-lg hover:bg-gray-800 dark:hover:bg-white transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-5">

            @if (session('success'))
                <div class="px-4 py-3 rounded-lg bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <form x-ref="form" method="GET" action="{{ route('products.index') }}" class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk..." class="w-full pl-9 pr-4 py-2 text-sm rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition" />
                </div>
                <div x-data="{
                    open: false,
                    selected: '{{ request('category') }}',
                    categories: {{ json_encode($categories->values()->all()) }},
                    selectCategory(val) {
                        this.selected = val;
                        this.open = false;
                        this.$nextTick(() => { this.$refs.form.submit(); });
                    }
                }" @click.outside="open = false" class="relative sm:w-48">
                    <input type="hidden" name="category" :value="selected">
                    <button
                        type="button"
                        @click="open = !open"
                        class="w-full flex items-center justify-between py-2 px-3.5 text-sm rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 hover:border-gray-300 dark:hover:border-gray-600 focus:outline-none transition-colors"
                    >
                        <span class="truncate" x-text="selected ? selected : 'Semua Kategori'"></span>
                        <svg class="w-4 h-4 ml-2 text-gray-400 shrink-0 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div
                        x-show="open"
                        x-cloak
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="absolute left-0 z-50 mt-1.5 w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg overflow-hidden py-1 max-h-60 overflow-y-auto"
                    >
                        <button
                            type="button"
                            @click="selectCategory('')"
                            class="w-full text-left px-3.5 py-2 text-sm transition-colors flex items-center justify-between"
                            :class="!selected ? 'bg-blue-600 text-white font-medium' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700/60'"
                        >
                            <span>Semua Kategori</span>
                        </button>
                        <template x-for="cat in categories" :key="cat">
                            <button
                                type="button"
                                @click="selectCategory(cat)"
                                class="w-full text-left px-3.5 py-2 text-sm transition-colors flex items-center justify-between"
                                :class="selected === cat ? 'bg-blue-600 text-white font-medium' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700/60'"
                            >
                                <span x-text="cat"></span>
                            </button>
                        </template>
                    </div>
                </div>
                <button type="submit" class="px-4 py-2 text-sm font-medium rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">Filter</button>
                @if(request('search') || request('category'))
                    <a href="{{ route('products.index') }}" class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors">Reset</a>
                @endif
            </form>

            @if ($products->count() > 0)
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-gray-500 dark:text-gray-400 uppercase border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th class="py-3 px-4">Produk</th>
                                <th class="py-3 px-4 hidden sm:table-cell">Kategori</th>
                                <th class="py-3 px-4">Harga</th>
                                <th class="py-3 px-4 hidden md:table-cell">Stok</th>
                                <th class="py-3 px-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
                            @foreach ($products as $product)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                    <td class="py-3 px-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded bg-gray-100 dark:bg-gray-700 overflow-hidden shrink-0">
                                                @if ($product->image)
                                                    <img src="{{ asset('storage/' . $product->image) }}" alt="" class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center text-gray-300 dark:text-gray-600">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                    </div>
                                                @endif
                                            </div>
                                            <a href="{{ route('products.show', $product) }}" class="font-medium text-gray-900 dark:text-gray-100 hover:underline truncate max-w-[200px]">{{ $product->name }}</a>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 hidden sm:table-cell text-gray-500 dark:text-gray-400">{{ $product->category ?? '—' }}</td>
                                    <td class="py-3 px-4 text-gray-900 dark:text-gray-100 font-medium">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                    <td class="py-3 px-4 hidden md:table-cell">
                                        @if ($product->stock == 0)
                                            <span class="text-red-500 text-xs font-medium">Habis</span>
                                        @elseif ($product->stock < 10)
                                            <span class="text-amber-500 text-xs font-medium">{{ $product->stock }}</span>
                                        @else
                                            <span class="text-gray-600 dark:text-gray-400 text-xs">{{ $product->stock }}</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-right">
                                        <div class="flex items-center justify-end gap-1">
                                            <a href="{{ route('products.edit', $product) }}" class="p-1.5 text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-colors" title="Edit">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </a>
                                            <form method="POST" action="{{ route('products.destroy', $product) }}" onsubmit="return confirm('Hapus produk ini?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors" title="Hapus">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $products->links() }}
                </div>
            @else
                <div class="text-center py-16">
                    <p class="text-gray-400 dark:text-gray-500 text-sm">Tidak ada product</p>
                    <a href="{{ route('products.create') }}" class="inline-block mt-4 text-sm text-gray-700 dark:text-gray-300 underline hover:no-underline">Tambah produk baru</a>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
