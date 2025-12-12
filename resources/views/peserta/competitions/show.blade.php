@extends('layouts.app')

@section('content')
<div class="space-y-6">
  <!-- Back Link -->
  <a href="{{ route('peserta.competitions.index') }}" class="inline-flex items-center gap-2 text-blue-600 hover:text-orange-500 font-semibold">
    ← Kembali ke Daftar Lomba
  </a>

  <div class="grid lg:grid-cols-3 gap-6">
    <!-- Sidebar (Poster + Files) -->
    <div class="space-y-4">
      <!-- Poster Card -->
      <div class="bg-white rounded-2xl shadow-md ring-1 ring-cyan-200 overflow-hidden">
        <div class="h-72 bg-gradient-to-br from-orange-300 to-yellow-400 flex items-center justify-center text-white text-8xl">
          @if($competition->poster_path)
            <img src="{{ Storage::url($competition->poster_path) }}" class="w-full h-72 object-cover" alt="poster">
          @else
            🏆
          @endif
        </div>
      </div>

      <!-- File Downloads Card -->
      <div class="bg-white rounded-2xl shadow-md ring-1 ring-cyan-200 p-5 space-y-3">
        <h3 class="font-bold text-lg text-blue-900">📥 Berkas Penting</h3>

        @if($competition->guidebook_path)
          <a href="{{ Storage::url($competition->guidebook_path) }}" target="_blank"
             class="flex items-center justify-between p-3 bg-gradient-to-r from-blue-400 to-cyan-400 text-white rounded-xl hover:shadow-md transition">
            <span class="font-semibold">📖 Guidebook</span>
            <span>↓</span>
          </a>
        @else
          <div class="p-3 bg-slate-100 text-slate-500 rounded-xl">📖 Guidebook belum ada</div>
        @endif

        @if($templates->get('proposal'))
          <a href="{{ Storage::url($templates->get('proposal')->file_path) }}" target="_blank"
             class="flex items-center justify-between p-3 bg-gradient-to-r from-orange-400 to-orange-500 text-white rounded-xl hover:shadow-md transition">
            <span class="font-semibold">📝 Template Proposal</span>
            <span>↓</span>
          </a>
        @else
          <div class="p-3 bg-slate-100 text-slate-500 rounded-xl">📝 Template belum ada</div>
        @endif

        @if($templates->get('rekomendasi'))
          <a href="{{ Storage::url($templates->get('rekomendasi')->file_path) }}" target="_blank"
             class="flex items-center justify-between p-3 bg-gradient-to-r from-emerald-400 to-teal-400 text-white rounded-xl hover:shadow-md transition">
            <span class="font-semibold">✍️ Template Rekomendasi</span>
            <span>↓</span>
          </a>
        @else
          <div class="p-3 bg-slate-100 text-slate-500 rounded-xl">✍️ Template belum ada</div>
        @endif

        @if($competition->source_link)
          <a href="{{ $competition->source_link }}" target="_blank"
             class="flex items-center justify-between p-3 bg-gradient-to-r from-yellow-400 to-yellow-500 text-white rounded-xl hover:shadow-md transition font-semibold">
            🔗 Link Sumber Resmi
            <span class="text-lg">→</span>
          </a>
        @endif
      </div>
    </div>

    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-5">
      <!-- Title & Status -->
      <div class="bg-white rounded-2xl shadow-md ring-1 ring-cyan-200 p-6">
        <h1 class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-orange-500 bg-clip-text text-transparent mb-3">
          {{ $competition->title }}
        </h1>
        
        @if($isRegistered)
          <div class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-100 text-emerald-900 rounded-full font-semibold ring-2 ring-emerald-300">
            ✅ Kamu sudah terdaftar
          </div>
          <a href="{{ route('peserta.registrations.show', $registration ?? '') }}" class="ml-2 inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-500 to-cyan-500 text-white rounded-full font-semibold hover:shadow-lg transition">
            Lihat Pendaftaran Saya →
          </a>
        @else
          <form method="POST" action="{{ route('peserta.competitions.register', $competition) }}" class="inline">
            @csrf
            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-orange-400 to-orange-500 text-white font-bold rounded-xl hover:shadow-lg transition">
              🎯 Daftar Sekarang
            </button>
          </form>
        @endif
      </div>

      <!-- Description -->
      <div class="bg-white rounded-2xl shadow-md ring-1 ring-cyan-200 p-6">
        <h2 class="text-2xl font-bold text-blue-900 mb-3">📋 Deskripsi</h2>
        <p class="text-slate-900 whitespace-pre-line leading-relaxed">
          {{ $competition->description ?? 'Tidak ada deskripsi tersedia.' }}
        </p>
      </div>

      <!-- Stages -->
      <div class="bg-white rounded-2xl shadow-md ring-1 ring-cyan-200 p-6">
        <h2 class="text-2xl font-bold text-blue-900 mb-3">🎪 Tahapan Lomba</h2>
        <p class="text-slate-900 whitespace-pre-line leading-relaxed">
          {{ $competition->stages ?? 'Tidak ada informasi tahapan.' }}
        </p>
      </div>
    </div>
  </div>
</div>
@endsection
