<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
   User::create([
            'name' => 'test',
            'phone' => '000000000',
            'password' => Hash::make("test"),
        ]);

        User::create([
            'name' => 'admin',
            'phone' => '657528859',
            'password' => Hash::make("Juniorbrayel2009"),
        ]);

      User::create([
            'name' => 'Fobs',
            'phone' => '999999999',
            'password' => Hash::make("0000000000"),
        ]);
    }
}
