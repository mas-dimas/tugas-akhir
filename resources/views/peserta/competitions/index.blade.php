@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-sky-300 via-sky-100 to-amber-100">
  <div class="max-w-6xl mx-auto p-6">
    <div class="bg-white/70 backdrop-blur rounded-2xl shadow p-6">
      <div class="flex items-center justify-between gap-4">
        <div>
          <h1 class="text-2xl font-bold">Daftar Lomba 🏖️</h1>
          <p class="text-gray-700 mt-1">Pilih lomba, baca guidebook, dan unduh template dokumen.</p>
        </div>
        <a href="{{ route('peserta.dashboard') }}" class="text-sky-700 underline">Dashboard</a>
      </div>

      <div class="mt-6 grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse($competitions as $c)
          <a href="{{ route('peserta.competitions.show', $c) }}"
             class="group bg-white rounded-2xl shadow hover:shadow-lg transition overflow-hidden">
            <div class="h-40 bg-sky-50">
              @if($c->poster_path)
                <img src="{{ asset('storage/'.$c->poster_path) }}"
                     class="w-full h-40 object-cover" alt="poster">
              @else
                <div class="w-full h-40 flex items-center justify-center text-gray-500">
                  Tidak ada poster
                </div>
              @endif
            </div>
            <div class="p-4">
              <h2 class="font-bold text-lg group-hover:text-sky-700 transition">
                {{ $c->title }}
              </h2>
              <p class="text-sm text-gray-700 mt-1 line-clamp-2">
                {{ $c->description ?? 'Klik untuk lihat detail lomba.' }}
              </p>
              <div class="mt-3 text-sm text-sky-700 underline">
                Lihat detail →
              </div>
            </div>
          </a>
        @empty
          <div class="text-gray-700">Belum ada lomba.</div>
        @endforelse
      </div>

      <div class="mt-6">
        {{ $competitions->links() }}
      </div>
    </div>
  </div>
</div>
@endsection
