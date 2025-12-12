@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-sky-300 via-sky-100 to-amber-100">
  <div class="max-w-5xl mx-auto p-6">
    <div class="bg-white/70 backdrop-blur rounded-2xl shadow p-6">
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-bold">Template Dokumen</h1>
          <p class="text-gray-700 mt-1">
            Lomba: <span class="font-semibold">{{ $competition->title }}</span>
          </p>
        </div>

        <a href="{{ route('admin.competitions.index') }}" class="text-sky-700 underline">
          Kembali
        </a>
      </div>

      @if (session('success'))
        <div class="mt-4 p-3 rounded-xl bg-green-100 text-green-800">
          {{ session('success') }}
        </div>
      @endif

      <div class="mt-6 grid md:grid-cols-2 gap-6">

        {{-- Card: Proposal --}}
        <div class="bg-white rounded-2xl shadow p-5">
          <h2 class="text-lg font-bold">Template Proposal</h2>

          @php $p = $templates->get('proposal'); @endphp
          @if($p)
            <p class="mt-2 text-sm text-gray-700">
              Saat ini: <span class="font-semibold">{{ $p->title ?? 'Template Proposal' }}</span>
            </p>
            <a class="mt-2 inline-block text-sky-700 underline" target="_blank"
               href="{{ asset('storage/'.$p->file_path) }}">
              Download / Lihat
            </a>

            <form class="mt-3" method="POST" action="{{ route('admin.templates.destroy', $p) }}"
                  onsubmit="return confirm('Hapus template proposal ini?')">
              @csrf
              @method('DELETE')
              <button class="px-3 py-2 rounded-xl bg-rose-600 text-white hover:bg-rose-700">
                Hapus Template
              </button>
            </form>
          @else
            <p class="mt-2 text-sm text-gray-600">Belum ada template proposal.</p>
          @endif

          <form class="mt-4 space-y-3" method="POST"
                action="{{ route('admin.competitions.templates.store', $competition) }}"
                enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="type" value="proposal">

            <div>
              <label class="font-semibold">Judul (opsional)</label>
              <input name="title" class="w-full mt-1 p-2 rounded-xl border" placeholder="Template Proposal 2025">
            </div>

            <div>
              <label class="font-semibold">File (PDF/DOC/DOCX)</label>
              <input type="file" name="file" class="w-full mt-1" required>
            </div>

            <button class="w-full px-4 py-2 rounded-xl bg-sky-600 text-white hover:bg-sky-700">
              Upload / Update Proposal
            </button>
          </form>
        </div>

        {{-- Card: Rekomendasi --}}
        <div class="bg-white rounded-2xl shadow p-5">
          <h2 class="text-lg font-bold">Template Surat Rekomendasi</h2>

          @php $r = $templates->get('rekomendasi'); @endphp
          @if($r)
            <p class="mt-2 text-sm text-gray-700">
              Saat ini: <span class="font-semibold">{{ $r->title ?? 'Template Rekomendasi' }}</span>
            </p>
            <a class="mt-2 inline-block text-sky-700 underline" target="_blank"
               href="{{ asset('storage/'.$r->file_path) }}">
              Download / Lihat
            </a>

            <form class="mt-3" method="POST" action="{{ route('admin.templates.destroy', $r) }}"
                  onsubmit="return confirm('Hapus template rekomendasi ini?')">
              @csrf
              @method('DELETE')
              <button class="px-3 py-2 rounded-xl bg-rose-600 text-white hover:bg-rose-700">
                Hapus Template
              </button>
            </form>
          @else
            <p class="mt-2 text-sm text-gray-600">Belum ada template rekomendasi.</p>
          @endif

          <form class="mt-4 space-y-3" method="POST"
                action="{{ route('admin.competitions.templates.store', $competition) }}"
                enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="type" value="rekomendasi">

            <div>
              <label class="font-semibold">Judul (opsional)</label>
              <input name="title" class="w-full mt-1 p-2 rounded-xl border" placeholder="Template Surat Rekomendasi">
            </div>

            <div>
              <label class="font-semibold">File (PDF/DOC/DOCX)</label>
              <input type="file" name="file" class="w-full mt-1" required>
            </div>

            <button class="w-full px-4 py-2 rounded-xl bg-amber-500 text-white hover:bg-amber-600">
              Upload / Update Rekomendasi
            </button>
          </form>
        </div>

      </div>
    </div>
  </div>
</div>
@endsection
