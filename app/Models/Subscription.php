<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    const STATUS_UNSUBSCRIBED = 0;
    const STATUS_SUBSCRIBED = 1;

    protected $fillable = [
        'email',
        'status'
    ];

    public static function add($fields)
    {
        $subscription = new static;
        $subscription->fillable($fields);
        $subscription->status = Subscription::STATUS_UNSUBSCRIBED;
        $subscription->save();
    }

    public function remove()
    {
        $this->delete();
    }

    public function subscribe()
    {
        $this->status = Subscription::STATUS_SUBSCRIBED;
        $this->save();
    }

    public function unsubscribe()
    {
        $this->status = Subscription::STATUS_UNSUBSCRIBED;
        $this->save();
    }

    public function toggleStatus()
    {
        if ($this->status === Subscription::STATUS_UNSUBSCRIBED) {
            return $this->subscribe();
        }

        return $this->unsubscribe();
    }
}
