<?php

namespace App\Http\Controllers;

use App\Models\Post;

class LikeController extends Controller
{
    /**
     * fungsi untuk like sebuah post.
     */
    public function store(Post $post)
    {
        return Post::like($post);
    }
}
