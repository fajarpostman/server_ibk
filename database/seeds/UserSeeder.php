<?php

use Illuminate\Database\Seeder;

use App\Models\User\User;
use App\Models\User\Profile;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            'admin', 'admin@admin.com', bcrypt('admin'), 'admin'
        ];

        User::create([
            'username' => $users[0],
            'email' => $users[1],
            'password' => $users[2],
            'position' => $users[3],
        ]);

        Profile::create([
            'user_email' => $users[1],
        ]);
    }
}
