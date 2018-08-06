<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Morilog\Jalali\jDate;

class News extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'title',
        'text',
        'target',
        'author'
    ];


    protected $appends = [
        'attach',
    ];

    public function attach()
    {
        if ($this->target=='all'){
            $target =  'همه';
        }
        elseif ($this->target=='teachers'){
            $target =   'دبیران';
        }
        elseif ($this->target=='students'){
            $target =  'دانش آموزان';
        }
        elseif ($this->target=='parents'){
            $target =   'اولیا';
        }

        $date = jDate::forge($this->created_at)->format('%B %d، %Y');
        return [
            'target_name' => $target,
            'date' => $date,
        ];
    }

    public function getAttachAttribute()
    {
        return $this->attach();
    }
}
