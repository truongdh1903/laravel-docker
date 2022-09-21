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
        'buyer_id',
        'shop_id',
        'pick_name',
        'pick_address',
        'pick_phone',
        'name',
        'address',
        'phone',
        'return_name',
        'return_address',
        'return_phone',
        'status',
        'price'
    ];

    public function products() {
        return $this->belongsToMany(Product::class);
    }
}
