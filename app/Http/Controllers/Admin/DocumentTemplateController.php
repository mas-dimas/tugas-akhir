<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\DocumentTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentTemplateController extends Controller
{
    public function index(Competition $competition)
    {
        $templates = $competition->documentTemplates
            ->keyBy('type'); // akses cepat: $templates['proposal'] dll

        return view('admin.templates.index', compact('competition', 'templates'));
    }

    public function store(Request $request, Competition $competition)
    {
        $data = $request->validate([
            'type'  => 'required|in:proposal,rekomendasi',
            'title' => 'nullable|string|max:255',
            'file'  => 'required|mimes:pdf,doc,docx|max:5120',
        ]);

        // kalau sudah ada template type ini, replace file lama
        $existing = DocumentTemplate::where('competition_id', $competition->id)
            ->where('type', $data['type'])
            ->first();

        if ($existing) {
            Storage::disk('public')->delete($existing->file_path);
        }

        $path = $request->file('file')->store("templates/{$competition->id}", 'public');

        $template = DocumentTemplate::updateOrCreate(
            ['competition_id' => $competition->id, 'type' => $data['type']],
            ['title' => $data['title'] ?? null, 'file_path' => $path]
        );

        return redirect()
            ->route('admin.competitions.templates.index', $competition)
            ->with('success', "Template {$template->type} berhasil diupload/diupdate.");
    }

    public function destroy(DocumentTemplate $template)
    {
        Storage::disk('public')->delete($template->file_path);
        $competitionId = $template->competition_id;

        $template->delete();

        return redirect()
            ->route('admin.competitions.templates.index', $competitionId)
            ->with('success', "Template berhasil dihapus.");
    }
}
