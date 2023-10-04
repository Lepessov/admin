<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'id' => 1,
                'name' => 'Admin',
                'email' => 'admin@admin.com',
                'role_id' => 1
            ],
        ];

        foreach ($users as $user) {
            User::query()
                ->updateOrCreate(
                    [
                        'id'    => $user['id'],
                        'email' => $user['email'],
                    ],
                    [
                        'role_id'  => $user['role_id'],
                        'name'  => $user['name'],
                        'password' => Hash::make('admin'),
                    ]
                );
        }

        User::query()
            ->selectRaw("setval(pg_get_serial_sequence('users', 'id'), max(id))")
            ->get();
    }
}
