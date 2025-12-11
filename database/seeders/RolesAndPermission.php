<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermission extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    Permission::create(['name'=>'manager']);
    Permission::create(['name'=>'book_trip']);
    $admin=Role::create(['name'=>'admin']);
    $passenger=Role::create(['name'=>'passenger']);
    $admin->givepermissionto(['manager']);
    $passenger->givepermissionto(['book_trip']);
    $user=User::create([
        'name'=>'admin',
        'email'=>'admin@gmail.com',
        'password'=>Hash::make('password123'),
    ]);
    $user->assignRole('admin');
    }
}
