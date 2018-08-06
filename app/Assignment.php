<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Assignment extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];


    protected $fillable = [
        'class_ids',
        'subject_ids',
        'year_id',
        'type',//1 = without file , 2 = with file , 3 = with file and upload , 4 = with link
        'title',
        'description',
        'deadtime',
        'path',
    ];
}
