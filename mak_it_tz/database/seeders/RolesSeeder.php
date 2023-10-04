<?php

namespace Database\Seeders;

use App\Models\Roles;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'id'       => 1,
                'name'     => 'admin'
            ],
            [
                'id'       => 2,
                'name'     => 'seller'
            ],
            [
                'id'       => 3,
                'name'     => 'user'
            ],
        ];

        foreach ($roles as $role) {
            Roles::query()
                ->updateOrCreate(
                    [
                        'id'       => $role['id'],
                    ],
                    [
                        'name' => $role['name'],
                    ]
                );
        }

        Roles::query()
            ->selectRaw("setval(pg_get_serial_sequence('roles', 'id'), max(id))")
            ->get();
    }
}
