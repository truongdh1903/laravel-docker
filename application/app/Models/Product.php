<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'weight',
        'type',
        'shop_id',
        'image',
        'price',
        'sale_off_rate',
        'quantity'
    ];

    public function packages() {
        return $this->belongsToMany(Package::class);
    }
}
