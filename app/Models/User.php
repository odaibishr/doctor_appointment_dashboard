<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    public const ROLE_ADMIN = 'admin';
    public const ROLE_DOCTOR = 'doctor';
    public const ROLE_PATIENT = 'patient';
    public const ROLE_LEGACY_USER = 'user';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
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

    public function canAccessPanel(Panel $panel): bool
    {
        return in_array($this->roleNormalized(), [
            self::ROLE_ADMIN,
            self::ROLE_DOCTOR,
            self::ROLE_PATIENT,
        ], true);
    }

    public function roleNormalized(): string
    {
        return $this->role === self::ROLE_LEGACY_USER ? self::ROLE_PATIENT : (string) $this->role;
    }

    public function isAdmin(): bool
    {
        return $this->roleNormalized() === self::ROLE_ADMIN;
    }

    public function isDoctor(): bool
    {
        return $this->roleNormalized() === self::ROLE_DOCTOR;
    }

    public function isPatient(): bool
    {
        return $this->roleNormalized() === self::ROLE_PATIENT;
    }

    public function doctor(): HasOne
    {
        return $this->hasOne(Doctor::class);
    }

    public function patient(): HasOne
    {
        return $this->hasOne(Patient::class);
    }
    public function appointments()
    {
        return $this->hasMany(BookAppointment::class);
    }
     public function reviews()
    {
        return $this->hasMany(Review::class);
    }
      public function favoriteDoctors()
    {
        return $this->hasMany(FavoriteDoctor::class);
    }
        public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}
