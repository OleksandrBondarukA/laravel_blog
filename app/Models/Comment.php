<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    const STATUS_DISALLOW = 0;
    const STATUS_ALLOW = 1;

    public function post()
    {
        return $this->hasOne(Post::class);
    }

    public function author()
    {
        return $this->hasOne(User::class);
    }

    public function allow()
    {
        $this->status = Comment::STATUS_ALLOW;
        $this->save();
    }

    public function disallow()
    {
        $this->status = Comment::STATUS_DISALLOW;
        $this->save();
    }

    public function toggleStatus()
    {
        if ($this->status === Comment::STATUS_DISALLOW) {
            return $this->allow();
        }

        return $this->disallow();
    }

    public function remove()
    {
        $this->delete();
    }
}
