<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomMail extends Model
{
    protected $fillable = ['to', 'subject', 'body', 'sent_at'];

    protected $casts = [
        'sent_at' => 'datetime',
    ];
}
