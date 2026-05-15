<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use DataSDK\Categories\Models\Categories;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class SortingTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Test the 'changePosition' route.
     *
     * @return void
     */
    public function test_change_position()
    {
        
        $user = $this->createUser();


        $categories = Categories::factory()->count(5)->create();


        $tableName = "categories";
        
        $firstCategory = $categories[0];
        $secondCategory = $categories[3];

        $moveId =  $firstCategory->id;
        $moveToId = $secondCategory->id;

        // Angiv move_id og move_to_id
 
        $data = [
            "table" => $tableName,
            "move_id" => $moveId,
            "move_to_id" => $moveToId
        ];
     

        // Afsend POST-anmodningen til ruten 'sorting.change'
        $response = $this->actingAs($user)->postJson(route('sorting.change'), $data);

        // Bekræft, at svaret har status 200 (OK)
        $response->assertStatus(200);


    }

    
}
