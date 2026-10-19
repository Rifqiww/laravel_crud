<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Selamat datang, {{ Auth::user()->name }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if ($products->count() > 0)
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-xs uppercase tracking-wider font-semibold text-gray-400 dark:text-gray-500">
                        Daftar Produk
                    </h3>
                    @hasanyrole('super-admin|admin')
                        <a href="{{ route('products.index') }}" class="text-xs font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 transition-colors">
                            Kelola Produk
                        </a>
                    @endhasanyrole
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach ($products as $product)
                        <a href="{{ route('products.show', $product) }}" class="group block bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden hover:border-gray-400 dark:hover:border-gray-500 transition-colors">
                            <div class="aspect-[4/3] bg-gray-50 dark:bg-gray-900 border-b border-gray-100 dark:border-gray-700/60 overflow-hidden flex items-center justify-center">
                                @if ($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="text-gray-300 dark:text-gray-600">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                @endif
                            </div>

                            <div class="p-4">
                                <div class="flex items-center justify-between gap-2">
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">
                                        {{ $product->name }}
                                    </p>
                                    @if ($product->category)
                                        <span class="text-[10px] text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700/60 px-1.5 py-0.5 rounded shrink-0">
                                            {{ $product->category }}
                                        </span>
                                    @endif
                                </div>
                                <p class="text-sm font-semibold text-gray-900 dark:text-gray-100 mt-1.5">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </p>
                                <div class="flex items-center justify-between mt-3 text-xs text-gray-400">
                                    <span>Stok: {{ $product->stock }}</span>
                                    <span>{{ $product->updated_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="text-center py-20 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg">
                    <p class="text-gray-400 dark:text-gray-500 text-sm">Tidak ada product</p>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
