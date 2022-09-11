<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $user = new User;

        $user->name = 'Admin';
        $user->email = config('app.admin.email');
        $user->password = config('app.admin.password');

        $user->save();
    }
}
