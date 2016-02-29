<?php

namespace App;

use App\Role;
use Auth;
use App\RoleUser;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public function instance()
    {
        return $this->hasOne('App\Instance');
    }

    public function roles()
    {
        return $this->belongsToMany('App\Role')->withTimestamps();
    }

    public function assignRole($role)
    {
        if(is_string($role)){
            $role = Role::where('name', $role)->firstOrFail();
        }
        
        $roleuser = RoleUser::where('role_id', $role->id)->where('user_id',Auth::user()->id)->count();
        
        if($roleuser>0){
            return false;
        }
        return $this->roles()->attach($role);
    }

    public function revokeRole($role)
    {
        if(is_string($role)){
            $role= Role::where('name', $role)->firstOrFail();
        }

        return $this->roles()->detach($role);
    }

    public function hasRole($name)
    {
        foreach ($this->roles as $role) {
            if($role->name === $name ) return true;
        }

        return false;
    }
}
