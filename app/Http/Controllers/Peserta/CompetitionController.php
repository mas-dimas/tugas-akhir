<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\Competition;

class CompetitionController extends Controller
{
    public function index()
    {
        $competitions = Competition::latest()->paginate(9);
        return view('peserta.competitions.index', compact('competitions'));
    }

    public function show(Competition $competition)
    {
        $templates = $competition->documentTemplates()->get()->keyBy('type');
        $isRegistered = \App\Models\Registration::where('user_id', auth()->id())
            ->where('competition_id', $competition->id)
            ->exists();
        return view('peserta.competitions.show', compact('competition', 'templates', 'isRegistered'));
    }
}
