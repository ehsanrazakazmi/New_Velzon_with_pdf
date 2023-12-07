<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\CreateUserSeeder;
use Database\Seeders\PermissionTableSeeder;
use Database\Seeders\CreateRoleSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(PermissionTableSeeder::class);
        $this->call(CreateUserSeeder::class);
        $this->call(CreateRoleSeeder::class);

    }
}
