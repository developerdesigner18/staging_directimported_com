<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailTemplates extends Model
{
    protected $fillable = [
        'key',
        'subject',
        'placeholder',
        'body',
    ];

    public function render($data = [])
    {
        return \Illuminate\Support\Facades\Blade::render($this->body, $data);
    }
}
