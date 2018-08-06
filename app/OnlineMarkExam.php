<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Morilog\Jalali\jDate;

class OnlineMarkExam extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable=[
        'exam_id',
        'cuy_id',
        'answers',
        'mark',
        'date',
    ];

    protected $appends = [
        'attach',
    ];

    public function attach()
    {
        $cuy = Cuy::find($this->cuy_id);
        $user = User::find($cuy->user_id);
        return [
            'fullname' => $user->lname." ".$user->fname,
            'img' => $user->photo,
            'exam_date' => jDate::forge($this->created_at)->format('datetime'),
        ];
    }

    public function getAttachAttribute()
    {
        return $this->attach();
    }
}
