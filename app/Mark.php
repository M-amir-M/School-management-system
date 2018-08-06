<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mark extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'mark',
        'cuy_id',
        'subject_id',
        'status',
        'date'
    ];

    protected $appends = [
        'subject_name','mark_type',
    ];

    public function subject_name()
    {
        $subject = Subject::find($this->subject_id);
        return $subject->subject;
    }

    public function getMarkTypeAttribute()
    {
        return $this->mark_type();
    }

    public function mark_type()
    {
        $type = '';
        if ($this->status == 'term1'){
            $type = 'دی';
        }
        elseif ($this->status == 'term2'){
            $type = 'خرداد';
        }
        elseif ($this->status == 'term3'){
            $type = 'شهریور';
        }
        return $type;
    }

    public function getSubjectNameAttribute()
    {
        return $this->subject_name();
    }

    public function claseUserYear()
    {
        return $this->belongsTo(Cuy::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
