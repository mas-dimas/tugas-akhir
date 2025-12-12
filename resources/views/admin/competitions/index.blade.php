@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-sky-300 via-sky-100 to-amber-100">
  <div class="max-w-6xl mx-auto p-6">
    <div class="bg-white/70 backdrop-blur rounded-2xl shadow p-6">
      <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold">Admin - Daftar Lomba</h1>
        <a href="{{ route('admin.competitions.create') }}"
           class="px-4 py-2 rounded-xl bg-sky-600 text-white hover:bg-sky-700">
          + Tambah Lomba
        </a>
      </div>

      @if (session('success'))
        <div class="mt-4 p-3 rounded-xl bg-green-100 text-green-800">
          {{ session('success') }}
        </div>
      @endif

      <div class="mt-6 overflow-x-auto">
        <table class="w-full border rounded-xl overflow-hidden">
          <thead class="bg-sky-50">
            <tr>
              <th class="p-3 text-left">Poster</th>
              <th class="p-3 text-left">Judul</th>
              <th class="p-3 text-left">Link</th>
              <th class="p-3 text-left">Aksi</th>
            </tr>
          </thead>
          <tbody class="bg-white/60">
            @forelse ($competitions as $c)
              <tr class="border-t">
                <td class="p-3">
                  @if($c->poster_path)
                    <img class="w-20 h-20 object-cover rounded-xl"
                         src="{{ asset('storage/'.$c->poster_path) }}" alt="poster">
                  @else
                    <div class="w-20 h-20 rounded-xl bg-gray-200 flex items-center justify-center text-xs text-gray-600">
                      No Poster
                    </div>
                  @endif
                </td>
                <td class="p-3 font-semibold">{{ $c->title }}</td>
                <td class="p-3">
                  @if($c->source_link)
                    <a class="text-sky-700 underline" href="{{ $c->source_link }}" target="_blank">Sumber</a>
                  @else
                    <span class="text-gray-500 text-sm">-</span>
                  @endif
                </td>
                <td class="p-3">
                  <div class="flex gap-2">
		    <a href="{{ route('admin.competitions.templates.index', $c) }}"
		       class="px-3 py-2 rounded-xl bg-sky-500 text-white hover:bg-sky-600">
     		      Template
   		    </a>

                    <a href="{{ route('admin.competitions.edit', $c) }}"
                       class="px-3 py-2 rounded-xl bg-amber-500 text-white hover:bg-amber-600">
                      Edit
                    </a>

                    <form action="{{ route('admin.competitions.destroy', $c) }}" method="POST"
                          onsubmit="return confirm('Yakin hapus lomba ini?')">
                      @csrf
                      @method('DELETE')
                      <button class="px-3 py-2 rounded-xl bg-rose-600 text-white hover:bg-rose-700">
                        Hapus
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td class="p-4 text-center text-gray-600" colspan="4">Belum ada lomba.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <div class="mt-4">
        {{ $competitions->links() }}
      </div>
    </div>
  </div>
</div>
@endsection
