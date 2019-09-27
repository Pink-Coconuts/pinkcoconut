<?php

use Illuminate\Database\Seeder;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admin')->delete();

        DB::table('role_user')->delete();

        DB::table('roles')->delete();

        DB::table('permission_role')->delete();

        DB::table('admin')->insert([
        	['id' => 1, 'username' => 'admin', 'email' => 'admin@trioangle.com', 'password' => Hash::make('makent'), 'status' => 'Active', 'created_at' => '2016-04-17 00:00:00'],
            ['id' => 2, 'username' => 'subadmin', 'email' => 'subadmin@trioangle.com', 'password' => Hash::make('subadmin123'), 'status' => 'Active', 'created_at' => '2016-04-17 00:00:00'],
            ['id' => 3, 'username' => 'accountant', 'email' => 'accountant@trioangle.com', 'password' => Hash::make('accountant123'), 'status' => 'Active', 'created_at' => '2016-04-17 00:00:00']
        ]);

        DB::table('roles')->insert([
            ['id' => 1, 'name' => 'admin', 'display_name' => 'Admin', 'description' => 'Admin User', 'created_at' => '2016-04-17 00:00:00','updated_at' => '2016-04-17 00:00:00'],
            ['id' => 2, 'name' => 'subadmin', 'display_name' => 'subadmin', 'description' => 'subadmin', 'created_at' => '2016-04-17 00:10:00','updated_at' => '2016-04-17 00:00:00'],
            ['id' => 3, 'name' => 'accountant', 'display_name' => 'accountant', 'description' => 'accountant', 'created_at' => '2016-04-17 00:10:00','updated_at' => '2016-04-17 00:00:00']
        ]);

        DB::table('role_user')->insert([
            ['user_id' => 1, 'role_id' => '1'],
            ['user_id' => 2, 'role_id' => '2'],
            ['user_id' => 3, 'role_id' => '3']
        ]);

        DB::table('permission_role')->insert([
            ['permission_id' => 1, 'role_id' => '1'],
            ['permission_id' => 1, 'role_id' => '2'],
            ['permission_id' => 2, 'role_id' => '1'],
            ['permission_id' => 2, 'role_id' => '2'],
            ['permission_id' => 3, 'role_id' => '1'],
            ['permission_id' => 3, 'role_id' => '2'],
            ['permission_id' => 4, 'role_id' => '1'],
            ['permission_id' => 5, 'role_id' => '1'],
            ['permission_id' => 6, 'role_id' => '1'],
            ['permission_id' => 7, 'role_id' => '1'],
            ['permission_id' => 8, 'role_id' => '1'],
            ['permission_id' => 8, 'role_id' => '3'],
            ['permission_id' => 9, 'role_id' => '1'],
            ['permission_id' => 9, 'role_id' => '2'],
            ['permission_id' => 9, 'role_id' => '3'],
            ['permission_id' => 10, 'role_id' => '1'],
            ['permission_id' => 10, 'role_id' => '3'],
            ['permission_id' => 11, 'role_id' => '1'],
            ['permission_id' => 12, 'role_id' => '1'],
            ['permission_id' => 13, 'role_id' => '1'],
            ['permission_id' => 14, 'role_id' => '1'],
            ['permission_id' => 14, 'role_id' => '2'],
            ['permission_id' => 14, 'role_id' => '3'],
            ['permission_id' => 15, 'role_id' => '1'],
            ['permission_id' => 16, 'role_id' => '1'],
            ['permission_id' => 17, 'role_id' => '1'],
            ['permission_id' => 17, 'role_id' => '3'],
            ['permission_id' => 18, 'role_id' => '1'],
            ['permission_id' => 19, 'role_id' => '1'],
            ['permission_id' => 20, 'role_id' => '1'],
            ['permission_id' => 21, 'role_id' => '1'],
            ['permission_id' => 22, 'role_id' => '1'],
            ['permission_id' => 23, 'role_id' => '1'],
            ['permission_id' => 24, 'role_id' => '1'],
            ['permission_id' => 25, 'role_id' => '1'],
            ['permission_id' => 26, 'role_id' => '1'],
            ['permission_id' => 27, 'role_id' => '1'],
            ['permission_id' => 28, 'role_id' => '1'],
            ['permission_id' => 29, 'role_id' => '1'],
            ['permission_id' => 30, 'role_id' => '1'],
            ['permission_id' => 31, 'role_id' => '1'],
            ['permission_id' => 32, 'role_id' => '1'],
            ['permission_id' => 33, 'role_id' => '1'],
            ['permission_id' => 34, 'role_id' => '1'],
            ['permission_id' => 35, 'role_id' => '1'],
            ['permission_id' => 36, 'role_id' => '1'],
            ['permission_id' => 37, 'role_id' => '1'],
            ['permission_id' => 38, 'role_id' => '1'],
            ['permission_id' => 39, 'role_id' => '1'],
            ['permission_id' => 40, 'role_id' => '1'],
            ['permission_id' => 41, 'role_id' => '1'],
            ['permission_id' => 42, 'role_id' => '1'],
            ['permission_id' => 43, 'role_id' => '1'],
            ['permission_id' => 44, 'role_id' => '1'],
            ['permission_id' => 45, 'role_id' => '1'],
            ['permission_id' => 46, 'role_id' => '1'],
            ['permission_id' => 47, 'role_id' => '1'],
            ['permission_id' => 48, 'role_id' => '1'],
            ['permission_id' => 49, 'role_id' => '1'],
        ]);
    }
}
