<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Friend extends Model
{
    use HasFactory;

    /**
     * atribut yang bisa diisi.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id_1',
        'user_id_2',
        'status'
    ];

    /**
     * relasi friend dengan user.
     */
    public function user_1()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id_1');
    }

    /**
     * relasi friend dengan user.
     */
    public function user_2()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id_2');
    }

    /**
     * fungsi untuk menambah teman.
     */
    public static function addFriend($user)
    {
        $status = ($user->is_private) ? false : true;
        Friend::create([
            'user_id_1' => Auth::user()->id,
            'user_id_2' => $user->id,
            'status' => $status
        ]);
        if ($status) {
            return back()->with('followed', 'Anda mengikuti '.$user->name);
        } else {
            return back()->with('requested', 'Permintaan dikirim');
        }
    }

    /**
     * fungsi untuk menerima pertemanan.
     */
    public static function accept($user)
    {
        Friend::where('user_id_2', Auth::user()->id)->where('user_id_1', $user->id)->update(['status'=>true]);
        return back()->with('accepted', 'Anda menerima pertemanan');
    }

    /**
     * fungsi untuk menolak pertemanan.
     */
    public static function reject($user)
    {
        Friend::where('user_id_2', Auth::user()->id)->where('user_id_1', $user->id)->delete();
        return back()->with('rejected', 'Anda menolak pertemanan');
    }

    /**
     * fungsi untuk menghapus pertemanan.
     */
    public static function unfollow($user)
    {
        Friend::where('user_id_1', Auth::user()->id)->where('user_id_2', $user->id)->delete();
        return back()->with('unfollowed', 'Anda berhenti mengikuti '.$user->name);
    }
}
