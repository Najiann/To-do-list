<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-white">
            To-Do List
        </h2>
    </x-slot>

    <div class="p-6 max-w-2xl mx-auto bg-gray-800 rounded-xl shadow-md space-y-6 text-white">
        <!-- Form tambah tugas -->
        <form action="/todo" method="POST" class="flex flex-col sm:flex-row items-stretch gap-3">
            @csrf
            <input 
                name="title" 
                placeholder="Tambahkan tugas baru..." 
                class="flex-1 px-4 py-2 rounded-lg bg-gray-700 text-white border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-400" 
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
                <li class="flex justify-between items-center bg-gray-700 p-4 rounded-lg shadow-sm border border-gray-600 {{ $todo->is_done ? 'opacity-60' : '' }}">
                    <div class="text-lg {{ $todo->is_done ? 'line-through text-gray-400' : 'text-white' }}">
                        {{ $todo->title }}
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
                    </div>
                </li>
            @empty
                <p class="text-center text-gray-400 italic">Belum ada tugas. Yuk, tambahkan satu!</p>
            @endforelse
        </ul>
    </div>
</x-app-layout>
