<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CompetitionController extends Controller
{
    public function index()
    {
        $competitions = Competition::latest()->paginate(10);

        return view('admin.competitions.index', compact('competitions'));
    }

    public function create()
    {
        return view('admin.competitions.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'nullable|string',
            'stages'        => 'nullable|string',
            'source_link'   => 'nullable|url',
            'poster'        => 'nullable|image|max:2048',
            'guidebook'     => 'nullable|mimes:pdf,doc,docx|max:5120',
        ]);

        $competition = new Competition($data);

        if ($request->hasFile('poster')) {
            $competition->poster_path = $request->file('poster')->store('posters', 'public');
        }

        if ($request->hasFile('guidebook')) {
            $competition->guidebook_path = $request->file('guidebook')->store('guidebooks', 'public');
        }

        $competition->save();

        return redirect()->route('admin.competitions.index')
            ->with('success', 'Lomba berhasil dibuat.');
    }

    public function edit(Competition $competition)
    {
        return view('admin.competitions.edit', compact('competition'));
    }

    public function update(Request $request, Competition $competition)
    {
        $data = $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'nullable|string',
            'stages'        => 'nullable|string',
            'source_link'   => 'nullable|url',
            'poster'        => 'nullable|image|max:2048',
            'guidebook'     => 'nullable|mimes:pdf,doc,docx|max:5120',
        ]);

        $competition->fill($data);

        if ($request->hasFile('poster')) {
            if ($competition->poster_path) {
                Storage::disk('public')->delete($competition->poster_path);
            }
            $competition->poster_path = $request->file('poster')->store('posters', 'public');
        }

        if ($request->hasFile('guidebook')) {
            if ($competition->guidebook_path) {
                Storage::disk('public')->delete($competition->guidebook_path);
            }
            $competition->guidebook_path = $request->file('guidebook')->store('guidebooks', 'public');
        }

        $competition->save();

        return redirect()->route('admin.competitions.index')
            ->with('success', 'Lomba berhasil diperbarui.');
    }

    public function destroy(Competition $competition)
    {
        if ($competition->poster_path) {
            Storage::disk('public')->delete($competition->poster_path);
        }
        if ($competition->guidebook_path) {
            Storage::disk('public')->delete($competition->guidebook_path);
        }

        $competition->delete();

        return redirect()->route('admin.competitions.index')
            ->with('success', 'Lomba berhasil dihapus.');
    }
}
