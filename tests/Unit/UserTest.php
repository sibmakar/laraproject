<?php

namespace Tests\Unit;

use App\User;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    //use RefreshDatabase;

    public function testUserHasProjects()
    {
        $user = factory('App\User')->create();

        $this->assertInstanceOf(Collection::class, $user->projects);
    }


    public function testUserHasAccessibleProjects()
    {
        $this->withoutExceptionHandling();


        ProjectFactory::ownedBy($anotherUser = factory(User::class)->create())->create();

        $this->assertCount(1, $anotherUser->accessibleProjects());



        $project = ProjectFactory::ownedBy($anotherUser2 = factory(User::class)->create())->create();
        $project->invite($anotherUser);

        $this->assertCount(2, $anotherUser->accessibleProjects());
//        dd($project->owner->accessibleProjects());
        $this->assertCount(1, $anotherUser2->accessibleProjects());
    }
}
