<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Teacher extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'phone', 'password'];

    protected $hidden = ['password'];

    public function profilePicture()
    {
        return $this->morphOne(ProfilePicture::class, 'userable');
    }

    public function getProfilePictureUrlAttribute()
    {
        if ($this->profilePicture) {
            // return asset('storage/' . $this->profilePicture->filename);
            return url('storage/' . $this->profilePicture->filename);
        }
        return null;
    }

    protected $appends = ['profile_picture_url'];

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\ResetPasswordNotification($token));
    }
}
