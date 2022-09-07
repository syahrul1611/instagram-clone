<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * menampilkan post dari user yang sudah di follow.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::friendPosts();
        return view('home', compact('posts'));
    }

    /**
     * menampilkan halaman upload post.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('upload');
    }

    /**
     * fungsi untuk mengupload postingan.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Post::newPost($request);
        return redirect(route('post.index'))->with('uploaded', 'Postingan dikirim');
    }

    /**
     * menampilkan detil dari sebuah post.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        if (Auth::user()->username !== $post->user_username) {
            if ($post->user->is_private) {
                exit;
            }
        }
        $comments = [];
        foreach ($post->comments->sortBy('created_at') as $comment) {
            $comments[] = [
                'user_username' => $comment->user_username,
                'user_name' => $comment->user_name,
                'user_image' => $comment->user_image,
                'comment' => $comment->comment,
                'time' => $comment->created_at->diffForHumans()
            ];
        }
        $post = [
            'user_username' => $post->user_username,
            'user_name' => $post->user_name,
            'user_image' => $post->user_image,
            'image' => $post->image,
            'caption' => $post->caption,
            'comments' => $comments,
            'likes' => $post->likes->count(),
            'like' => $post->likes->contains('user_username',Auth::user()->username),
            'edit' => (Auth::user()->username === $post->user_username) ? true : false
        ];
        return $post;
    }

    /**
     * mengupdate post.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $post->update(['caption' => $request->caption]);
        return back()->with('updated', 'Post berhasil diubah');
    }

    /**
     * menghapus post.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        Storage::delete('storage/post/'.$post->user_username.'/'.$post->image);
        $post->comments()->delete();
        $post->likes()->delete();
        $post->delete();
        return back()->with('deleted', 'Postingan dihapus');
    }
}
