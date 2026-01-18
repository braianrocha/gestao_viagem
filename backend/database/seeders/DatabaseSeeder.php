<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Criando meus usuários iniciais
        User::create([
            'name' => 'Admin',
            'email' => 'admin@teste.com',
            'password' => bcrypt('123456'),
            'is_admin' => true,
        ]);

        User::create([
            'name' => 'User',
            'email' => 'user@teste.com',
            'password' => bcrypt('123456'),
            'is_admin' => false,
        ]);
        
    }
}
