<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Grade extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'grade'
    ];

    public function fields()
    {
        return $this->hasMany(Field::class);
    }

    public function delete()
    {
        parent::delete();
    }
}
