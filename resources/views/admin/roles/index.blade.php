<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Pengaturan Role') }}
            </h2>
            <button
                x-data=""
                x-on:click.prevent="$dispatch('open-modal', 'add-user-modal')"
                class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-gray-900 dark:bg-gray-100 text-white dark:text-gray-900 text-sm font-medium rounded-lg hover:bg-gray-800 dark:hover:bg-white transition-colors"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Pengguna
            </button>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="px-4 py-3 rounded-lg bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="px-4 py-3 rounded-lg bg-red-50 dark:bg-red-950/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Total Pengguna</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mt-1">{{ $totalUsers }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Super Admin</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mt-1">{{ $totalSuperAdmins }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Admin</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mt-1">{{ $totalAdmins }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Member</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mt-1">{{ $totalMembers }}</p>
                </div>
            </div>

            <form x-ref="form" method="GET" action="{{ route('admin.roles.index') }}" class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari nama atau email..."
                        class="w-full pl-9 pr-4 py-2 text-sm rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition"
                    />
                </div>
                <div x-data="{
                    open: false,
                    selected: '{{ request('role') }}',
                    roles: {{ json_encode($roles->pluck('name')->all()) }},
                    selectRole(val) {
                        this.selected = val;
                        this.open = false;
                        this.$nextTick(() => { this.$refs.form.submit(); });
                    }
                }" @click.outside="open = false" class="relative sm:w-48">
                    <input type="hidden" name="role" :value="selected">
                    <button
                        type="button"
                        @click="open = !open"
                        class="w-full flex items-center justify-between py-2 px-3.5 text-sm rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 hover:border-gray-300 dark:hover:border-gray-600 focus:outline-none transition-colors"
                    >
                        <span class="truncate" x-text="selected ? selected : 'Semua Role'"></span>
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
                            @click="selectRole('')"
                            class="w-full text-left px-3.5 py-2 text-sm transition-colors flex items-center justify-between"
                            :class="!selected ? 'bg-blue-600 text-white font-medium' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700/60'"
                        >
                            <span>Semua Role</span>
                        </button>
                        <template x-for="r in roles" :key="r">
                            <button
                                type="button"
                                @click="selectRole(r)"
                                class="w-full text-left px-3.5 py-2 text-sm transition-colors flex items-center justify-between"
                                :class="selected === r ? 'bg-blue-600 text-white font-medium' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700/60'"
                            >
                                <span x-text="r"></span>
                            </button>
                        </template>
                    </div>
                </div>
                <button type="submit" class="px-4 py-2 text-sm font-medium rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                    Filter
                </button>
                @if(request('search') || request('role'))
                    <a href="{{ route('admin.roles.index') }}" class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors">
                        Reset
                    </a>
                @endif
            </form>

            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                <table class="w-full text-left text-sm">
                    <thead class="text-xs text-gray-500 dark:text-gray-400 uppercase border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th class="py-3 px-4">Pengguna</th>
                            <th class="py-3 px-4">Role</th>
                            <th class="py-3 px-4">Ubah Role</th>
                            <th class="py-3 px-4 hidden sm:table-cell">Terdaftar</th>
                            <th class="py-3 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
                        @forelse ($users as $user)
                            @php
                                $currentRole = $user->roles->first()?->name ?? 'member';
                                $isMe = $user->id === auth()->id();
                            @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                <td class="py-3 px-4">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase shrink-0">
                                            {{ substr($user->name, 0, 2) }}
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900 dark:text-gray-100 flex items-center gap-2">
                                                <span>{{ $user->name }}</span>
                                                @if ($isMe)
                                                    <span class="text-[10px] text-gray-400 border border-gray-200 dark:border-gray-700 px-1.5 py-0.2 rounded">Anda</span>
                                                @endif
                                            </div>
                                            <div class="text-xs text-gray-400">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>

                                <td class="py-3 px-4">
                                    <span class="text-xs font-medium px-2 py-0.5 rounded {{ $currentRole === 'super-admin' ? 'bg-purple-50 dark:bg-purple-950/40 text-purple-700 dark:text-purple-300' : ($currentRole === 'admin' ? 'bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300') }}">
                                        {{ $currentRole }}
                                    </span>
                                </td>

                                <td class="py-3 px-4">
                                    <form method="POST" action="{{ route('admin.users.updateRole', $user) }}">
                                        @csrf
                                        @method('PATCH')
                                        <select
                                            name="role"
                                            onchange="this.form.submit()"
                                            {{ $isMe ? 'disabled' : '' }}
                                            class="text-xs py-1 px-2.5 rounded border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-1 focus:ring-gray-400 transition cursor-pointer disabled:opacity-40 disabled:cursor-not-allowed"
                                        >
                                            @foreach ($roles as $r)
                                                <option value="{{ $r->name }}" {{ $currentRole === $r->name ? 'selected' : '' }}>
                                                    {{ $r->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </form>
                                </td>

                                <td class="py-3 px-4 hidden sm:table-cell text-xs text-gray-400">
                                    {{ $user->created_at ? $user->created_at->format('d M Y') : '—' }}
                                </td>

                                <td class="py-3 px-4 text-right">
                                    @if (!$isMe)
                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Hapus pengguna {{ $user->name }}?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors" title="Hapus Pengguna">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-xs text-gray-400">Akun saat ini</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-gray-400 dark:text-gray-500 text-sm">
                                    Tidak ada pengguna ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $users->links() }}
            </div>

        </div>
    </div>

    <x-modal name="add-user-modal" focusable>
        <form method="POST" action="{{ route('admin.users.store') }}" class="p-6">
            @csrf

            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                Tambah Pengguna
            </h2>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 mb-5">
                Buat akun pengguna baru dan tentukan rolenya.
            </p>

            <div class="space-y-4">
                <div>
                    <x-input-label for="new_name" value="Nama Lengkap" />
                    <x-text-input id="new_name" name="name" type="text" class="mt-1 block w-full" required />
                </div>

                <div>
                    <x-input-label for="new_email" value="Email" />
                    <x-text-input id="new_email" name="email" type="email" class="mt-1 block w-full" required />
                </div>

                <div>
                    <x-input-label for="new_password" value="Password" />
                    <x-text-input id="new_password" name="password" type="password" class="mt-1 block w-full" required />
                </div>

                <div>
                    <x-input-label for="new_role" value="Role" />
                    <select id="new_role" name="role" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm shadow-sm">
                        <option value="member">member</option>
                        <option value="admin">admin</option>
                        <option value="super-admin">super-admin</option>
                    </select>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                <button type="button" x-on:click="$dispatch('close')" class="px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-gray-900 dark:bg-gray-100 text-white dark:text-gray-900 text-sm font-medium rounded-lg hover:bg-gray-800 dark:hover:bg-white transition-colors">
                    Simpan
                </button>
            </div>
        </form>
    </x-modal>
</x-app-layout>
