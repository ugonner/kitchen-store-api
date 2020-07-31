<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Symfony\Component\VarDumper\Cloner\Data;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','mobile', 'email', 'password', 'roleid'
    ];

    //protected $guarded = [];
    /*protected $attributes = [
        'mobile'=> '07034667861',
        'about'=> 'just me',
        'imageurl'=> '/assets/images/logo.jpg',
        'rolenote'=> 'principal officer',
        'dateofregistration'=> 'Date::now()',
        'roleid'=> 1,
        'positionid'=> 1,
        'locationid'=> 4,
        'sublocationid'=> 1

    ];*/
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
