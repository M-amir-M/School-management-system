<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Clase extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'clase',
        'field_id'
    ];


    protected $appends = [
        'field_name',
    ];

    public function field_name()
    {
        $field = Field::find($this->field_id);
        $grade = Grade::find($field->grade_id);
        return $field->field."-".$grade->grade;
    }

    public function getFieldNameAttribute()
    {
        return $this->field_name();
    }

    public function field()
    {
        return $this->belongsTo(Field::class);
    }

    public function delete()
    {
        parent::delete();
    }

    public function claseschedules()
    {
        return $this->hasMany(Claseschedule::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

}
