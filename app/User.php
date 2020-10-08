<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use Storage;

class User extends Authenticatable implements MustVerifyEmail
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
        'first_name', 'last_name', 'email', 'password', 'avatar_url', 'bio', 'username'
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

    /**
     * 
     * Get All Posts of This User
     * 
     */
    public function posts(){

        return $this->hasMany('\App\Post');

    }

    /**
     * 
     * Get Only Approved Posts Of This User
     * 
     */
    public function approvedPosts(){

        return $this->posts()->where('status', 'published');

    }


    public function comments(){

        return $this->hasMany('\App\Comment', 'user_id');

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

    public function downloadEvents(){
        return $this->hasMany('App\DownloadEvent');
    }

    public function viewEvents(){
        return $this->hasMany('App\ViewEvent');
    }

    public function emailCampaigns(){
        return $this->belongsToMany('App\EmailCampaign');
    }

    public function medias(){
        return $this->morphToMany('App\Media', 'mediable');
    }

    /**
     * 
     * Get Active Users
     * 
     */
    public function activeUsers(){
        return $this->where('status', 'active');
    }

    /**
     * 
     * Get the user's avatar
     * 
     */
    public function avatar(){
        $check_if_exists = $this->medias->where('sorting', 4)->first();
        if(empty($check_if_exists)){
            return '';
        }
        return $check_if_exists;
    }

    /**
     * 
     * Get the user's avatar url
     * 
     */
    public function avatarUrl(){
        $avatar = $this->avatar();
        if(empty($avatar)){
            $avatar = 'profiles/no-avatar.png';
        } else {
            $avatar = $avatar->url;
        }
        return Storage::url($avatar);
    }

    
    /**
     * Get the user's full name.
     * @return string
     */
    public function getFullNameAttribute() {
        return "{$this->first_name} {$this->last_name}";
    }

}
