<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassSchedule extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable =[
        'clase_id',
        'subject_id',
        'year_id',
        'day_week',
        'start_time',
        'end_time'
    ];
    
    protected $appends = [
        'subject_name'
    ];

    public function subject_name()
    {
        $subject = Subject::find($this->subject_id);
        return $subject->subject;
    }

    public function getSubjectNameAttribute()
    {
        return $this->subject_name();
    }

}
