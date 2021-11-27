<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // This function allows us to create a dummy profile for each time a registered
    // user logs into the platform for the first time.
    protected static function boot() {
        parent::boot();

        // The first time each user signs in, their username will appear at the dummy profile
        static::created(
            function ($user) {
                $user->profile()->create([
                    'title'=> $user->username,
                ]);
            }
        );
    }

    // Each user has many posts that can create... Same applies to the Post.php 
    // where we're creating another function that tells us that the user is able to 
    // create many posts (hasMany)
    public function posts() {
        return $this ->hasMany(Post::class)->orderBy('created_at', 'DESC'); // Added the "->orderBy('created_at', 'DESC')" as we needed our posts to be ordered by the newest post to be displayed first
    }

    // We are in the User folder... A user has ONE profile, so we're creating a function
    // that tells us that if a user is logged in, he/she will have only ONE profile
    // The same applies to Profile.php which creates a function that tells the program
    // that a user that has a profile, that profile belongsTo the user him/herself
    public function profile() {
        return $this->hasOne(Profile::class);
    }
}
