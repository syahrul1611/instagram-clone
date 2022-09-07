<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Halaman untuk mencari user lain
     * 
     * @var \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $users = User::searchUser($request->keyword);
        $posts = Post::publicPosts();
        return view('search', compact('users','posts'));
    }

    /**
     * Halaman untuk dashboard profile
     * 
     * @var \App\Models\User
     */
    public function get_profile(User $user)
    {
        return view('dashboard', compact('user'));
    }

    /**
     * Fungsi untuk update user profile
     * 
     * @var \Illuminate\Http\Request
     */
    public function update_profile(Request $request)
    {
        User::updateProfile($request);
        return back()->with('edited', 'Akun berhasil disimpan');
    }
}
