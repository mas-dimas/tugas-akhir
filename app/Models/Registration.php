<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Competition;
use App\Models\SubmissionDocument;

class Registration extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'competition_id',
        'status',
    ];

    // Relasi: pendaftaran milik satu user (peserta)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: pendaftaran untuk satu lomba
    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }

    // Relasi: pendaftaran punya banyak dokumen yang di-upload
    public function submissionDocuments()
    {
        return $this->hasMany(SubmissionDocument::class);
    }
}
