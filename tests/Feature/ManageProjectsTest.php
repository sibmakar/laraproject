<?php

namespace Tests\Feature;

use App\Project;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Facades\Tests\Setup\ProjectFactory;
use Tests\TestCase;

class ManageProjectsTest extends TestCase
{

    use WithFaker;
//use RefreshDatabase;

    /**
     * @test
     */
    public function a_user_can_create_a_project()
    {


        $this->signIn();
        $this->get('/projects/create')->assertStatus(200);

        $attributes = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->sentence,
            'notes' => 'General Notes Here',
        ];


        $response = $this->post('/projects', $attributes);

        $project = Project::where($attributes)->first();


        $response->assertRedirect($project->path());


        $this->get($project->path())
            ->assertSee($attributes['title'])
            ->assertSee($attributes['description'])
            ->assertSee($attributes['notes']);

    }


    /**
     * @test
     */
    public function tasks_can_be_included_as_part_a_new_project_creation()
    {
        $this->withoutExceptionHandling();

        $this->signIn();
        $attributes = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->sentence,
            'notes' => 'General Notes Here',
            'tasks' => [
                ['body' => 'Task 1'],
                ['body' => 'Task 2']
            ]
        ];

        $this->post('/projects', $attributes);

        $project = Project::where([
            'title' => $attributes['title'],
            'description' => $attributes['description']
        ])->first();

        $this->assertCount(2, $project->tasks);

    }


    /**
     * @test
     */
    public function a_user_can_update_a_project()
    {


        $project = ProjectFactory::create();
        $this->actingAs($project->owner)
            ->patch($project->path(), $attributes = ['title' => 'Changed title', 'description' => 'New Description','notes' => 'Changed Notes for Test'])
            ->assertRedirect($project->path());

        $this->assertDatabaseHas('projects', $attributes);

        $this->get($project->path() . '/edit')->assertOk();

    }


    /**
     * @test
     */
    public function a_user_can_view_their_project()
    {
       $project = ProjectFactory::create();

        $this->actingAs($project->owner)
            ->get($project->path())
            ->assertSee($project->title)
            ->assertSee(Str::limit($project->description, 140));

    }


    /**
     * @test
     */
    public function a_user_can_update_a_projects_general_notes()
    {

        $project = ProjectFactory::create();

        $this->actingAs($project->owner)
            ->patch($project->path(), [
                'notes' => 'New General Nodes for Tests!'
            ])->assertRedirect($project->path());


        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'notes' => 'New General Nodes for Tests!',
        ]);

    }


    /**
     * @test
     */

    public function an_authenticated_user_cannot_view_projects_of_other_users()
    {
        $this->signIn();

        $project = ProjectFactory::create();
        $this->get($project->path())
            ->assertStatus(403);
    }

    /**
     * @test
     */

    public function an_authenticated_user_cannot_update_projects_of_other_users()
    {
        $this->signIn();

        $project = ProjectFactory::create();
        $this->patch($project->path(), ['notes' => 'Changed Notes for Test'])
            ->assertStatus(403);
    }

    /**
     * @test
     */
    public function a_project_requires_a_title()
    {
        $this->signIn();

        $attributes = factory('App\Project')->raw(['title' => '']);
        $this->post('/projects', $attributes)->assertSessionHasErrors('title');
    }

    /**
     * @test
     */
    public function a_project_requires_a_description()
    {
        $this->signIn();

        $attributes = factory('App\Project')->raw(['description' => '']);
        $this->post('/projects', $attributes)->assertSessionHasErrors('description');
    }


    /**
     * @test
     */
    public function guests_cannot_manage_projects()
    {

        $project = ProjectFactory::create();

        $this->post('/projects', $project->toArray())->assertRedirect('login');
        $this->get('/projects')->assertRedirect('login');
        $this->get('/projects/create')->assertRedirect('login');
        $this->get($project->path())->assertRedirect('login');
        $this->get($project->path() . '/edit')->assertRedirect('login');
    }

    /**
     * @test
     */
    public function an_authenticated_user_can_delete_a_project()
    {
        $this->withoutExceptionHandling();

        $project = ProjectFactory::create();
        $this->actingAs($project->owner)
            ->delete($project->path())
            ->assertRedirect('/projects');


        $this->assertDatabaseMissing('projects', $project->only('id'));

    }

    /**
     * @test
     */
    public function guest_cannot_delete_a_project()
    {
        //$this->withoutExceptionHandling();

        $project = ProjectFactory::create();

        $this->delete($project->path())
            ->assertRedirect('login');

        $this->signIn();

        $this->delete($project->path())
            ->assertStatus(403);


        $this->assertDatabaseHas('projects', $project->only('id'));

    }

    /**
     * @test
     */
    public function a_member_cannot_delete_a_project()
    {
        $project = ProjectFactory::create();
        $user = $this->signIn();

        $project->invite($user);

        $this->delete($project->path())->assertStatus(403);
    }


    /**
     * @test
     */
    public function a_user_can_see_all_projects_they_have_been_invited_to_on_their_dashboard()
    {
        $this->withoutExceptionHandling();

        $project = ProjectFactory::create();

        $project->invite($anotherUser = factory(User::class)->create());

        $this->actingAs($anotherUser)->get('projects')->assertSee($project->title);

    }


}
