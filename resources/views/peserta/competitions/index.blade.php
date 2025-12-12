@extends('layouts.app')

@section('content')
<div class="space-y-6">
  <!-- Hero Section -->
  <div class="bg-gradient-to-r from-cyan-400 via-blue-400 to-orange-400 rounded-3xl shadow-lg p-8 text-white">
    <h1 class="text-4xl font-bold mb-2">🌞 Daftar Lomba Musim Panas 🌊</h1>
    <p class="text-lg opacity-90">Pilih lomba favorit, download template, dan tampilkan bakat terbaik Anda!</p>
  </div>

  <!-- Grid Kompetisi -->
  <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
    @forelse($competitions as $c)
      <a href="{{ route('peserta.competitions.show', $c) }}"
         class="group bg-white rounded-2xl shadow-md ring-1 ring-cyan-200 hover:shadow-xl hover:ring-cyan-400 transition overflow-hidden transform hover:-translate-y-1">
        <!-- Poster Section -->
        <div class="h-48 bg-gradient-to-br from-cyan-300 to-blue-400 overflow-hidden relative">
          @if($c->poster_path)
            <img src="{{ Storage::url($c->poster_path) }}" class="w-full h-48 object-cover group-hover:scale-110 transition duration-300" alt="poster">
          @else
            <div class="w-full h-48 flex items-center justify-center text-white text-6xl">
              🎯
            </div>
          @endif
          <!-- Badge -->
          <div class="absolute top-3 right-3 bg-orange-400 text-white px-3 py-1 rounded-full text-sm font-semibold shadow-md">
            Buka
          </div>
        </div>

        <!-- Info Section -->
        <div class="p-5">
          <h2 class="font-bold text-lg text-blue-900 group-hover:text-orange-500 transition mb-2">
            {{ $c->title }}
          </h2>
          <p class="text-sm text-slate-700 line-clamp-2 mb-4">
            {{ $c->description ?? 'Klik untuk lihat detail lomba yang seru ini!' }}
          </p>
          
          <!-- Badge Status -->
          <div class="flex items-center justify-between">
            <span class="inline-flex items-center gap-1 text-sm font-semibold text-cyan-600">
              ✨ {{ $c->registrations->count() }} peserta
            </span>
            <div class="text-orange-500 font-bold group-hover:translate-x-2 transition">
              →
            </div>
          </div>
        </div>
      </a>
    @empty
      <div class="col-span-full text-center py-12">
        <p class="text-6xl mb-4">😎</p>
        <p class="text-xl text-slate-600">Belum ada lomba. Tunggu update musim panas berikutnya!</p>
      </div>
    @endforelse
  </div>

  <!-- Pagination -->
  <div class="flex justify-center">
    {{ $competitions->links() }}
