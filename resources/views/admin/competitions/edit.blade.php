@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-sky-300 via-sky-100 to-amber-100">
  <div class="max-w-3xl mx-auto p-6">
    <div class="bg-white/70 backdrop-blur rounded-2xl shadow p-6">
      <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold">Edit Lomba</h1>
        <a href="{{ route('admin.competitions.index') }}" class="text-sky-700 underline">Kembali</a>
      </div>

      @if ($errors->any())
        <div class="mt-4 p-3 rounded-xl bg-rose-100 text-rose-800">
          <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form class="mt-6 space-y-4" method="POST" action="{{ route('admin.competitions.update', $competition) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div>
          <label class="font-semibold">Judul</label>
          <input name="title" value="{{ old('title', $competition->title) }}" class="w-full mt-1 p-2 rounded-xl border" required>
        </div>

        <div>
          <label class="font-semibold">Deskripsi</label>
          <textarea name="description" class="w-full mt-1 p-2 rounded-xl border" rows="3">{{ old('description', $competition->description) }}</textarea>
        </div>

        <div>
          <label class="font-semibold">Tahapan Lomba</label>
          <textarea name="stages" class="w-full mt-1 p-2 rounded-xl border" rows="3">{{ old('stages', $competition->stages) }}</textarea>
        </div>

        <div>
          <label class="font-semibold">Link Sumber</label>
          <input name="source_link" value="{{ old('source_link', $competition->source_link) }}" class="w-full mt-1 p-2 rounded-xl border" placeholder="https://...">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="font-semibold">Poster Baru (opsional)</label>
            <input type="file" name="poster" class="w-full mt-1">
            @if($competition->poster_path)
              <img class="mt-2 w-full max-w-[220px] rounded-xl"
                   src="{{ asset('storage/'.$competition->poster_path) }}" alt="poster">
            @endif
          </div>

          <div>
            <label class="font-semibold">Guidebook Baru (opsional)</label>
            <input type="file" name="guidebook" class="w-full mt-1">
            @if($competition->guidebook_path)
              <a class="mt-2 inline-block text-sky-700 underline"
                 href="{{ asset('storage/'.$competition->guidebook_path) }}" target="_blank">
                Lihat Guidebook Saat Ini
              </a>
            @endif
          </div>
        </div>

        <button class="w-full px-4 py-2 rounded-xl bg-amber-500 text-white hover:bg-amber-600">
          Update
        </button>
      </form>
    </div>
  </div>
</div>
@endsection

