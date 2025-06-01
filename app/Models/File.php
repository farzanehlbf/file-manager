<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'original_name', 'stored_name', 'mime_type', 'size'];

    public function folders()
    {
        return $this->belongsToMany(Folder::class);
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function shares()
    {
        return $this->hasMany(Share::class);
    }
}
