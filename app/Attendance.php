<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'cuy_id',
        'subject_id',
        'date',
        'status'
    ];

    protected $appends = [
        'attach',
    ];

    public function attach()
    {
        $cuy = Cuy::find($this->cuy_id);
        $user = User::find($cuy->user_id);
        $subject = Subject::find($this->subject_id);
        if ($this->status == 1){
            $status_name = 'حاضر';
        }
        if ($this->status == 2){
            $status_name = 'غایب';
        }
        if ($this->status == 3){
            $status_name = 'تاخیر';
        }
        if ($this->status == 4){
            $status_name = 'تاخیر موجه';
        }
        return [
            'fullname' => $user->lname." ".$user->fname,
            'img' => $user->photo,
            'subject_name' => $subject->subject,
            'status_name' => $status_name
        ];
    }

    public function getAttachAttribute()
    {
        return $this->attach();
    }
}
