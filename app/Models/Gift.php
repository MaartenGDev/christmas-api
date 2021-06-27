<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gift extends Model
{
    protected $fillable = ['title','description', 'url', 'reserved_by', 'image'];

    protected $casts = [
      'user_id' => 'integer'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
