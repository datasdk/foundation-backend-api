<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class Install extends Command
{
    protected $signature = 'datas:install';

    protected $description = 'Install and configure Datas CMS';

    public function handle(): int
    {
        $this->info('Starting Datas installation...');

        $firstName = $this->ask('First name');
        $lastName  = $this->ask('Last name');
        $email     = $this->ask('E-mail');
        $password  = $this->secret('Password');

        $this->info('Generating application key...');

        Artisan::call('key:generate', [
            '--force' => true,
        ]);

        $this->line(Artisan::output());

        if ($this->confirm('This installation will reset the database. Continue?', true)) {
            $this->info('Running migrations...');

            Schema::disableForeignKeyConstraints();

            Artisan::call('migrate:fresh', [
                '--force' => true,
            ]);

            Schema::enableForeignKeyConstraints();

            $this->line(Artisan::output());
        }

        $this->info('Seeding database...');

        Artisan::call('db:seed', [
            '--force' => true,
        ]);

        $this->line(Artisan::output());

        $this->info('Creating storage link...');

        Artisan::call('storage:link');

        $this->line(Artisan::output());

        $this->info('Creating admin user...');

        $user = User::firstOrCreate(
            [
                'email' => $email,
            ],
            [
                'first_name'        => ucfirst($firstName),
                'last_name'         => ucfirst($lastName),
                'uid'               => (string) Str::uuid(),
                'password'          => Hash::make($password),
                'email_verified_at' => now(),
            ]
        );

        $user->assignRole('admin');

        $this->info('Datas CMS installed successfully.');

        return self::SUCCESS;
    }
}