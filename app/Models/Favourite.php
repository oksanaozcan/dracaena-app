<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Favourite extends Model
{
    use HasFactory;

    protected $fillable = ['client_id', 'product_id'];

    protected $table = 'favourites';

}
