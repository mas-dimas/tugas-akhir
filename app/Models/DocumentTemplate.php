<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentTemplate extends Model
{
    protected $fillable = [
        'competition_id',
        'type',
        'title',
        'file_path',
    ];

    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }
}
