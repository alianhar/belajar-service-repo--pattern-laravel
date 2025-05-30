<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Model
{
    /** @use HasFactory<\Database\Factories\OrderItemFactory> */
    use HasFactory,SoftDeletes;

    protected $guarded = ['id'];

    public function orders(){
        return $this->hasMany(Order::class);
    }

    public function books(){
        return $this->hasMany(Book::class);
    }
}
