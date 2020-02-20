<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use Storage;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'avatar_url', 'bio', 'username'
    ];

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


    public function arts(){

        return $this->hasMany('\App\Art', 'user_id');

    }


    public function comments(){

        return $this->hasMany('\App\Comment', 'user_id');

    }

    public function upvotes(){

        return $this->hasMany('\App\Upvote', 'user_id');

    }

    public function reported()
    {
        return $this->morphMany('\App\Report', 'reportable');
    }

    public function reports(){

        return $this->hasMany('\App\Report');

    }

    public function userLinks(){
        return $this->hasMany('\App\UserLink');
    }

    public function profile_picture($path){
        return Storage::disk('s3')->url($path);
    }

    public function contests(){
        return $this->hasMany('\App\Contest');
    }


    /**
     * 
     * Change status numbers to text and check if deleted or not
     * 
     */
    public function statusToText(){

        if($this->trashed()){
            return 'deleted';
        }
        switch($this->status){

            case 0:
                return 'inactive';

            case 1:
                return 'active';
            
            case 2:
                return 'blocked';

            default:
                return 'unknown';

        }
        return 'unknown';
    }
    
}
