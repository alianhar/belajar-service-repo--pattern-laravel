<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    /** @use HasFactory<\Database\Factories\BookFactory> */
    use HasFactory,SoftDeletes;

    protected $guarded = ["id"];

    public function category(){
        return $this->belongsTo(BookCategory::class);
    }

    public function orderItem(){
        return $this->belongsTo(OrderItem::class);
    }
}
