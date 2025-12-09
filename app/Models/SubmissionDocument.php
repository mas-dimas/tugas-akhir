<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubmissionDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'registration_id',
        'type',     // 'proposal' / 'surat_rekomendasi'
        'file_path',
        'status',   // 'belum_dikoreksi', 'terdapat_koreksi', 'tidak_ada_koreksi'
        'notes',
    ];

    // Relasi: dokumen milik satu pendaftaran
    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }
}
