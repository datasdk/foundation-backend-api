<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use User;


class Install extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'datas:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
    
        parent::__construct();
    }

    public function create_admin($first_name,$last_name,$email,$password,$uid){


        if(User::count() === 0){



            return User::firstOrCreate([
                "first_name" => ucfirst($first_name),
                "last_name" => ucfirst($last_name),
                "email" => $email,
                "password" => $password,
                "uid" => $uid,
                "email_verified_at" => now()
            ])
            ->assignRole("admin")
            ->setPassword($password);

        }


    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {


   
        $first_name = $this->ask('What is your first_name?');

        $last_name  = $this->ask('What is your last_name?');

        $email     = $this->ask('What is your E-mail?');

        $password  = $this->secret('Choose your password?');
    
        $uid = uniqid();



        // Migrating

        $this->info('Migrating');

        Schema::disableForeignKeyConstraints();

        Artisan::call("migrate:fresh");

        Schema::enableForeignKeyConstraints();

        // Seeding

        $this->info('Seeding database');

        Artisan::call("db:seed");


        // storage link

        $this->info('Creating storage link');

        Artisan::call("storage:link");



        /*
        $this->info('Clear logs');

        Artisan::command('logs:clear');

        $this->info('Clear caches');

        Artisan::command('cache:clear');
        */

        // create admin

        $this->info('Creating admin');

        $this->create_admin($first_name,$last_name,$email,$password,$uid);


        // success

        $this->info('Datas is installed successfuly!');

    }
}
