<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Workbook extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'subject_id',
        'cuy_id',
        'mos1',
        'pay1',
        'mos2',
        'pay2',
    ];

    protected $appends = [
        'subject_name','subject_vahed'
    ];

    public function subject_name()
    {
        $subject = Subject::find($this->subject_id);
        return $subject->subject;
    }

    public function getSubjectVahedAttribute()
    {
        return $this->subject_vahed();
    }
    
    public function subject_vahed()
    {
        $subject = Subject::find($this->subject_id);
        return $subject->vahed;
    }

    public function getSubjectNameAttribute()
    {
        return $this->subject_name();
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    public function cuys()
    {
        return $this->hasMany(Cuy::class);
    }
}
