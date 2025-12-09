<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'competition_id',
        'type',        // 'proposal' / 'surat_rekomendasi'
        'file_path',
        'description',
    ];

    // Relasi: template milik satu lomba
    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }
}
