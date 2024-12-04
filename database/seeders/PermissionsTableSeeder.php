<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('permissions')->delete();
        
        \DB::table('permissions')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'view_asesor',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 13:20:22',
                'updated_at' => '2024-11-10 13:20:22',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'view_any_asesor',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 13:20:22',
                'updated_at' => '2024-11-10 13:20:22',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'create_asesor',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 13:20:22',
                'updated_at' => '2024-11-10 13:20:22',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'update_asesor',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 13:20:22',
                'updated_at' => '2024-11-10 13:20:22',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'restore_asesor',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 13:20:22',
                'updated_at' => '2024-11-10 13:20:22',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'restore_any_asesor',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 13:20:22',
                'updated_at' => '2024-11-10 13:20:22',
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'replicate_asesor',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 13:20:22',
                'updated_at' => '2024-11-10 13:20:22',
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'reorder_asesor',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 13:20:22',
                'updated_at' => '2024-11-10 13:20:22',
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'delete_asesor',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 13:20:22',
                'updated_at' => '2024-11-10 13:20:22',
            ),
            9 => 
            array (
                'id' => 10,
                'name' => 'delete_any_asesor',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 13:20:22',
                'updated_at' => '2024-11-10 13:20:22',
            ),
            10 => 
            array (
                'id' => 11,
                'name' => 'force_delete_asesor',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 13:20:22',
                'updated_at' => '2024-11-10 13:20:22',
            ),
            11 => 
            array (
                'id' => 12,
                'name' => 'force_delete_any_asesor',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 13:20:22',
                'updated_at' => '2024-11-10 13:20:22',
            ),
            12 => 
            array (
                'id' => 13,
                'name' => 'view_cliente',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 13:20:22',
                'updated_at' => '2024-11-10 13:20:22',
            ),
            13 => 
            array (
                'id' => 14,
                'name' => 'view_any_cliente',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 13:20:22',
                'updated_at' => '2024-11-10 13:20:22',
            ),
            14 => 
            array (
                'id' => 15,
                'name' => 'create_cliente',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 13:20:22',
                'updated_at' => '2024-11-10 13:20:22',
            ),
            15 => 
            array (
                'id' => 16,
                'name' => 'update_cliente',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 13:20:22',
                'updated_at' => '2024-11-10 13:20:22',
            ),
            16 => 
            array (
                'id' => 17,
                'name' => 'restore_cliente',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 13:20:22',
                'updated_at' => '2024-11-10 13:20:22',
            ),
            17 => 
            array (
                'id' => 18,
                'name' => 'restore_any_cliente',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 13:20:22',
                'updated_at' => '2024-11-10 13:20:22',
            ),
            18 => 
            array (
                'id' => 19,
                'name' => 'replicate_cliente',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 13:20:22',
                'updated_at' => '2024-11-10 13:20:22',
            ),
            19 => 
            array (
                'id' => 20,
                'name' => 'reorder_cliente',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 13:20:22',
                'updated_at' => '2024-11-10 13:20:22',
            ),
            20 => 
            array (
                'id' => 21,
                'name' => 'delete_cliente',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 13:20:22',
                'updated_at' => '2024-11-10 13:20:22',
            ),
            21 => 
            array (
                'id' => 22,
                'name' => 'delete_any_cliente',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 13:20:22',
                'updated_at' => '2024-11-10 13:20:22',
            ),
            22 => 
            array (
                'id' => 23,
                'name' => 'force_delete_cliente',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 13:20:22',
                'updated_at' => '2024-11-10 13:20:22',
            ),
            23 => 
            array (
                'id' => 24,
                'name' => 'force_delete_any_cliente',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 13:20:22',
                'updated_at' => '2024-11-10 13:20:22',
            ),
            24 => 
            array (
                'id' => 25,
                'name' => 'view_cuenta::cliente',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 13:20:22',
                'updated_at' => '2024-11-10 13:20:22',
            ),
            25 => 
            array (
                'id' => 26,
                'name' => 'view_any_cuenta::cliente',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 13:20:22',
                'updated_at' => '2024-11-10 13:20:22',
            ),
            26 => 
            array (
                'id' => 27,
                'name' => 'create_cuenta::cliente',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 13:20:22',
                'updated_at' => '2024-11-10 13:20:22',
            ),
            27 => 
            array (
                'id' => 28,
                'name' => 'update_cuenta::cliente',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 13:20:22',
                'updated_at' => '2024-11-10 13:20:22',
            ),
            28 => 
            array (
                'id' => 29,
                'name' => 'restore_cuenta::cliente',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 13:20:22',
                'updated_at' => '2024-11-10 13:20:22',
            ),
            29 => 
            array (
                'id' => 30,
                'name' => 'restore_any_cuenta::cliente',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 13:20:22',
                'updated_at' => '2024-11-10 13:20:22',
            ),
            30 => 
            array (
                'id' => 31,
                'name' => 'replicate_cuenta::cliente',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 13:20:22',
                'updated_at' => '2024-11-10 13:20:22',
            ),
            31 => 
            array (
                'id' => 32,
                'name' => 'reorder_cuenta::cliente',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 13:20:22',
                'updated_at' => '2024-11-10 13:20:22',
            ),
            32 => 
            array (
                'id' => 33,
                'name' => 'delete_cuenta::cliente',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 13:20:22',
                'updated_at' => '2024-11-10 13:20:22',
            ),
            33 => 
            array (
                'id' => 34,
                'name' => 'delete_any_cuenta::cliente',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 13:20:22',
                'updated_at' => '2024-11-10 13:20:22',
            ),
            34 => 
            array (
                'id' => 35,
                'name' => 'force_delete_cuenta::cliente',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 13:20:22',
                'updated_at' => '2024-11-10 13:20:22',
            ),
            35 => 
            array (
                'id' => 36,
                'name' => 'force_delete_any_cuenta::cliente',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 13:20:22',
                'updated_at' => '2024-11-10 13:20:22',
            ),
            36 => 
            array (
                'id' => 37,
                'name' => 'view_movimiento',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 13:20:22',
                'updated_at' => '2024-11-10 13:20:22',
            ),
            37 => 
            array (
                'id' => 38,
                'name' => 'view_any_movimiento',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 13:20:22',
                'updated_at' => '2024-11-10 13:20:22',
            ),
            38 => 
            array (
                'id' => 39,
                'name' => 'create_movimiento',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 13:20:22',
                'updated_at' => '2024-11-10 13:20:22',
            ),
            39 => 
            array (
                'id' => 40,
                'name' => 'update_movimiento',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 13:20:22',
                'updated_at' => '2024-11-10 13:20:22',
            ),
            40 => 
            array (
                'id' => 41,
                'name' => 'restore_movimiento',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 13:20:22',
                'updated_at' => '2024-11-10 13:20:22',
            ),
            41 => 
            array (
                'id' => 42,
                'name' => 'restore_any_movimiento',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 13:20:22',
                'updated_at' => '2024-11-10 13:20:22',
            ),
            42 => 
            array (
                'id' => 43,
                'name' => 'replicate_movimiento',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 13:20:22',
                'updated_at' => '2024-11-10 13:20:22',
            ),
            43 => 
            array (
                'id' => 44,
                'name' => 'reorder_movimiento',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 13:20:22',
                'updated_at' => '2024-11-10 13:20:22',
            ),
            44 => 
            array (
                'id' => 45,
                'name' => 'delete_movimiento',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 13:20:22',
                'updated_at' => '2024-11-10 13:20:22',
            ),
            45 => 
            array (
                'id' => 46,
                'name' => 'delete_any_movimiento',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 13:20:22',
                'updated_at' => '2024-11-10 13:20:22',
            ),
            46 => 
            array (
                'id' => 47,
                'name' => 'force_delete_movimiento',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 13:20:22',
                'updated_at' => '2024-11-10 13:20:22',
            ),
            47 => 
            array (
                'id' => 48,
                'name' => 'force_delete_any_movimiento',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 13:20:22',
                'updated_at' => '2024-11-10 13:20:22',
            ),
            48 => 
            array (
                'id' => 49,
                'name' => 'view_role',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 13:20:22',
                'updated_at' => '2024-11-10 13:20:22',
            ),
            49 => 
            array (
                'id' => 50,
                'name' => 'view_any_role',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 13:20:22',
                'updated_at' => '2024-11-10 13:20:22',
            ),
            50 => 
            array (
                'id' => 51,
                'name' => 'create_role',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 13:20:22',
                'updated_at' => '2024-11-10 13:20:22',
            ),
            51 => 
            array (
                'id' => 52,
                'name' => 'update_role',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 13:20:22',
                'updated_at' => '2024-11-10 13:20:22',
            ),
            52 => 
            array (
                'id' => 53,
                'name' => 'delete_role',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 13:20:22',
                'updated_at' => '2024-11-10 13:20:22',
            ),
            53 => 
            array (
                'id' => 54,
                'name' => 'delete_any_role',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 13:20:22',
                'updated_at' => '2024-11-10 13:20:22',
            ),
            54 => 
            array (
                'id' => 55,
                'name' => 'view_seguimiento',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 13:20:22',
                'updated_at' => '2024-11-10 13:20:22',
            ),
            55 => 
            array (
                'id' => 56,
                'name' => 'view_any_seguimiento',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 13:20:22',
                'updated_at' => '2024-11-10 13:20:22',
            ),
            56 => 
            array (
                'id' => 57,
                'name' => 'create_seguimiento',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 13:20:22',
                'updated_at' => '2024-11-10 13:20:22',
            ),
            57 => 
            array (
                'id' => 58,
                'name' => 'update_seguimiento',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 13:20:22',
                'updated_at' => '2024-11-10 13:20:22',
            ),
            58 => 
            array (
                'id' => 59,
                'name' => 'restore_seguimiento',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 13:20:22',
                'updated_at' => '2024-11-10 13:20:22',
            ),
            59 => 
            array (
                'id' => 60,
                'name' => 'restore_any_seguimiento',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 13:20:22',
                'updated_at' => '2024-11-10 13:20:22',
            ),
            60 => 
            array (
                'id' => 61,
                'name' => 'replicate_seguimiento',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 13:20:22',
                'updated_at' => '2024-11-10 13:20:22',
            ),
            61 => 
            array (
                'id' => 62,
                'name' => 'reorder_seguimiento',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 13:20:22',
                'updated_at' => '2024-11-10 13:20:22',
            ),
            62 => 
            array (
                'id' => 63,
                'name' => 'delete_seguimiento',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 13:20:22',
                'updated_at' => '2024-11-10 13:20:22',
            ),
            63 => 
            array (
                'id' => 64,
                'name' => 'delete_any_seguimiento',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 13:20:22',
                'updated_at' => '2024-11-10 13:20:22',
            ),
            64 => 
            array (
                'id' => 65,
                'name' => 'force_delete_seguimiento',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 13:20:22',
                'updated_at' => '2024-11-10 13:20:22',
            ),
            65 => 
            array (
                'id' => 66,
                'name' => 'force_delete_any_seguimiento',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 13:20:22',
                'updated_at' => '2024-11-10 13:20:22',
            ),
            66 => 
            array (
                'id' => 67,
                'name' => 'view_user',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 13:20:22',
                'updated_at' => '2024-11-10 13:20:22',
            ),
            67 => 
            array (
                'id' => 68,
                'name' => 'view_any_user',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 13:20:22',
                'updated_at' => '2024-11-10 13:20:22',
            ),
            68 => 
            array (
                'id' => 69,
                'name' => 'create_user',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 13:20:22',
                'updated_at' => '2024-11-10 13:20:22',
            ),
            69 => 
            array (
                'id' => 70,
                'name' => 'update_user',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 13:20:22',
                'updated_at' => '2024-11-10 13:20:22',
            ),
            70 => 
            array (
                'id' => 71,
                'name' => 'restore_user',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 13:20:22',
                'updated_at' => '2024-11-10 13:20:22',
            ),
            71 => 
            array (
                'id' => 72,
                'name' => 'restore_any_user',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 13:20:22',
                'updated_at' => '2024-11-10 13:20:22',
            ),
            72 => 
            array (
                'id' => 73,
                'name' => 'replicate_user',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 13:20:22',
                'updated_at' => '2024-11-10 13:20:22',
            ),
            73 => 
            array (
                'id' => 74,
                'name' => 'reorder_user',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 13:20:22',
                'updated_at' => '2024-11-10 13:20:22',
            ),
            74 => 
            array (
                'id' => 75,
                'name' => 'delete_user',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 13:20:22',
                'updated_at' => '2024-11-10 13:20:22',
            ),
            75 => 
            array (
                'id' => 76,
                'name' => 'delete_any_user',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 13:20:22',
                'updated_at' => '2024-11-10 13:20:22',
            ),
            76 => 
            array (
                'id' => 77,
                'name' => 'force_delete_user',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 13:20:22',
                'updated_at' => '2024-11-10 13:20:22',
            ),
            77 => 
            array (
                'id' => 78,
                'name' => 'force_delete_any_user',
                'guard_name' => 'web',
                'created_at' => '2024-11-10 13:20:22',
                'updated_at' => '2024-11-10 13:20:22',
            ),
            78 => 
            array (
                'id' => 79,
                'name' => 'view_asignacion',
                'guard_name' => 'web',
                'created_at' => '2024-12-04 12:33:03',
                'updated_at' => '2024-12-04 12:33:03',
            ),
            79 => 
            array (
                'id' => 80,
                'name' => 'view_any_asignacion',
                'guard_name' => 'web',
                'created_at' => '2024-12-04 12:33:03',
                'updated_at' => '2024-12-04 12:33:03',
            ),
            80 => 
            array (
                'id' => 81,
                'name' => 'create_asignacion',
                'guard_name' => 'web',
                'created_at' => '2024-12-04 12:33:03',
                'updated_at' => '2024-12-04 12:33:03',
            ),
            81 => 
            array (
                'id' => 82,
                'name' => 'update_asignacion',
                'guard_name' => 'web',
                'created_at' => '2024-12-04 12:33:03',
                'updated_at' => '2024-12-04 12:33:03',
            ),
            82 => 
            array (
                'id' => 83,
                'name' => 'restore_asignacion',
                'guard_name' => 'web',
                'created_at' => '2024-12-04 12:33:03',
                'updated_at' => '2024-12-04 12:33:03',
            ),
            83 => 
            array (
                'id' => 84,
                'name' => 'restore_any_asignacion',
                'guard_name' => 'web',
                'created_at' => '2024-12-04 12:33:03',
                'updated_at' => '2024-12-04 12:33:03',
            ),
            84 => 
            array (
                'id' => 85,
                'name' => 'replicate_asignacion',
                'guard_name' => 'web',
                'created_at' => '2024-12-04 12:33:03',
                'updated_at' => '2024-12-04 12:33:03',
            ),
            85 => 
            array (
                'id' => 86,
                'name' => 'reorder_asignacion',
                'guard_name' => 'web',
                'created_at' => '2024-12-04 12:33:03',
                'updated_at' => '2024-12-04 12:33:03',
            ),
            86 => 
            array (
                'id' => 87,
                'name' => 'delete_asignacion',
                'guard_name' => 'web',
                'created_at' => '2024-12-04 12:33:03',
                'updated_at' => '2024-12-04 12:33:03',
            ),
            87 => 
            array (
                'id' => 88,
                'name' => 'delete_any_asignacion',
                'guard_name' => 'web',
                'created_at' => '2024-12-04 12:33:03',
                'updated_at' => '2024-12-04 12:33:03',
            ),
            88 => 
            array (
                'id' => 89,
                'name' => 'force_delete_asignacion',
                'guard_name' => 'web',
                'created_at' => '2024-12-04 12:33:03',
                'updated_at' => '2024-12-04 12:33:03',
            ),
            89 => 
            array (
                'id' => 90,
                'name' => 'force_delete_any_asignacion',
                'guard_name' => 'web',
                'created_at' => '2024-12-04 12:33:03',
                'updated_at' => '2024-12-04 12:33:03',
            ),
            90 => 
            array (
                'id' => 91,
                'name' => 'widget_AccountsTable',
                'guard_name' => 'web',
                'created_at' => '2024-12-04 12:33:03',
                'updated_at' => '2024-12-04 12:33:03',
            ),
        ));
        
        
    }
}