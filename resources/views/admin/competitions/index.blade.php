@extends('layouts.app')

@section('content')
<div class="space-y-6">
  <!-- Header Section -->
  <div class="flex items-center justify-between gap-4">
    <div>
      <h1 class="text-3xl font-bold text-slate-900">🏆 Kelola Lomba</h1>
      <p class="text-slate-600 mt-1">Tambah, edit, atau hapus lomba yang Anda kelola.</p>
    </div>
    <a href="{{ route('admin.competitions.create') }}"
       class="px-6 py-3 rounded-xl bg-gradient-to-r from-blue-500 to-cyan-500 text-white font-bold hover:shadow-lg transition">
      + Tambah Lomba Baru
    </a>
  </div>

  @if (session('success'))
    <div class="rounded-xl border-2 border-emerald-300 bg-emerald-50 text-emerald-900 px-4 py-3 flex items-center gap-2">
      <span class="text-xl">✅</span> {{ session('success') }}
    </div>
  @endif

  <!-- Table Section -->
  <div class="bg-gradient-to-br from-white via-cyan-50 to-blue-50 rounded-2xl shadow-md ring-1 ring-cyan-200 overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full">
        <thead class="bg-gradient-to-r from-cyan-50 to-blue-50 border-b border-cyan-200">
          <tr>
            <th class="p-4 text-left text-slate-900 font-bold">Poster</th>
            <th class="p-4 text-left text-slate-900 font-bold">Judul Lomba</th>
            <th class="p-4 text-left text-slate-900 font-bold">Sumber</th>
            <th class="p-4 text-left text-slate-900 font-bold">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-200">
          @forelse ($competitions as $c)
            <tr class="hover:bg-slate-50 transition">
              <td class="p-4">
                @if($c->poster_path)
                  <img class="w-20 h-20 object-cover rounded-lg ring-2 ring-cyan-200"
                       src="{{ Storage::url($c->poster_path) }}" alt="poster">
                @else
                  <div class="w-20 h-20 rounded-lg bg-slate-200 flex items-center justify-center text-2xl">
                    🎯
                  </div>
                @endif
              </td>
              <td class="p-4">
                <h3 class="font-bold text-slate-900">{{ $c->title }}</h3>
                <p class="text-sm text-slate-600 line-clamp-1">{{ $c->description ?? '-' }}</p>
              </td>
              <td class="p-4">
                @if($c->source_link)
                  <a class="text-blue-600 font-semibold hover:text-blue-800 underline" href="{{ $c->source_link }}" target="_blank">
                    🔗 Buka
                  </a>
                @else
                  <span class="text-slate-500">-</span>
                @endif
              </td>
              <td class="p-4">
                <div class="flex flex-wrap gap-2">
                  <a href="{{ route('admin.competitions.templates.index', $c) }}"
                     class="px-3 py-2 rounded-lg bg-purple-500 text-white text-sm font-semibold hover:bg-purple-600 transition">
                    📋 Template
                  </a>
                  <a href="{{ route('admin.competitions.edit', $c) }}"
                     class="px-3 py-2 rounded-lg bg-orange-500 text-white text-sm font-semibold hover:bg-orange-600 transition">
                    ✏️ Edit
                  </a>
                  <form action="{{ route('admin.competitions.destroy', $c) }}" method="POST"
                        onsubmit="return confirm('Yakin hapus lomba ini?')" class="inline">
                    @csrf
                    @method('DELETE')
                    <button class="px-3 py-2 rounded-lg bg-red-500 text-white text-sm font-semibold hover:bg-red-600 transition">
                      🗑️ Hapus
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td class="p-8 text-center text-slate-600 col-span-4" colspan="4">
                <p class="text-lg">😴 Belum ada lomba. <a href="{{ route('admin.competitions.create') }}" class="text-blue-600 underline">Buat sekarang</a></p>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <!-- Pagination -->
  <div class="flex justify-center">
    {{ $competitions->links() }}
  </div>
</div>
@endsection
