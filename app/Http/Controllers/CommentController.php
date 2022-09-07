<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * fungsi untuk comment pada sebuah post.
     */
    public function store(Post $post, Request $request)
    {
        $request['post'] = $post;
        return Post::comment($request);
    }
}