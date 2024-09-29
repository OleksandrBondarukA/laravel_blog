<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    const IS_BANNED = 0;
    const IS_ACTIVE = 1;
    const IS_ADMIN = 1;
    const IS_USER = 0;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function add($fields)
    {
        $user = new static;
        $user->fillable($fields);
        $user->password = bcrypt($fields['password']);
        $user->save();
    }

    public function edit($fields)
    {
        $this->fillable($fields);
        $this->password = bcrypt($fields['password']);
        $this->save();
    }

    public function generatePassword($password)
    {
        if ($password != null) {
            $this->password = bcrypt($password);
            $this->save();
        }
    }

    public function remove()
    {
        $this->delete();
    }

    public function uploadAvatar($image)
    {
        if ($image == null) {
            return;
        }

        $this->removeAvatar();

        $filename = str_random(10) . '.' . $image->extension();
        $image->storeAs('uploads', $filename);

        $this->avatar = $filename;
        $this->save();
    }

    public function removeAvatar()
    {
        if ($this->avatar != null) {
            Storage::delete($this->avatar);
        }
    }

    public function getAvatar()
    {
        if ($this->avatar == null) {
            return '/img/no-user-image.svg';
        }

        return '/uploads/' . $this->avatar;
    }

    public function makeAdmin()
    {
        $this->is_admin = User::IS_ADMIN;
        $this->save();
    }

    public function makeNormal()
    {
        $this->is_admin = User::IS_USER;
        $this->save();
    }

    public function toggleAdmin($value)
    {
        if ($value == null) {
            return;
        }

        if ($value == 1) {
            $this->makeAdmin();
        } else {
            $this->makeNormal();
        }
    }

    public function ban()
    {
        $this->status = User::IS_BANNED;
        $this->save();
    }

    public function unban()
    {
        $this->status = User::IS_ACTIVE;
        $this->save();
    }

    public function toggleBan($value)
    {
        if ($value == null) {
            return;
        }

        if ($value == 1) {
            $this->unban();
        } else {
            $this->ban();
        }
    }
}
