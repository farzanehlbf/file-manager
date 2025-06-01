<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Share extends Model
{
    protected $fillable = ['file_id', 'token', 'expires_at'];

    public function file()
    {
        return $this->belongsTo(File::class);
    }
}
