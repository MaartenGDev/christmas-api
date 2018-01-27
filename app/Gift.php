<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gift extends Model
{
    protected $fillable = ['title','description', 'url', 'reserved_by', 'image'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
