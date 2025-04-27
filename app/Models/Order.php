<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory,SoftDeletes;

    protected $guarded = ['id'];

    public function users(){
        return $this->hasMany(User::class);
    }

    public function orderItem(){
        return $this->belongsTo(OrderItem::class);
    }
}
