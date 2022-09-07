<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use App\Models\User;

class FriendController extends Controller
{
    /**
     * kontroler untuk request pertemanan
     */
    public function request(User $user)
    {
        return Friend::addFriend($user);
    }

    /**
     * kontroler untuk menghapus pertemanan
     */
    public function delete(User $user)
    {
        return Friend::unfollow($user);
    }

    /**
     * kontroler untuk menerima pertemanan
     */
    public function accept(User $user)
    {
        return Friend::accept($user);
    }

    /**
     * kontroler untuk menolak pertemanan
     */
    public function reject(User $user)
    {
        return Friend::reject($user);
    }
}
