<?php

namespace Tests\Feature\Projects;

use App\Models\Law;
use App\Models\LawBook;
use App\Models\Project;
use App\Models\User;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    /**
     * The projects index page should be shown if a user is authenticated.
     */
    public function test_projects_index_screen_can_be_rendered(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/projects');

        $response->assertStatus(200);
    }

    /**
     * The projects index page should not be shown if no user is authenticated.
     */
    public function test_projects_index_screen_cannot_be_rendered_if_user_is_not_authenticated(): void
    {
        $response = $this->get('/projects');

        $response->assertRedirect('/login');
    }

    /**
     * The project store method should store a project in the database.
     */
    public function test_project_can_be_stored(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/projects', [
            'name' => 'Test Project',
        ]);

        $response->assertRedirect('/projects');
        $this->assertDatabaseHas('projects', [
            'name' => 'Test Project',
        ]);
    }

    /**
     * The project store method shouldn't store a project in the database if the name is missing.
     */
    public function test_project_cannot_be_stored_if_name_is_missing(): void
    {
        // Clear the database
        $this->artisan('migrate:fresh');

        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/projects', [
            'name' => '',
        ]);

        $response->assertSessionHasErrors('name');
        $this->assertDatabaseCount('projects', 0);
    }

    /**
     * The project store method should only be reachable when authenticated
     */
    public function test_project_cannot_be_stored_if_user_is_not_authenticated(): void
    {
        $this->artisan('migrate:fresh');

        $response = $this->post('/projects', [
            'name' => 'Test Project',
        ]);

        $response->assertRedirect('/login');
        $this->assertDatabaseCount('projects', 0);
    }

    /**
     * The project can be renamed when the user owns the project.
     */
    public function test_project_can_be_renamed(): void
    {
        $user = User::factory()->create();
        $project = new Project();
        $project->name = 'Test Project';
        $project = $user->projects()->save($project);

        $response = $this->actingAs($user)->patch(route('projects.rename', $project), [
            'name' => 'Renamed Project',
        ]);

        $response->assertRedirect('/projects');
        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'name' => 'Renamed Project',
            'user_id' => $user->id,
        ]);
    }

    /**
     * The project can only be renamed when the user owns the project.
     */
    public function test_project_cannot_be_renamed_if_user_does_not_own_project(): void
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $project = new Project();
        $project->name = 'Test Project';
        $project = $user->projects()->save($project);

        $response = $this->actingAs($user2)->patch(route('projects.rename', $project), [
            'name' => 'Renamed Project',
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'name' => 'Test Project',
            'user_id' => $user->id,
        ]);
    }

    /**
     * The project can be deleted when the user owns the project.
     */
    public function test_project_can_be_deleted(): void
    {
        $user = User::factory()->create();
        $project = new Project();
        $project->name = 'Test Project';
        $project = $user->projects()->save($project);

        $response = $this->actingAs($user)->delete(route('projects.destroy', $project));

        $response->assertRedirect('/projects');
        $this->assertDatabaseMissing('projects', [
            'id' => $project->id,
            'name' => 'Test Project',
            'user_id' => $user->id,
        ]);
    }

    /**
     * The project can only be deleted when the user owns the project.
     */
    public function test_project_cannot_be_deleted_if_user_does_not_own_project(): void
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $project = new Project();
        $project->name = 'Test Project';
        $project = $user->projects()->save($project);

        $response = $this->actingAs($user2)->delete(route('projects.destroy', $project));

        $response->assertStatus(403);
        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'name' => 'Test Project',
            'user_id' => $user->id,
        ]);
    }

    /**
     * The project show method should show the project if the user owns the project.
     */
    public function test_project_can_be_shown(): void
    {
        $user = User::factory()->create();
        $project = new Project();
        $project->name = 'Test Project';
        $project = $user->projects()->save($project);

        $response = $this->actingAs($user)->get(route('projects.show', $project));

        $response->assertStatus(200);
    }

    /**
     * The project show method should not show the project if the user does not own the project.
     */
    public function test_project_cannot_be_shown_if_user_does_not_own_project(): void
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $project = new Project();
        $project->name = 'Test Project';
        $project = $user->projects()->save($project);

        $response = $this->actingAs($user2)->get(route('projects.show', $project));

        $response->assertStatus(403);
    }

    /**
     * The project show view should show all laws of the project.
     */
    public function test_project_show_view_should_show_all_laws_of_the_project(): void
    {
        $this->artisan('migrate:fresh');

        $user = User::factory()->create();
        $project = new Project();
        $project->name = 'Test Project';
        $project = $user->projects()->save($project);

        $lawBook = new LawBook();
        $lawBook->name = 'Test Law Book';
        $lawBook->slug = 'hgb';
        $lawBook->short = 'HGB';
        $lawBook->prefix = '§';

        $lawBook->save();

        $law = new Law();
        $law->name = 'Test Law';
        $law->slug = '192';
        $law->url = 'https://www.gesetze-im-internet.de/hgb/__192.html';
        $law->lawBook()->associate($lawBook);

        $project->laws()->save($law);

        $response = $this->actingAs($user)->get(route('projects.show', $project));

        $response->assertSee($lawBook->name);
        $response->assertSee($lawBook->slug);

        $response->assertSee($law->name);
        $response->assertSee($law->slug);
    }

    /**
     * Test that laws can be removed from a project if the user owns the project.
     */
    public function test_law_can_be_removed_from_project(): void
    {
        // Migrate fresh to clear the database
        $this->artisan('migrate:fresh');

        $user = User::factory()->create();
        $project = new Project();
        $project->name = 'Test Project';
        $project = $user->projects()->save($project);

        $lawBook = new LawBook();
        $lawBook->name = 'Test Law Book';
        $lawBook->slug = 'hgb';
        $lawBook->short = 'HGB';
        $lawBook->prefix = '§';

        $lawBook->save();

        $law = new Law();
        $law->name = 'Test Law';
        $law->slug = '192';
        $law->url = 'https://www.gesetze-im-internet.de/hgb/__192.html';
        $law->lawBook()->associate($lawBook);

        $project->laws()->save($law);

        $response = $this->actingAs($user)->delete(route('laws.destroy', [$project, $law]));

        $response->assertRedirect(route('projects.show', $project));
        $this->assertDatabaseMissing('laws', [
            'id' => $law->id,
            'name' => 'Test Law',
            'slug' => '192',
            'url' => 'https://www.gesetze-im-internet.de/hgb/__192.html',
            'law_book_id' => $lawBook->id,
            'project_id' => $project->id,
        ]);
    }

    /**
     * Test that laws cannot be removed from a project if the user does not own the project.
     */
    public function test_law_cannot_be_removed_from_project_if_user_does_not_own_project(): void
    {
        // Migrate fresh to clear the database
        $this->artisan('migrate:fresh');

        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $project = new Project();
        $project->name = 'Test Project';
        $project = $user->projects()->save($project);

        $lawBook = new LawBook();
        $lawBook->name = 'Test Law Book';
        $lawBook->slug = 'hgb';
        $lawBook->short = 'HGB';
        $lawBook->prefix = '§';

        $lawBook->save();

        $law = new Law();
        $law->name = 'Test Law';
        $law->slug = '192';
        $law->url = 'https://www.gesetze-im-internet.de/hgb/__192.html';
        $law->lawBook()->associate($lawBook);

        $project->laws()->save($law);

        $response = $this->actingAs($user2)->delete(route('laws.destroy', [$project, $law]));

        $response->assertStatus(403);
        $this->assertDatabaseHas('laws', [
            'id' => $law->id,
            'name' => 'Test Law',
            'slug' => '192',
            'url' => 'https://www.gesetze-im-internet.de/hgb/__192.html',
            'law_book_id' => $lawBook->id,
            'project_id' => $project->id,
        ]);
    }
}
