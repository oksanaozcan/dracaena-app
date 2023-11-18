<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillboardTag extends Model
{
    use HasFactory;

    protected $table = 'billboard_tags';
    protected $fillable = ['billboard_id', 'tag_id'];
}
