<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableInterface;
class User extends Model implements AuthenticatableInterface
{
    use Authenticatable;

    protected $fillable = [
        'first_name',
        'last_name',
        'password',
        'email',
    ];

    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}
