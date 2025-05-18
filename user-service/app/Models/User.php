<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\InteractsWithMedia ;
use Spatie\MediaLibrary\MediaCollections\Models\Media ;
use Laravel\Sanctum\HasApiTokens ;
use Spatie\MediaLibrary\HasMedia;

class User extends Authenticatable  implements HasMedia
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable , InteractsWithMedia , HasApiTokens;


    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        // 'firebase_uid',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

     /**
     * Validation rules
     *
     * @var array
     */
    public static array $rules =[
        'name' => 'sometimes|string',
        'email' => 'required|email|unique:users',
        // 'email' => 'sometimes|email|unique:users,email,' . $user->id,
        'avatar' => 'sometimes|file|image|max:2048', // max 2MB
    ];

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d/m/Y H:i:s');
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d/m/Y H:i:s');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }




    // Tu peux définir des conversions si tu veux, par exemple pour des miniatures
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
             ->width(100)
             ->height(100);
    }

    public function getAvatarUrlAttribute()
    {
        return $this->getFirstMediaUrl('avatars') ?: null;
    }
}
