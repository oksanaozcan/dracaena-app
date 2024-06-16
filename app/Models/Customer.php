<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Customer extends Authenticatable
{
    use HasFactory;
    use HasApiTokens;
    use Notifiable;

    protected $table = 'customers';

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getRememberToken()
    {
      return null; // not supported
    }

    public function setRememberToken($value)
    {
      // not supported
    }

    public function getRememberTokenName()
    {
      return null; // not supported
    }

    /**
     * Overrides the method to ignore the remember token.
    */
    public function setAttribute($key, $value)
    {
    $isRememberTokenAttribute = $key == $this->getRememberTokenName();
    if (!$isRememberTokenAttribute)
    {
        parent::setAttribute($key, $value);
    }
    }

}
