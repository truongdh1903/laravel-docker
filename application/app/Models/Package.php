<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'shop_id',
        'pick_name',
        'pick_address',
        'pick_phone',
        'name',
        'address',
        'phone',
        'deliver_id',
        'deliver_name',
        'deliver_phone',
        'return_name',
        'return_address',
        'return_phone',
        'status',
        'price',
        'order_day',
        'receive_day',
        'return_day',
        'deliver_cost'
    ];

    public function products() {
        return $this->belongsToMany(Product::class);
    }

    public function scopeById($query, $id) {
        if ($id) {
            $query = $query->where('id', $id);
        }
        return $query;
    }

    public function scopeOrderDayIn($query, $orderDayFrom, $orderDayTo) {
        if($orderDayFrom) {
            $query = $query->where('order_day', '>=', $orderDayFrom);
        }
        if($orderDayTo) {
            $query = $query->where('order_day', '<=', $orderDayTo);
        }
        return $query;
    }

    public function scopeReceiveDayIn($query, $receiveDayFrom, $receiveDayTo) {
        if($receiveDayFrom) {
            $query = $query->where('receive_day', '>=', $receiveDayFrom);
        }
        if($receiveDayTo) {
            $query = $query->where('receive_day', '<=', $receiveDayTo);
        }
        return $query;
    }

    public function scopeByShipperId($query, $id) {
        if ($id) {
            $query = $query->where('deliver_id', $id);
        }
    }
}
