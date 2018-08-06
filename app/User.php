<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use File;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'api_token',
        'username',
        'email',
        'password',
        'fname',
        'lname',
        'role',
        'birthday',
        'activated',
        'address',
        'phone',
        'mobile',
        'photo',
        'year_id'
    ];

    protected $hidden = [
        'password', 'remember_token','api_token',
    ];

    protected $appends = [
        'cuy_id','clase_id','fullname'
    ];
    
    public function cuy_id()
    {
        //اگر شرط if نزاریم به ارور بر میخوریم
        $cuy = Cuy::where(['user_id' => $this->id, 'year_id' => Auth::user()->year_id])->first();
        if (count($cuy) >0 ){
            return $cuy->id;
        }
        return NULL;
    }

    public function clase_id()
    {
        $cuy = Cuy::where(['user_id' => $this->id, 'year_id' => Auth::user()->year_id])->first();
        if(count($cuy)>0){
            return $cuy->clase;
        }
        return NULL;
    }

    public function getCuyIdAttribute()
    {
        return $this->cuy_id();
    }

    public function getClaseIdAttribute()
    {
        return $this->clase_id();
    }
    
    public function fullname()
    {
        $fullname = $this->lname." ".$this->fname;
        return $fullname;
    }

    public function getFullnameAttribute()
    {
        return $this->fullname();
    }

    public function hasRole($role)
    {
        $roles = explode('|',$role);
        if (in_array($this->role,$roles)){
            return true; // this looks for an admin column in your users table
        }
        return false;
    }






    public function cuys()
    {
        return $this->hasMany(Cuy::class);
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    public function delete()
    {
        parent::delete();
        if ($this->photo != 'male.png'){
            File::delete([
                $this->photo
            ]);
        }
    }

}
