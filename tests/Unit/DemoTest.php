<?php

namespace Tests\Unit;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class DemoTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Tests whether the user model can be serialized to JSON.
     *
     * @return void
     */
    public function testUserJsonSerializeWithProfileAttached()
    {
        $profile = new Profile();
        $profile->save();

        $user = User::factory()->make();
        $user->identifies()->associate($profile);
        $user->save();

        // Get a fresh user model and reset cached relationships
        $user = $user->fresh();

        // This causes an infinite recursion
        $this->assertNotNull($user->jsonSerialize());
    }

    /**
     * Tests whether the user model can be converted to an array.
     *
     * @return void
     */
    public function testUserToArrayWithProfileAttached()
    {
        $profile = new Profile();
        $profile->save();

        $user = User::factory()->make();
        $user->identifies()->associate($profile);
        $user->save();

        // Get a fresh user model and reset cached relationships
        $user = $user->fresh();

        // This causes an infinite recursion
        $this->assertNotNull($user->toArray());
    }

    /**
     * Tests whether the user model can be converted to an array of attributes.
     *
     * @return void
     */
    public function testUserAttributesToArrayWithProfileAttached()
    {
        $profile = new Profile();
        $profile->save();

        $user = User::factory()->make();
        $user->identifies()->associate($profile);
        $user->save();

        // Get a fresh user model and reset cached relationships
        $user = $user->fresh();

        // This does not cause an infinite recursion as the relations are not included
        $this->assertNotNull($user->attributesToArray());
    }

    /**
     * Tests whether the user controller does not cause recursion.
     *
     * @return void
     */
    public function testUserControllerDoesNotCauseRecursion(): void
    {
        $profile = new Profile();
        $profile->save();

        $user = User::factory()->make();
        $user->identifies()->associate($profile);
        $user->save();

        $response = $this->get('/users');

        // Check whether the request was successful and did not cause recursion
        $response->assertStatus(200);
    }
}
