<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    //論理削除
    use SoftDeletes;
    protected $fillable = [
        'user_id',
        'category_name',
    ];
}