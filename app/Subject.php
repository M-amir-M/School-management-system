<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'subject',
        'vahed',
        'code',
        'field_id',
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

    public static function getClassSubject($class_id)
    {
        $class = Clase::find($class_id);
        $subjects = Subject::where(['field_id' => $class->field_id])
            ->select('id','subject','code','vahed','field_id')
            ->orderBy('code')->get();
        return $subjects;
    }

    public function marks()
    {
        return $this->hasMany(Mark::class);
    }

    public function delete()
    {
        parent::delete();
    }
}
