<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Field extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'field',
        'grade_id'
    ];
    
    protected $appends = [
        'grade_name'
    ];

    public function grade_name()
    {
        $grade = Grade::find($this->grade_id);
        return $grade->grade;
    }

    public function getGradeNameAttribute()
    {
        return $this->grade_name();
    }
    
    public function clases()
    {
        return $this->hasMany(Clase::class);
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }
    
    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    public function delete()
    {
        parent::delete();
    }
}
