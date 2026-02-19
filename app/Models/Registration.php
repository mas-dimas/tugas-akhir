<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'competition_id',
        'status',
    ];

    /**
     * Get the user that owns this registration.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the competition this registration is for.
     */
    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }
}
