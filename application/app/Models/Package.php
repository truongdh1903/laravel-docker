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

    public function scopeOrderDayIn($query, $order_day_from, $order_day_to) {
        if($order_day_from) {
            $query = $query->where('order_day', '>=', $order_day_from);
        }
        if($order_day_to) {
            $query = $query->where('order_day', '<=', $order_day_to);
        }
        return $query;
    }

    public function scopeReceiveDayIn($query, $receive_day_from, $receive_day_to) {
        if($receive_day_from) {
            $query = $query->where('receive_day', '>=', $receive_day_from);
        }
        if($receive_day_to) {
            $query = $query->where('receive_day', '<=', $receive_day_to);
        }
        return $query;
    }

    public function scopeByShipperId($query, $id) {
        if ($id) {
            $query = $query->where('deliver_id', $id);
        }
    }
}
