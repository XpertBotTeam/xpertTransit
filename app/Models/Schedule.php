<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;
    protected $fillable = ['day', 'start_time', 'end_time', 'is_attending'];
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
