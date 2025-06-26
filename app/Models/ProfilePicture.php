<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfilePicture extends Model
{
    protected $fillable = [
        'filename',
        'original_name',
        'mime_type',
        'file_size'
    ];

    public function userable()
    {
        return $this->morphTo();
    }
}