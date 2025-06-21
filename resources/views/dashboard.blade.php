<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-white">
            To-Do List
        </h2>
    </x-slot>

    <div class="p-6 max-w-2xl mx-auto bg-gray-800 rounded-xl shadow-md space-y-6 text-black">
        <!-- Form filter tanggal -->
        <form method="GET" class="mb-4 flex gap-2">
            <input type="date" name="filter_date" value="{{ request('filter_date', date('Y-m-d')) }}" class="rounded px-2 py-1">
            <button class="bg-yellow-400 px-3 py-1 rounded">Lihat</button>
        </form>

        <!-- Form tambah tugas -->
        <form action="/todo" method="POST" class="flex flex-col sm:flex-row items-stretch gap-3">
            @csrf
            <input 
                name="title" 
                placeholder="Tambahkan tugas baru..." 
                class="flex-1 px-4 py-2 rounded-lg bg-gray-700 text-black border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-400" 
                required
            >
            <input 
                type="date" 
                name="date" 
                value="{{ request('filter_date', date('Y-m-d')) }}" 
                class="rounded px-2 py-2"
                required
            >
            <button 
                type="submit" 
                class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-4 py-2 rounded-lg transition-all"
            >
                Tambah
            </button>
        </form>

        <!-- List tugas -->
        <ul class="space-y-3">
            @forelse($todos as $todo)
                <li x-data="{ open: false }" class="flex flex-col gap-2 bg-gray-700 p-4 rounded-lg shadow-sm border border-gray-600 {{ $todo->is_done ? 'opacity-60' : '' }}">
                    <div class="flex justify-between items-center">
                        <div class="text-lg {{ $todo->is_done ? 'line-through text-gray-400' : 'text-white' }}">
                            {{ $todo->title }}
                            <span class="text-xs text-gray-300 ml-2">({{ \Carbon\Carbon::parse($todo->date)->translatedFormat('l, d M Y') }})</span>
                        </div>
                        <div class="flex items-center gap-2">
                            @if(!$todo->is_done)
                            <form action="/todo/{{ $todo->id }}/done" method="POST">
                                @method('PATCH')
                                @csrf
                                <button title="Tandai selesai" class="text-green-400 hover:text-green-500 text-xl">✔</button>
                            </form>
                            @endif
                            <form action="/todo/{{ $todo->id }}" method="POST">
                                @method('DELETE')
                                @csrf
                                <button title="Hapus" class="text-red-400 hover:text-red-500 text-xl">🗑</button>
                            </form>
                            @if($todo->is_done)
                                <button @click="open = !open" class="ml-2 text-blue-400 hover:text-blue-600 text-xl" title="Lihat Evaluasi">
                                    ⬇
                                </button>
                            @endif
                        </div>
                    </div>

                    {{-- Dropdown evaluasi --}}
                    <div x-show="open" x-transition>
                        @if(!$todo->mood || !$todo->notes || !$todo->image)
                            <form action="{{ route('todo.evaluate', $todo->id) }}" method="POST" enctype="multipart/form-data" class="mt-2 flex flex-col gap-2 bg-yellow-100 p-3 rounded">
                                @csrf
                                <label class="font-semibold text-white">Bagaimana mood kamu?</label>
                                <input type="text" name="mood" class="rounded px-2 py-1" required>

                                <label class="font-semibold text-white">Catatan/kendala:</label>
                                <textarea name="notes" class="rounded px-2 py-1"></textarea>

                                <label class="font-semibold text-white">Upload gambar dokumentasi:</label>
                                <input type="file" name="image" accept="image/*" class="rounded text-white">

                                <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded mt-2">Simpan Evaluasi</button>
                            </form>
                        @else
                            <div class="mt-2 text-sm text-gray-800 bg-green-100 p-2 rounded">
                                <div><b>Mood:</b> {{ $todo->mood }}</div>
                                <div><b>Catatan:</b> {{ $todo->notes }}</div>
                                @if($todo->image)
                                    <div class="mt-1">
                                        <img src="{{ asset('storage/' . $todo->image) }}" alt="Dokumentasi" class="max-h-32 rounded">
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </li>
            @empty
                <p class="text-center text-gray-400 italic">Belum ada tugas. Yuk, tambahkan satu!</p>
            @endforelse
        </ul>
    </div>
</x-app-layout>
