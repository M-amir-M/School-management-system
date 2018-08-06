<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Library extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'bookName',
        'bookDescription',
        'bookAuthor',
        'bookType',
        'bookPrice',
        'bookPath',
        'number',
    ];
}
