<?php

namespace Tests\Unit;

use App\User;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityTest extends TestCase
{

    //use RefreshDatabase;

    public function testItHasUser()
    {
        $this->withoutExceptionHandling();

        $user = $this->signIn();

        $project = ProjectFactory::ownedBy($user)->create();

        $this->assertInstanceOf(User::class, $project->activity->first()->user);
        $this->assertEquals($user->toArray(), $project->activity->first()->user->toArray());
    }
}
