<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Schema;

class InstallTest extends TestCase
{
    use RefreshDatabase;
    

   
    public function test_create_admin()
    {

  
        $user = $this->createUser();
 
        $this->assertNotNull($user);
        
    }



    public function test_various_artisan_commands_and_queries()
    {
        $commands = [
            'route:list',
            //'config:cache',
            'config:clear',
            'cache:clear',
            'cache:forget cache-key-does-not-exist',
            'view:clear',
            'migrate:status',
            'queue:failed',
            'queue:restart',
            'queue:flush',
            'queue:work --once',
            'event:list',
            'optimize:clear',
            'clear-compiled',
            'schedule:list',
        ];

        foreach ($commands as $command) {
            try {
                $exitCode = Artisan::call($command);
                $this->assertEquals(0, $exitCode, "Artisan command '$command' should exit with code 0.");
            } catch (\Exception $e) {
                $msg = "Artisan command '$command' failed: " . $e->getMessage() . "\n"
                    . "Config files are located in 'config/' and cached config in 'bootstrap/cache/config.php'.\n"
                    . $e->getTraceAsString();

                \Log::error($msg);

                $this->fail($msg);
            }
        }

        // Test simple DB query - for example, count categories if that table exists
        try {
            $count = \DB::table('categories')->count();
            $this->assertIsInt($count, 'Categories count should be an integer.');
        } catch (\Exception $e) {
            $msg = "DB query failed: " . $e->getMessage() . "\n" . $e->getTraceAsString();
            \Log::warning($msg);
            $this->markTestSkipped('Categories table does not exist or DB query failed.');
        }
    }





    public function test_admin_has_admin_role()
    {
        
        Artisan::call("db:seed --class=RoleSeeder");
        
        $user = $this->createUser()->assignRole("admin");        

        $this->assertTrue($user->hasRole('admin'), "User does not have the 'admin' role.");
        
    }


}
