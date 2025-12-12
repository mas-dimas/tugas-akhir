@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-sky-300 via-sky-100 to-amber-100">
  <div class="max-w-6xl mx-auto p-6">
    <div class="bg-white/70 backdrop-blur rounded-2xl shadow p-6">
      <div class="flex items-start justify-between gap-4">
        <div>
          <h1 class="text-2xl font-bold">Upload Dokumen</h1>
          <p class="text-gray-700 mt-1">
            Lomba: <span class="font-semibold">{{ $registration->competition->title }}</span>
          </p>
        </div>
        <a href="{{ route('peserta.registrations.index') }}" class="text-sky-700 underline">← Pendaftaran Saya</a>
      </div>

      @if(session('success'))
        <div class="mt-4 p-3 rounded-xl bg-green-100 text-green-800">{{ session('success') }}</div>
      @endif

      <div class="mt-6 grid md:grid-cols-2 gap-6">

        {{-- Proposal --}}
        <div class="bg-white rounded-2xl shadow p-5">
          <h2 class="font-bold text-lg">Proposal</h2>

          @php $p = $docs->get('proposal'); @endphp
          <div class="mt-2 text-sm">
            <div>Status: <span class="font-semibold">{{ $p->status_review ?? 'belum_upload' }}</span></div>
            @if($p && $p->admin_note)
              <div class="mt-2 p-3 rounded-xl bg-amber-100 text-amber-900">
                <div class="font-semibold">Catatan Admin:</div>
                <div class="whitespace-pre-line">{{ $p->admin_note }}</div>
              </div>
            @endif
            @if($p)
              <a class="inline-block mt-2 text-sky-700 underline" target="_blank"
                 href="{{ asset('storage/'.$p->file_path) }}">Lihat Dokumen</a>
            @endif
          </div>

          <form class="mt-4" method="POST" enctype="multipart/form-data"
                action="{{ route('peserta.registrations.documents.upload', $registration) }}">
            @csrf
            <input type="hidden" name="type" value="proposal">
            <input type="file" name="file" class="w-full" required>
            <button class="mt-3 w-full px-4 py-2 rounded-xl bg-sky-600 text-white hover:bg-sky-700">
              Upload / Update Proposal
            </button>
          </form>
        </div>

        {{-- Rekomendasi --}}
        <div class="bg-white rounded-2xl shadow p-5">
          <h2 class="font-bold text-lg">Surat Rekomendasi</h2>

          @php $r = $docs->get('rekomendasi'); @endphp
          <div class="mt-2 text-sm">
            <div>Status: <span class="font-semibold">{{ $r->status_review ?? 'belum_upload' }}</span></div>
            @if($r && $r->admin_note)
              <div class="mt-2 p-3 rounded-xl bg-amber-100 text-amber-900">
                <div class="font-semibold">Catatan Admin:</div>
                <div class="whitespace-pre-line">{{ $r->admin_note }}</div>
              </div>
            @endif
            @if($r)
              <a class="inline-block mt-2 text-sky-700 underline" target="_blank"
                 href="{{ asset('storage/'.$r->file_path) }}">Lihat Dokumen</a>
            @endif
          </div>

          <form class="mt-4" method="POST" enctype="multipart/form-data"
                action="{{ route('peserta.registrations.documents.upload', $registration) }}">
            @csrf
            <input type="hidden" name="type" value="rekomendasi">
            <input type="file" name="file" class="w-full" required>
            <button class="mt-3 w-full px-4 py-2 rounded-xl bg-amber-500 text-white hover:bg-amber-600">
              Upload / Update Rekomendasi
            </button>
          </form>
        </div>

      </div>
    </div>
  </div>
</div>
@endsection
