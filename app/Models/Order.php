<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * @property Carbon $created_at
 */

class Order extends Model
{
    protected $fillable =
    [
        'user_id',
        'status',
        'price',
    ];

    protected $casts =
    [
        'status' => OrderStatus::class,
    ];

    public function markAsCanceled()
    {
        $this->update(['status' => OrderStatus::CANCELED]);
    }
    public function markAsComleted()
    {
        $this->update(['status' => OrderStatus::COMPLETED]);
    }

    public function olderThan($minutes): bool
    {
        return ($this->created_at->diffInMinutes() > $minutes);
    }
}
