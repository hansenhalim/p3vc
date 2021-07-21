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
      'password' => '$2a$10$rrmgjnqHk28lkl3mlZ/JxeMEJmiAtzgt3P521J687z0izx/IWFp/m', // password
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
      'password' => '$2a$10$R1YEcFv9qqgRIf0KSJ0jXORU22st6BF4vteXsPWNvMKK8312wp/A.', // password
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
      'password' => '$2a$10$.CA.6Lk79deK88RcP2sw/eLRenn7dLb.8TX/m4N3RNA7jgLXRBdPa', // password
      'remember_token' => Str::random(10),
      'menuroles' => 'operator'
    ]);
    $user->assignRole('operator');

    $user = User::create([
      'name' => 'Operator 2',
      'email' => 'opr2@p3villacitra.com',
      'email_verified_at' => now(),
      'password' => '$2a$10$.CA.6Lk79deK88RcP2sw/eLRenn7dLb.8TX/m4N3RNA7jgLXRBdPa', // password
      'remember_token' => Str::random(10),
      'menuroles' => 'operator'
    ]);
    $user->assignRole('operator');

    $user = User::create([
      'name' => 'Supervisor 1',
      'email' => 'spv1@p3villacitra.com',
      'email_verified_at' => now(),
      'password' => '$2a$10$.CA.6Lk79deK88RcP2sw/eLRenn7dLb.8TX/m4N3RNA7jgLXRBdPa', // password
      'remember_token' => Str::random(10),
      'menuroles' => 'supervisor'
    ]);
    $user->assignRole('supervisor');

    $user = User::create([
      'name' => 'Supervisor 2',
      'email' => 'spv2@p3villacitra.com',
      'email_verified_at' => now(),
      'password' => '$2a$10$.CA.6Lk79deK88RcP2sw/eLRenn7dLb.8TX/m4N3RNA7jgLXRBdPa', // password
      'remember_token' => Str::random(10),
      'menuroles' => 'supervisor'
    ]);
    $user->assignRole('supervisor');

    $user = User::create([
      'name' => 'Operator Dev',
      'email' => 'opr@dev.p3villacitra.com',
      'email_verified_at' => now(),
      'password' => '$2a$10$R1YEcFv9qqgRIf0KSJ0jXORU22st6BF4vteXsPWNvMKK8312wp/A.', // password
      'remember_token' => Str::random(10),
      'menuroles' => 'operator'
    ]);
    $user->assignRole('operator');

    $user = User::create([
      'name' => 'Supervisor Dev',
      'email' => 'spv@dev.p3villacitra.com',
      'email_verified_at' => now(),
      'password' => '$2a$10$R1YEcFv9qqgRIf0KSJ0jXORU22st6BF4vteXsPWNvMKK8312wp/A.', // password
      'remember_token' => Str::random(10),
      'menuroles' => 'supervisor'
    ]);
    $user->assignRole('supervisor');
  }
}
