<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Boards extends Model
{
    // softDeletes를 넣음
    use HasFactory, SoftDeletes;


    protected $guarded = ['id', 'created_at'];


    protected $dates = ['deleted_at'];


}
