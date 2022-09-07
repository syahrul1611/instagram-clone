<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    /**
     * atribut yang bisa diisi.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'post_id',
        'user_name',
        'user_username',
        'user_image'
    ];

    /**
     * relasi like dengan post.
     */
    public function post()
    {
        return $this->belongsTo(\App\Models\Post::class);
    }
}
