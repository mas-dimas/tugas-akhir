<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Competition extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'description',
        'poster_path',
    ];

    /**
     * Get all registrations for this competition.
     */
    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }
}
