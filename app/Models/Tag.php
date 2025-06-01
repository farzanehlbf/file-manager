<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['name', 'color'];

    public function files()
    {
        return $this->morphedByMany(File::class, 'taggable');
    }

    public function folders()
    {
        return $this->morphedByMany(Folder::class, 'taggable');
    }
}
