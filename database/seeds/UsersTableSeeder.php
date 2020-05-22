<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $group = DB::table('groups')->where('name', config('default.adminGroupName'))->first();
        $user = DB::table('users')->where('account', config('default.adminAccount'))->first();
        if ($group !== null && $user === null) {
            DB::table('users')->insert([
                'group_id'   => $group->id,
                'account'    => config('default.adminAccount'),
                'email'      => config('default.adminEmail'),
                'password'   => Hash::make(config('default.adminPassword')),
                'name'       => config('default.adminName'),
                'active'     => 1,
                'token'      => '',
                'login_at'   => null,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
            ]);
        }
    }
}
