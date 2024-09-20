<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Profile extends Model
{
    use HasFactory;

    protected $appends = [
        'user_status',
    ];

    public function user(): MorphOne
    {
        return $this->morphOne(User::class, 'identifies');
    }

    /**
     * Get flag whether user is staff.
     */
    protected function userStatus(): Attribute
    {
        return Attribute::make(
            get: fn () => match (true) {
                $this->user === null => 'unregistered',
                $this->user->password === null => 'no-password',
                default => 'active',
            },
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
