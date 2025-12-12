@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-sky-300 via-sky-100 to-amber-100">
  <div class="max-w-6xl mx-auto p-6">
    <div class="bg-white/70 backdrop-blur rounded-2xl shadow p-6">
      <div class="flex items-start justify-between gap-4">
        <div>
          <h1 class="text-2xl font-bold">{{ $competition->title }}</h1>
          <p class="text-gray-700 mt-1">Detail lomba & berkas penting.</p>
        </div>
        <a href="{{ route('peserta.competitions.index') }}" class="text-sky-700 underline">← Kembali</a>
      </div>

      <div class="mt-6 grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1">
          <div class="bg-white rounded-2xl shadow overflow-hidden">
            @if($competition->poster_path)
              <img src="{{ asset('storage/'.$competition->poster_path) }}"
                   class="w-full h-72 object-cover" alt="poster">
            @else
              <div class="w-full h-72 flex items-center justify-center text-gray-500 bg-sky-50">
                Poster belum tersedia
              </div>
            @endif
          </div>

          <div class="mt-4 bg-white rounded-2xl shadow p-4 space-y-3">
            <h2 class="font-bold">Berkas</h2>

            <div class="flex items-center justify-between">
              <span class="text-gray-700">Guidebook</span>
              @if($competition->guidebook_path)
                <a class="text-sky-700 underline" target="_blank"
                   href="{{ asset('storage/'.$competition->guidebook_path) }}">
                  Download
                </a>
              @else
                <span class="text-gray-500 text-sm">Belum ada</span>
              @endif
            </div>

            <div class="flex items-center justify-between">
              <span class="text-gray-700">Template Proposal</span>
              @if($templates->get('proposal'))
                <a class="text-sky-700 underline" target="_blank"
                   href="{{ asset('storage/'.$templates->get('proposal')->file_path) }}">
                  Download
                </a>
              @else
                <span class="text-gray-500 text-sm">Belum ada</span>
              @endif
            </div>

            <div class="flex items-center justify-between">
              <span class="text-gray-700">Template Rekomendasi</span>
              @if($templates->get('rekomendasi'))
                <a class="text-sky-700 underline" target="_blank"
                   href="{{ asset('storage/'.$templates->get('rekomendasi')->file_path) }}">
                  Download
                </a>
              @else
                <span class="text-gray-500 text-sm">Belum ada</span>
              @endif
            </div>

            @if($competition->source_link)
              <div class="pt-2 border-t">
                <a class="text-sky-700 underline" href="{{ $competition->source_link }}" target="_blank">
                  Link Sumber Resmi →
                </a>
              </div>
            @endif
          </div>
        </div>

        <div class="lg:col-span-2 space-y-6">
          <div class="bg-white rounded-2xl shadow p-5">
            <h2 class="font-bold text-lg">Deskripsi</h2>
            <p class="text-gray-800 mt-2 whitespace-pre-line">
              {{ $competition->description ?? '-' }}
            </p>
          </div>

          <div class="bg-white rounded-2xl shadow p-5">
            <h2 class="font-bold text-lg">Tahapan Lomba</h2>
            <p class="text-gray-800 mt-2 whitespace-pre-line">
              {{ $competition->stages ?? '-' }}
            </p>
          </div>

          {{-- Tombol daftar (nanti kita aktifkan setelah buat registrations) --}}
          <div class="bg-white rounded-2xl shadow p-5">
            <h2 class="font-bold text-lg">Pendaftaran</h2>
            <p class="text-gray-700 mt-2">
              Tombol daftar akan aktif setelah fitur pendaftaran (registrations) dibuat.
            </p>
	@if($isRegistered)
 	 <div class="mt-3 p-3 rounded-xl bg-green-100 text-green-800">
   	 Kamu sudah terdaftar di lomba ini ✅
 	 </div>

 	 <a href="{{ route('peserta.registrations.index') }}"
    	 class="inline-block mt-3 px-4 py-2 rounded-xl bg-amber-500 text-white hover:bg-amber-600">
   	 Lihat Pendaftaran Saya
 	 </a>
	@else
 	 @if(session('error'))
   	 <div class="mt-3 p-3 rounded-xl bg-rose-100 text-rose-800">
     	 {{ session('error') }}
   	 </div>
 	 @endif

 	 <form method="POST" action="{{ route('peserta.competitions.register', $competition) }}">
   	 @csrf
   	 <button class="mt-3 px-4 py-2 rounded-xl bg-sky-600 text-white hover:bg-sky-700">
     	 Daftar Lomba
	    </button>
	 	 </form>
		@endif

          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
