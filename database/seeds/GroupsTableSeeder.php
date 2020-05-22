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
        $generalGroupName = config('default.GeneralGroupName');

        $group = DB::table('groups')->where('name', $adminGroupName)->first();
        if ($group == null) {
            DB::transaction(function () use ($adminGroupName, $generalGroupName) {
                DB::table('groups')->insertGetId([
                    'name'       => $adminGroupName,
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now(),
                ]);
                DB::table('groups')->insertGetId([
                    'name'       => $generalGroupName,
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now(),
                ]);
            });
        }
    }
}
