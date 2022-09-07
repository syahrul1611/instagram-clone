<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Post extends Model
{
    use HasFactory;

    /**
     * atribut yang bisa diisi.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'slug',
        'user_id',
        'user_name',
        'user_username',
        'user_image',
        'image',
        'caption',
    ];

    /**
    * kunci rute untuk model.
    *
    * @return string
    */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * relasi post dengan user.
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    /**
     * relasi post dengan like.
     */
    public function likes()
    {
        return $this->hasMany(\App\Models\Like::class);
    }

    /**
     * relasi post dengan comment.
     */
    public function comments()
    {
        return $this->hasMany(\App\Models\Comment::class);
    }

    /**
     * fungsi untuk membuat post baru.
     */
    public static function newPost($request)
    {
        $user = Auth::user();
        $validatedData = $request->validate([
            'image' => 'required|file|image|mimes:png,jpg,jpeg|max:5120',
            'caption' => 'required'
        ]);
        $validatedData['slug'] = $user->username.date('d-m-y-H:m:s');
        $validatedData['user_id'] = $user->id;
        $validatedData['user_name'] = $user->name;
        $validatedData['user_username'] = $user->username;
        $validatedData['user_image'] = $user->image;
        $extension = $request->file('image')->extension();
        $fileName = date('d.m.y_H-m-s').'.'.$extension;
        $request->file('image')->storeAs('storage/post/'.$user->username, $fileName);
        $validatedData['image'] = $fileName;
        Post::create($validatedData);
    }

    /**
     * fungsi untuk query post yang public.
     */
    public static function publicPosts()
    {
        $users = User::where('is_private', false)->get();
        $posts = [];
        foreach ($users as $user) {
            foreach ($user->posts->sortByDesc('created_at') as $post) {
                $posts[] = $post;
            }
        }
        return $posts;
    }
    
    /**
     * fungsi untuk query post yang ada di friend.
     */
    public static function friendPosts()
    {
        $users = Friend::where('user_id_1',Auth::user()->id)->where('status',true)->get('user_id_2')->toArray();
        for ($i=0; $i < count($users); $i++) { 
            $users[$i] = $users[$i]['user_id_2'];
        }
        $users[count($users)+1] = Auth::user()->id;
        $posts = Post::with('likes')->whereIn('user_id',$users)->get();
        return $posts;
    }

    /**
     * fungsi untuk store like ke post yang bersangkutan.
     */
    public static function like($post)
    {
        $user = Auth::user();
        $like = collect(Like::where('post_id', $post->id)->get());
        if ($like->contains('user_username', null, $user->username)) {
            Like::where('post_id', $post->id)->where('user_username', $user->username)->delete();
            return [$post->likes->count(), false];
            exit;
        }
        Like::create([
            'post_id' => $post->id,
            'user_username' => $user->username,
            'user_name' => $user->name,
            'user_image' => $user->image
        ]);
        return [$post->likes->count(), true];
    }

    /**
     * fungsi untuk comment pada post.
     */
    public static function comment($request)
    {
        $user = Auth::user();
        $data = [
            'post_id' => $request->post->id,
            'user_username' => $user->username,
            'user_name' => $user->name,
            'user_image' => $user->image,
            'comment' => $request->comment
        ];
        $data['time'] = '1 detik yang lalu';
        Comment::create($data);
        return $data;
    }
}
