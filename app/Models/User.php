<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $appends = [
        'is_profile_user',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
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

    public function identifies(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get flag whether user is staff.
     */
    protected function isProfileUser(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->identifies !== null && $this->identifies instanceof Profile,
        );
    }

    #[\Override]
    public function toArray(): array
    {
        // Abort if potential infinite recursion is detected
        $maxLimit = 100;

        if (count(debug_backtrace(limit: $maxLimit)) === $maxLimit) {
            throw new \Exception('Potential infinite recursion detected. See stack trace for more information.');
        }

        return parent::toArray();
    }
}
