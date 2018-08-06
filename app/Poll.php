<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Poll extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'title',
        'target',
        'status',
        'count',
        'user_voted',
    ];

    protected $appends = [
        'attach',
    ];

    public function attach()
    {
        $target ='';
        $status ='';
        if ($this->target=='all'){
            $target =  'همه';
        }
        elseif ($this->target=='teacher'){
            $target =   'دبیران';
        }
        elseif ($this->target=='student'){
            $target =  'دانش آموزان';
        }
        elseif ($this->target=='parent'){
            $target =   'اولیا';
        }

        if ($this->status==1){
            $status =  'فعال';
        }
        elseif ($this->target==0){
            $status = 'غیرفعال';
        }
        return [
            'target_name' => $target,
            'status_name' => $status,
        ];
    }

    public function getAttachAttribute()
    {
        return $this->attach();
    }

    public function options()
    {
        return $this->hasMany(PollOption::class);
    }
}

