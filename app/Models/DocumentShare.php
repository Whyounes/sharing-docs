<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentShare extends Model
{
    protected $table = 'document_share';

    protected $fillable = [
        'document_id',
        'user_id',
        'view_count'
    ];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
