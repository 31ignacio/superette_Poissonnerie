<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transfert extends Model
{
    use HasFactory;
    protected $guarded = [''];


    public function user()
{
    return $this->belongsTo(user::class, 'user_id');
}
}
