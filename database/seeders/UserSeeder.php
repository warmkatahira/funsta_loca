<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
// モデル
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'user_id' => 'katahira',
            'last_name' => 'システム管理者',
            'first_name' => '',
            'email' => 't.katahira@warm.co.jp',
            'password' => bcrypt('katahira134'),
            'status' => 1,
            'role_id' => 'admin',
        ]);
        User::create([
            'user_id' => 'user',
            'last_name' => 'ユーザー',
            'first_name' => '',
            'email' => 'warm@warm.co.jp',
            'password' => bcrypt('user'),
            'status' => 1,
            'role_id' => 'user',
        ]);
    }
}