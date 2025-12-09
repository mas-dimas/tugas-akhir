<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Competition extends Model
{
    use HasFactory;

    // Kolom yang boleh diisi mass-assignment
    protected $fillable = [
        'title',
        'description',
        'stages',
        'poster_path',
        'guidebook_path',
        'source_link',
    ];

    // Relasi: satu lomba punya banyak pendaftaran
    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    // Relasi: satu lomba punya banyak template dokumen
    public function documentTemplates()
    {
        return $this->hasMany(DocumentTemplate::class);
    }
}
