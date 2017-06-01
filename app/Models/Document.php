<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Document extends Model
{
    protected $fillable = [
        'name',
        'path',
        'mime_type',
        'size',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shares()
    {
        return $this->hasMany(DocumentShare::class);
    }

    /**
     * If the current authenticated user is the owner of the current document
     *
     * @return bool
     */
    public function isOwner()
    {
        return (int)Auth::user()->id === (int)$this->user_id;
    }
}
