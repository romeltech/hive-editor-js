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
            'role'      => 'superadmin',
            'status'     => 'active', // active, draft, trashed
        ]);
        $user->save();

        $profile = new \App\Models\Profile([
            'user_id' => '1',
            'fullname'      => 'Steve Ayala',
            'position'   => 'Sr. Fullstack Developer',
            'slug'      => 'moikzz214',
        ]);
        $profile->save();


        $user = new \App\Models\User([
            'username' => 'romel',
            'email' => 'romel.i@gagroup.net',
            'password' => bcrypt('gag@112211'),
            'role' => 'superadmin',
            'status' => 'active', // active, draft, trashed
        ]);
        $user->save();
        $profile = new \App\Models\Profile([
            'user_id' => '2',
            'fullname' => 'Romel Indemne',
            'position' => 'President',
            'slug' => 'mel',
        ]);
        $profile->save();

    }
}
