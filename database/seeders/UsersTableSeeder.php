<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new \App\Models\User([
            'username' => 'admin',
            'email'      => 'admin@admin.com',
            'password'   => bcrypt('gag@112211'),
            'role'      => 'admin',
            'status'     => 'active', // active, draft, trashed 
        ]);
        $user->save();
         
    }
}
