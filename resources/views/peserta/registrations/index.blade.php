@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-sky-300 via-sky-100 to-amber-100">
  <div class="max-w-6xl mx-auto p-6">
    <div class="bg-white/70 backdrop-blur rounded-2xl shadow p-6">
      <div class="flex items-center justify-between gap-4">
        <div>
          <h1 class="text-2xl font-bold">Pendaftaran Saya 🧾</h1>
          <p class="text-gray-700 mt-1">Daftar lomba yang sudah kamu ikuti.</p>
        </div>
        <a href="{{ route('peserta.competitions.index') }}" class="text-sky-700 underline">
          + Cari Lomba
        </a>
      </div>

      @if(session('success'))
        <div class="mt-4 p-3 rounded-xl bg-green-100 text-green-800">
          {{ session('success') }}
        </div>
      @endif

      <div class="mt-6 grid md:grid-cols-2 gap-5">
        @forelse($registrations as $r)
          <div class="bg-white rounded-2xl shadow p-5">
            <div class="flex items-start justify-between gap-4">
              <div>
                <div class="font-bold text-lg">{{ $r->competition->title }}</div>
                <div class="text-sm text-gray-700 mt-1">
                  Status: <span class="font-semibold">{{ $r->status }}</span>
                </div>
              </div>
              <a href="{{ route('peserta.competitions.show', $r->competition) }}"
                 class="text-sky-700 underline">
                Detail →
              </a>
            </div>

            <div class="mt-4 text-sm text-gray-700">
              Terdaftar pada: {{ $r->created_at->format('d M Y H:i') }}
            </div>

            {{-- Nanti: tombol upload dokumen akan ada di halaman detail registration --}}
            <button disabled class="mt-4 w-full px-4 py-2 rounded-xl bg-gray-300 text-gray-600 cursor-not-allowed">
              Upload Dokumen (next step)
            </button>
          </div>
        @empty
          <div class="text-gray-700">Kamu belum mendaftar lomba.</div>
        @endforelse
      </div>
    </div>
  </div>
</div>
@endsection
