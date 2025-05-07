<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RefreshToken extends Model
{
    /** @use HasFactory<\Database\Factories\RefreshTokenFactory> */
    use HasFactory,SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'expires_at' => 'datetime',
        'revoked' => 'boolean'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}