<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Cashier\Billable;

class Client extends Model
{
    use HasFactory;
    use Billable;

    protected $table = 'clients';
    protected $guarded = [];

    public function orders()
    {
        return $this->hasMany(Order::class, 'client_id', 'clerk_id');
    }
}
