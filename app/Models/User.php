<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Te7aHoudini\LaravelTrix\Traits\HasTrixRichText;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasTrixRichText;

    /**
     * atribut yang bisa diisi.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'image',
        'bio',
        'is_private',
        'email',
        'password',
    ];

    /**
     * atribut yang disembunyikan.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * atribut yang harus dilemparkan.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
    * kunci rute untuk model.
    *
    * @return string
    */
    public function getRouteKeyName()
    {
        return 'username';
    }

    /**
     * relasi user dengan friend.
     */
    public function followers()
    {
        return $this->hasMany(\App\Models\Friend::class, 'user_id_2');
    }

    /**
     * relasi user dengan friend.
     */
    public function followings()
    {
        return $this->hasMany(\App\Models\Friend::class, 'user_id_1');
    }

    /**
     * relasi user dengan post.
     */
    public function posts()
    {
        return $this->hasMany(\App\Models\Post::class);
    }

    /**
     * fungsi search user.
     */
    public static function searchUser($keyword)
    {
        if ($keyword) {
            $friendsList = \App\Models\Friend::where('user_id_1',Auth::user()->id)->get('user_id_2')->toArray();
            for ($i=0; $i < count($friendsList); $i++) { 
                $data[$i] = $friendsList[$i]['user_id_2'];
            }
            $data[count($friendsList)+1] = Auth::user()->id;
            $users = User::where('name','like','%'.$keyword.'%')->where('username','like','%'.$keyword.'%')->get()->except($data);
        } else { $users = null; }
        return $users;
    }

    /**
     * fungsi update user.
     * @var $request
     */
    public static function updateProfile($request)
    {
        $rules = [
            'name' => 'max:36|min:3',
            'image' => 'image|mimes:jpg,jpeg,png|max:5120'
        ];
        if ($request->username === Auth::user()->username) {
            $rules['username'] = 'max:18|min:6';
        } else {
            $rules['username'] = 'max:18|min:6|unique:users';
        }
        $validatedData = $request->validate($rules);
        if($request->hasFile('image')) {
            if($request->oldImage !== 'default.png') {
                Storage::delete('storage/profile/'.$request->oldImage);
            }
            $extension = $request->file('image')->extension();
            $request->file('image')->storeAs('storage/profile', $request->username.'.'.$extension);
            $validatedData['image'] = $request->username.'.'.$extension;
        }
        if ($request->is_private === 'on') {
            $validatedData['is_private'] = true;
        } else {
            $validatedData['is_private'] = false;
        }
        $validatedData['bio'] = $request->bio;
        User::where('id',Auth::user()->id)->update($validatedData);
    }
}
