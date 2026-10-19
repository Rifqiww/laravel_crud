<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ auth()->user()->hasAnyRole(['super-admin', 'admin']) ? route('products.index') : route('dashboard') }}" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Detail Produk
                </h2>
            </div>

            @hasanyrole('super-admin|admin')
            <div class="flex items-center gap-2">
                <a href="{{ route('products.edit', $product) }}" class="px-3.5 py-1.5 text-sm font-medium rounded-lg border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                    Edit
                </a>
                <form method="POST" action="{{ route('products.destroy', $product) }}" onsubmit="return confirm('Hapus produk ini?');" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-3.5 py-1.5 text-sm font-medium rounded-lg border border-red-200 dark:border-red-900/60 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/30 transition-colors">
                        Hapus
                    </button>
                </form>
            </div>
            @endhasanyrole
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-6 sm:p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                    <div class="aspect-square bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden flex items-center justify-center">
                        @if ($product->image_url)
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="text-center text-gray-300 dark:text-gray-600">
                                <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <span class="text-xs mt-2 block">Tidak ada foto</span>
                            </div>
                        @endif
                    </div>

                    <div class="flex flex-col justify-between">
                        <div>
                            @if ($product->category)
                                <span class="text-xs uppercase tracking-wider font-semibold text-gray-400 dark:text-gray-500">
                                    {{ $product->category }}
                                </span>
                            @endif

                            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-1">
                                {{ $product->name }}
                            </h1>

                            <div class="mt-4">
                                <p class="text-xs text-gray-400 uppercase tracking-wider">Harga</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mt-0.5">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </p>
                            </div>

                            <div class="mt-4">
                                <p class="text-xs text-gray-400 uppercase tracking-wider">Ketersediaan Stok</p>
                                <div class="mt-1">
                                    @if ($product->stock == 0)
                                        <span class="inline-flex items-center gap-1.5 text-xs font-medium text-red-600 dark:text-red-400">
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                            Stok Habis
                                        </span>
                                    @elseif ($product->stock < 10)
                                        <span class="inline-flex items-center gap-1.5 text-xs font-medium text-amber-600 dark:text-amber-400">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                            Tersisa {{ $product->stock }} unit
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 text-xs font-medium text-emerald-600 dark:text-emerald-400">
                                            {{ $product->stock }} unit
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="mt-6 pt-6 border-t border-gray-100 dark:border-gray-700">
                                <h3 class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-2">Deskripsi</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed whitespace-pre-line">
                                    {{ $product->description ?: 'Belum ada deskripsi untuk produk ini.' }}
                                </p>
                            </div>
                        </div>

                        <div class="mt-6 pt-6 border-t border-gray-100 dark:border-gray-700 flex items-center justify-between text-xs text-gray-400">
                            <span>Dibuat: {{ $product->created_at->format('d M Y') }}</span>
                            <span>Diperbarui: {{ $product->updated_at->diffForHumans() }}</span>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
