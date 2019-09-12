<?php

namespace Tests\Feature;

use App\User;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvitationTest extends TestCase
{
    //use RefreshDatabase;


    /**
     * @test
     */
    public function nonowner_may_not_invite_users()
    {
        //$this->withoutExceptionHandling();

        $project = ProjectFactory::create();

        [$firstUser, $secondUser] = factory('App\User', 2)->create();

        $this->actingAs($secondUser)->post($project->path() . '/invitation', [
            'email' => $firstUser->email,
        ])->assertStatus(403);

        $project = $project->fresh();

        $this->assertFalse($project->members->contains($firstUser));


    }


    /**
     * @test
     */
    public function a_member_cannot_invite_a_user()
    {
        $project = ProjectFactory::create();

        [$firstUser, $secondUser] = factory('App\User', 2)->create();

        $project->invite($firstUser);


        $this->actingAs($firstUser)->post($project->path() . '/invitation', [
            'email' => $secondUser->email,
        ])->assertStatus(403);

        $this->assertFalse($project->members->contains($secondUser));
    }


    /**
     * @test
     */
    public function a_project_owner_can_invite_a_user()
    {
        $this->withoutExceptionHandling();

        $project = ProjectFactory::create();

        $user = factory('App\User')->create();

        $this->actingAs($project->owner)->post($project->path() . '/invitation', [
            'email' => $user->email,
        ])->assertRedirect($project->path());

        $this->assertTrue($project->members->contains($user));

    }



    /**
     * @test
     */
    public function the_invited_email_must_be_a_valid_birdboard_account()
    {
        //$this->withoutExceptionHandling();

        $project = ProjectFactory::create();

        $this->actingAs($project->owner)->post($project->path() . '/invitation', [
            'email' => 'nonuser11111xxx@email.com',
        ])->assertSessionHasErrors([
            'email' => 'The user you are inviting must have a Birdboard account.'
        ], null, 'invitations');


    }


    /**
     * @test
     */
    public function invited_users_may_update_projects_details()
    {

        $this->withoutExceptionHandling();

        $project = ProjectFactory::create();


        $project->invite($anotherUser = factory(User::class)->create());

//        $this->actingAs($anotherUser)->post($project->path() . '/tasks', $task = ['body' => $anotherUser->name . ' Foo Test Tasks']);

        $this->signIn($anotherUser);

        $this->post(action('ProjectTasksController@store', $project), $task = [ 'body' => 'Foo Task One Two']);

        $this->assertDatabaseHas('tasks', $task);


    }
}
