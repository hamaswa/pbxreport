<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable; 

   	protected $guard_name = 'web'; // or whatever guard you want to use

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'mobile', 'did_no', 'status'
    ]; 

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
	 
	public function Extension()
	{
		return $this->hasMany('App\Models\Extension');
	}
	
	
	public  function queue(){
	    return $this->hasMany('App\Models\Queue','user_id');
    }


	/**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required|string|max:255',
		'email' => 'required|string|email|max:255|unique:users',
		'password' => 'required|string|min:6|confirmed',
		'mobile' => 'required|string|max:255',
		'status' => 'required|string|max:1',
    ]; 
	
	/**
     * Validation rules
     *
     * @var array
     */
    public static $updaterules = [
        'name' => 'required|string|max:255',
		'mobile' => 'required|string|max:255',
		'status' => 'required|string|max:1',
    ]; 
}
