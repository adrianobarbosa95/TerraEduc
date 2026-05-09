<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
  
protected $fillable = [
    'name','email','password','slug','bio','photo',
    'github','linkedin','instagram','website','lattes'
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
public function disciplines()
{
    return $this->hasMany(Discipline::class);
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
   protected static function boot()
{
    parent::boot();

    static::creating(function ($user) {

        if (!$user->slug) {

            $originalSlug = Str::slug($user->name ?? 'usuario');
            $slug = $originalSlug;
            $i = 1;

            while (self::where('slug', $slug)->exists()) {
                $slug = "{$originalSlug}-{$i}";
                $i++;
            }

            $user->slug = $slug;
        }
    });
}
}
