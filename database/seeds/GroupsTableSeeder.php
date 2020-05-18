<?php

use Illuminate\Database\Seeder;

class GroupsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminGroupName = config('default.adminGroupName');
        $group = DB::table('groups')->where('name', $adminGroupName)->first();
        if ($group == null) {
            DB::transaction(function () use ($adminGroupName) {
                DB::table('groups')->insertGetId([
                    'name'       => $adminGroupName,
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now(),
                ]);
            });
        }
    }
}
