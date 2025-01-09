<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'user_id',
        'name'
    ];

    public function tasks()
    {
        return $this->hasMany(Task::class)->orderBy('priority', 'asc');
    }
}
