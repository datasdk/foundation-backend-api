<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RoleControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        // Spatie kræver at permissions/roles caches bliver nulstillet
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }

    public function test_index_returns_all_roles()
    {
        Role::create(["name" => "admin"]);
        Role::create(["name" => "editor"]);
        Role::create(["name" => "viewer"]);

        $response = $this->actingAs($this->user)->getJson(route('api.roles.index'));

        $response->assertOk()
                 ->assertJsonCount(3, 'data');
    }

    public function test_store_creates_a_new_role()
    {
        $data = [
            'name' => 'newrole',
            'lang' => 'da', // hvis dit API kræver det
        ];

        $response = $this->actingAs($this->user)->postJson(route('api.roles.store'), $data);

        $response->assertCreated()
                 ->assertJsonFragment(['name' => 'newrole']);

        $this->assertDatabaseHas('roles', ['name' => 'newrole']);
    }

    public function test_update_updates_existing_role()
    {
        $role = Role::create([
            'name' => 'oldrole',
        ]);

        $data = [
            'name' => 'newrole',
            'active' => true, // hvis dit API kræver det
        ];

        $response = $this->actingAs($this->user)->putJson(
            route('api.roles.update', ['role' => $role->id]),
            $data
        );

        $response->assertOk();

        $this->assertDatabaseHas('roles', [
            'id'   => $role->id,
            'name' => 'newrole',
        ]);
    }

    public function test_delete_deletes_existing_role()
    {
        $role = Role::create(['name' => 'deleteme']);

        $response = $this->actingAs($this->user)->deleteJson(route('api.roles.destroy', $role->id));

        $response->assertNoContent();

        $this->assertDatabaseMissing('roles', ['id' => $role->id]);
    }
}
