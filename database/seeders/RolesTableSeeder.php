<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('roles')->delete();
        
        \DB::table('roles')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'super_admin',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 13:20:22',
                'updated_at' => '2024-11-10 13:20:22',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'cliente',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 13:39:12',
                'updated_at' => '2024-11-10 13:39:12',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'asesor',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 14:08:37',
                'updated_at' => '2024-11-10 14:08:37',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'master',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 14:08:48',
                'updated_at' => '2024-11-10 14:08:48',
            ),
        ));
        
        
    }
}