<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PollOption extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'poll_id',
        'option',
        'count',
    ];

    protected $appends = [
        'percent',
    ];

    public function percent()
    {
        $pollCount = Poll::find($this->poll_id)->count;
        $optionCount = $this->count;
        $percent = 0;
        if ($pollCount != 0){
            $percent = ($optionCount/$pollCount)*100;
        }

        return $percent;
    }

    public function getPercentAttribute()
    {
        return $this->percent();
    }

    public function poll()
    {
        return $this->belongsTo(Poll::class);
    }
}

