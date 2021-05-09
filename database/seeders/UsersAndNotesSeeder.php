<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\RoleHierarchy;

class UsersAndNotesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* Create roles */
        $role = Role::create(['name' => 'master']); 
        RoleHierarchy::create([
            'role_id' => $role->id,
            'hierarchy' => 1,
        ]);
        $role = Role::create(['name' => 'supervisor']);
        RoleHierarchy::create([
            'role_id' => $role->id,
            'hierarchy' => 2,
        ]);
        $role = Role::create(['name' => 'operator']); 
        RoleHierarchy::create([
            'role_id' => $role->id,
            'hierarchy' => 3,
        ]);
        
        /*  insert users   */
        $user = User::create([ 
            'name' => 'Hansen Halim',
            'email' => 'fpsecond.hh@p3villacitra.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$ZXBAcKEoMOwt42HUdDkWZeZ5Y39J4GmhuzCCG/yO6le6mkp5NpTiS', // password
            'remember_token' => Str::random(10),
            'menuroles' => 'master,supervisor,operator' 
        ]);
        $user->assignRole('master');
        $user->assignRole('supervisor');
        $user->assignRole('operator');

        $user = User::create([ 
            'name' => 'Then Pau An',
            'email' => 'tpauan@p3villacitra.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$TABKxw2XFEfG1acTTG5YKuQanbbHgozY3a5pjvY8UR..TvBw/1HmO', // password
            'remember_token' => Str::random(10),
            'menuroles' => 'master,supervisor,operator' 
        ]);
        $user->assignRole('master');
        $user->assignRole('supervisor');
        $user->assignRole('operator');
        
        $user = User::create([ 
            'name' => 'Operator 1',
            'email' => 'opr1@p3villacitra.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$.s62g.L4t34.JtCvIr4L/OczmGIqw4.ZIJZCe2f.xcvNtmpe4ixZm', // password
            'remember_token' => Str::random(10),
            'menuroles' => 'operator'
        ]);
        $user->assignRole('operator');

        $user = User::create([ 
            'name' => 'Operator 2',
            'email' => 'opr2@p3villacitra.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$FLh7X/P40KLRWej8y.hTOuAR3GNEtry8La1PUMdDS5qS2hFRfmwMq', // password
            'remember_token' => Str::random(10),
            'menuroles' => 'operator'
        ]);
        $user->assignRole('operator');

        $user = User::create([ 
            'name' => 'Supervisor 1',
            'email' => 'spv1@p3villacitra.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$1YaD8C7QgVJVzNcyu4KXluPK47EW8HpG1DJQ1sv50Fj3O7XeZKITi', // password
            'remember_token' => Str::random(10),
            'menuroles' => 'supervisor'
        ]);
        $user->assignRole('supervisor');

        $user = User::create([ 
            'name' => 'Supervisor 2',
            'email' => 'spv2@p3villacitra.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$UCXWDkbgFqU7SyDaj8eViu1Oljz/oZtPld7Nt32pnfC07V0VS9aV2', // password
            'remember_token' => Str::random(10),
            'menuroles' => 'supervisor'
        ]);
        $user->assignRole('supervisor');
    }
}