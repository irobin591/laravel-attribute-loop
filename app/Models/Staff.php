<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Staff extends Model
{
    use HasFactory;

    /**
     * @return MorphOne<User>
     */
    public function user(): MorphOne
    {
        return $this->morphOne(User::class, 'identifies');
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
