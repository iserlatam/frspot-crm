<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RoleHasPermissionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('role_has_permissions')->delete();

        \DB::table('role_has_permissions')->insert(array (
            0 =>
            array (
                'permission_id' => 1,
                'role_id' => 1,
            ),
            1 =>
            array (
                'permission_id' => 1,
                'role_id' => 3,
            ),
            2 =>
            array (
                'permission_id' => 1,
                'role_id' => 4,
            ),
            3 =>
            array (
                'permission_id' => 2,
                'role_id' => 1,
            ),
            4 =>
            array (
                'permission_id' => 2,
                'role_id' => 3,
            ),
            5 =>
            array (
                'permission_id' => 2,
                'role_id' => 4,
            ),
            6 =>
            array (
                'permission_id' => 3,
                'role_id' => 1,
            ),
            7 =>
            array (
                'permission_id' => 3,
                'role_id' => 4,
            ),
            8 =>
            array (
                'permission_id' => 4,
                'role_id' => 1,
            ),
            9 =>
            array (
                'permission_id' => 4,
                'role_id' => 3,
            ),
            10 =>
            array (
                'permission_id' => 4,
                'role_id' => 4,
            ),
            11 =>
            array (
                'permission_id' => 5,
                'role_id' => 1,
            ),
            12 =>
            array (
                'permission_id' => 5,
                'role_id' => 4,
            ),
            13 =>
            array (
                'permission_id' => 6,
                'role_id' => 1,
            ),
            14 =>
            array (
                'permission_id' => 6,
                'role_id' => 4,
            ),
            15 =>
            array (
                'permission_id' => 7,
                'role_id' => 1,
            ),
            16 =>
            array (
                'permission_id' => 7,
                'role_id' => 4,
            ),
            17 =>
            array (
                'permission_id' => 8,
                'role_id' => 1,
            ),
            18 =>
            array (
                'permission_id' => 8,
                'role_id' => 3,
            ),
            19 =>
            array (
                'permission_id' => 8,
                'role_id' => 4,
            ),
            20 =>
            array (
                'permission_id' => 9,
                'role_id' => 1,
            ),
            21 =>
            array (
                'permission_id' => 9,
                'role_id' => 4,
            ),
            22 =>
            array (
                'permission_id' => 10,
                'role_id' => 1,
            ),
            23 =>
            array (
                'permission_id' => 10,
                'role_id' => 4,
            ),
            24 =>
            array (
                'permission_id' => 11,
                'role_id' => 1,
            ),
            25 =>
            array (
                'permission_id' => 11,
                'role_id' => 4,
            ),
            26 =>
            array (
                'permission_id' => 12,
                'role_id' => 1,
            ),
            27 =>
            array (
                'permission_id' => 12,
                'role_id' => 4,
            ),
            28 =>
            array (
                'permission_id' => 13,
                'role_id' => 1,
            ),
            29 =>
            array (
                'permission_id' => 13,
                'role_id' => 2,
            ),
            30 =>
            array (
                'permission_id' => 13,
                'role_id' => 3,
            ),
            31 =>
            array (
                'permission_id' => 13,
                'role_id' => 4,
            ),
            32 =>
            array (
                'permission_id' => 14,
                'role_id' => 1,
            ),
            33 =>
            array (
                'permission_id' => 14,
                'role_id' => 2,
            ),
            34 =>
            array (
                'permission_id' => 14,
                'role_id' => 3,
            ),
            35 =>
            array (
                'permission_id' => 14,
                'role_id' => 4,
            ),
            36 =>
            array (
                'permission_id' => 15,
                'role_id' => 1,
            ),
            37 =>
            array (
                'permission_id' => 15,
                'role_id' => 4,
            ),
            38 =>
            array (
                'permission_id' => 16,
                'role_id' => 1,
            ),
            39 =>
            array (
                'permission_id' => 16,
                'role_id' => 2,
            ),
            40 =>
            array (
                'permission_id' => 16,
                'role_id' => 3,
            ),
            41 =>
            array (
                'permission_id' => 16,
                'role_id' => 4,
            ),
            42 =>
            array (
                'permission_id' => 17,
                'role_id' => 1,
            ),
            43 =>
            array (
                'permission_id' => 17,
                'role_id' => 4,
            ),
            44 =>
            array (
                'permission_id' => 18,
                'role_id' => 1,
            ),
            45 =>
            array (
                'permission_id' => 18,
                'role_id' => 4,
            ),
            46 =>
            array (
                'permission_id' => 19,
                'role_id' => 1,
            ),
            47 =>
            array (
                'permission_id' => 19,
                'role_id' => 4,
            ),
            48 =>
            array (
                'permission_id' => 20,
                'role_id' => 1,
            ),
            49 =>
            array (
                'permission_id' => 20,
                'role_id' => 3,
            ),
            50 =>
            array (
                'permission_id' => 20,
                'role_id' => 4,
            ),
            51 =>
            array (
                'permission_id' => 21,
                'role_id' => 1,
            ),
            52 =>
            array (
                'permission_id' => 21,
                'role_id' => 4,
            ),
            53 =>
            array (
                'permission_id' => 22,
                'role_id' => 1,
            ),
            54 =>
            array (
                'permission_id' => 22,
                'role_id' => 4,
            ),
            55 =>
            array (
                'permission_id' => 23,
                'role_id' => 1,
            ),
            56 =>
            array (
                'permission_id' => 23,
                'role_id' => 4,
            ),
            57 =>
            array (
                'permission_id' => 24,
                'role_id' => 1,
            ),
            58 =>
            array (
                'permission_id' => 24,
                'role_id' => 4,
            ),
            59 =>
            array (
                'permission_id' => 25,
                'role_id' => 1,
            ),
            60 =>
            array (
                'permission_id' => 25,
                'role_id' => 2,
            ),
            61 =>
            array (
                'permission_id' => 25,
                'role_id' => 3,
            ),
            62 =>
            array (
                'permission_id' => 25,
                'role_id' => 4,
            ),
            63 =>
            array (
                'permission_id' => 26,
                'role_id' => 1,
            ),
            64 =>
            array (
                'permission_id' => 26,
                'role_id' => 2,
            ),
            65 =>
            array (
                'permission_id' => 26,
                'role_id' => 3,
            ),
            66 =>
            array (
                'permission_id' => 26,
                'role_id' => 4,
            ),
            67 =>
            array (
                'permission_id' => 27,
                'role_id' => 1,
            ),
            68 =>
            array (
                'permission_id' => 27,
                'role_id' => 4,
            ),
            69 =>
            array (
                'permission_id' => 28,
                'role_id' => 1,
            ),
            70 =>
            array (
                'permission_id' => 28,
                'role_id' => 2,
            ),
            71 =>
            array (
                'permission_id' => 28,
                'role_id' => 4,
            ),
            72 =>
            array (
                'permission_id' => 29,
                'role_id' => 1,
            ),
            73 =>
            array (
                'permission_id' => 29,
                'role_id' => 4,
            ),
            74 =>
            array (
                'permission_id' => 30,
                'role_id' => 1,
            ),
            75 =>
            array (
                'permission_id' => 30,
                'role_id' => 4,
            ),
            76 =>
            array (
                'permission_id' => 31,
                'role_id' => 1,
            ),
            77 =>
            array (
                'permission_id' => 31,
                'role_id' => 4,
            ),
            78 =>
            array (
                'permission_id' => 32,
                'role_id' => 1,
            ),
            79 =>
            array (
                'permission_id' => 32,
                'role_id' => 4,
            ),
            80 =>
            array (
                'permission_id' => 33,
                'role_id' => 1,
            ),
            81 =>
            array (
                'permission_id' => 33,
                'role_id' => 4,
            ),
            82 =>
            array (
                'permission_id' => 34,
                'role_id' => 1,
            ),
            83 =>
            array (
                'permission_id' => 34,
                'role_id' => 4,
            ),
            84 =>
            array (
                'permission_id' => 35,
                'role_id' => 1,
            ),
            85 =>
            array (
                'permission_id' => 35,
                'role_id' => 4,
            ),
            86 =>
            array (
                'permission_id' => 36,
                'role_id' => 1,
            ),
            87 =>
            array (
                'permission_id' => 36,
                'role_id' => 4,
            ),
            88 =>
            array (
                'permission_id' => 37,
                'role_id' => 1,
            ),
            89 =>
            array (
                'permission_id' => 37,
                'role_id' => 2,
            ),
            90 =>
            array (
                'permission_id' => 37,
                'role_id' => 4,
            ),
            91 =>
            array (
                'permission_id' => 38,
                'role_id' => 1,
            ),
            92 =>
            array (
                'permission_id' => 38,
                'role_id' => 2,
            ),
            93 =>
            array (
                'permission_id' => 38,
                'role_id' => 4,
            ),
            94 =>
            array (
                'permission_id' => 39,
                'role_id' => 1,
            ),
            95 =>
            array (
                'permission_id' => 39,
                'role_id' => 2,
            ),
            96 =>
            array (
                'permission_id' => 39,
                'role_id' => 4,
            ),
            97 =>
            array (
                'permission_id' => 40,
                'role_id' => 1,
            ),
            98 =>
            array (
                'permission_id' => 40,
                'role_id' => 4,
            ),
            99 =>
            array (
                'permission_id' => 41,
                'role_id' => 1,
            ),
            100 =>
            array (
                'permission_id' => 41,
                'role_id' => 4,
            ),
            101 =>
            array (
                'permission_id' => 42,
                'role_id' => 1,
            ),
            102 =>
            array (
                'permission_id' => 42,
                'role_id' => 4,
            ),
            103 =>
            array (
                'permission_id' => 43,
                'role_id' => 1,
            ),
            104 =>
            array (
                'permission_id' => 43,
                'role_id' => 4,
            ),
            105 =>
            array (
                'permission_id' => 44,
                'role_id' => 1,
            ),
            106 =>
            array (
                'permission_id' => 44,
                'role_id' => 4,
            ),
            107 =>
            array (
                'permission_id' => 45,
                'role_id' => 1,
            ),
            108 =>
            array (
                'permission_id' => 45,
                'role_id' => 4,
            ),
            109 =>
            array (
                'permission_id' => 46,
                'role_id' => 1,
            ),
            110 =>
            array (
                'permission_id' => 46,
                'role_id' => 4,
            ),
            111 =>
            array (
                'permission_id' => 47,
                'role_id' => 1,
            ),
            112 =>
            array (
                'permission_id' => 47,
                'role_id' => 4,
            ),
            113 =>
            array (
                'permission_id' => 48,
                'role_id' => 1,
            ),
            114 =>
            array (
                'permission_id' => 48,
                'role_id' => 4,
            ),
            115 =>
            array (
                'permission_id' => 49,
                'role_id' => 1,
            ),
            116 =>
            array (
                'permission_id' => 49,
                'role_id' => 4,
            ),
            117 =>
            array (
                'permission_id' => 50,
                'role_id' => 1,
            ),
            118 =>
            array (
                'permission_id' => 50,
                'role_id' => 4,
            ),
            119 =>
            array (
                'permission_id' => 51,
                'role_id' => 1,
            ),
            120 =>
            array (
                'permission_id' => 51,
                'role_id' => 4,
            ),
            121 =>
            array (
                'permission_id' => 52,
                'role_id' => 1,
            ),
            122 =>
            array (
                'permission_id' => 52,
                'role_id' => 4,
            ),
            123 =>
            array (
                'permission_id' => 53,
                'role_id' => 1,
            ),
            124 =>
            array (
                'permission_id' => 53,
                'role_id' => 4,
            ),
            125 =>
            array (
                'permission_id' => 54,
                'role_id' => 1,
            ),
            126 =>
            array (
                'permission_id' => 54,
                'role_id' => 4,
            ),
            127 =>
            array (
                'permission_id' => 55,
                'role_id' => 1,
            ),
            128 =>
            array (
                'permission_id' => 55,
                'role_id' => 2,
            ),
            129 =>
            array (
                'permission_id' => 55,
                'role_id' => 3,
            ),
            130 =>
            array (
                'permission_id' => 55,
                'role_id' => 4,
            ),
            131 =>
            array (
                'permission_id' => 56,
                'role_id' => 1,
            ),
            132 =>
            array (
                'permission_id' => 56,
                'role_id' => 2,
            ),
            133 =>
            array (
                'permission_id' => 56,
                'role_id' => 3,
            ),
            134 =>
            array (
                'permission_id' => 56,
                'role_id' => 4,
            ),
            135 =>
            array (
                'permission_id' => 57,
                'role_id' => 1,
            ),
            136 =>
            array (
                'permission_id' => 57,
                'role_id' => 3,
            ),
            137 =>
            array (
                'permission_id' => 57,
                'role_id' => 4,
            ),
            138 =>
            array (
                'permission_id' => 58,
                'role_id' => 1,
            ),
            139 =>
            array (
                'permission_id' => 58,
                'role_id' => 3,
            ),
            140 =>
            array (
                'permission_id' => 58,
                'role_id' => 4,
            ),
            141 =>
            array (
                'permission_id' => 59,
                'role_id' => 1,
            ),
            142 =>
            array (
                'permission_id' => 59,
                'role_id' => 4,
            ),
            143 =>
            array (
                'permission_id' => 60,
                'role_id' => 1,
            ),
            144 =>
            array (
                'permission_id' => 60,
                'role_id' => 4,
            ),
            145 =>
            array (
                'permission_id' => 61,
                'role_id' => 1,
            ),
            146 =>
            array (
                'permission_id' => 61,
                'role_id' => 4,
            ),
            147 =>
            array (
                'permission_id' => 62,
                'role_id' => 1,
            ),
            148 =>
            array (
                'permission_id' => 62,
                'role_id' => 3,
            ),
            149 =>
            array (
                'permission_id' => 62,
                'role_id' => 4,
            ),
            150 =>
            array (
                'permission_id' => 63,
                'role_id' => 1,
            ),
            151 =>
            array (
                'permission_id' => 63,
                'role_id' => 4,
            ),
            152 =>
            array (
                'permission_id' => 64,
                'role_id' => 1,
            ),
            153 =>
            array (
                'permission_id' => 64,
                'role_id' => 4,
            ),
            154 =>
            array (
                'permission_id' => 65,
                'role_id' => 1,
            ),
            155 =>
            array (
                'permission_id' => 65,
                'role_id' => 4,
            ),
            156 =>
            array (
                'permission_id' => 66,
                'role_id' => 1,
            ),
            157 =>
            array (
                'permission_id' => 66,
                'role_id' => 4,
            ),
            158 =>
            array (
                'permission_id' => 67,
                'role_id' => 1,
            ),
            159 =>
            array (
                'permission_id' => 67,
                'role_id' => 2,
            ),
            160 =>
            array (
                'permission_id' => 67,
                'role_id' => 3,
            ),
            161 =>
            array (
                'permission_id' => 67,
                'role_id' => 4,
            ),
            162 =>
            array (
                'permission_id' => 68,
                'role_id' => 1,
            ),
            163 =>
            array (
                'permission_id' => 68,
                'role_id' => 2,
            ),
            164 =>
            array (
                'permission_id' => 68,
                'role_id' => 3,
            ),
            165 =>
            array (
                'permission_id' => 68,
                'role_id' => 4,
            ),
            166 =>
            array (
                'permission_id' => 69,
                'role_id' => 1,
            ),
            167 =>
            array (
                'permission_id' => 69,
                'role_id' => 4,
            ),
            168 =>
            array (
                'permission_id' => 70,
                'role_id' => 1,
            ),
            169 =>
            array (
                'permission_id' => 70,
                'role_id' => 2,
            ),
            170 =>
            array (
                'permission_id' => 70,
                'role_id' => 3,
            ),
            171 =>
            array (
                'permission_id' => 70,
                'role_id' => 4,
            ),
            172 =>
            array (
                'permission_id' => 71,
                'role_id' => 1,
            ),
            173 =>
            array (
                'permission_id' => 71,
                'role_id' => 4,
            ),
            174 =>
            array (
                'permission_id' => 72,
                'role_id' => 1,
            ),
            175 =>
            array (
                'permission_id' => 72,
                'role_id' => 4,
            ),
            176 =>
            array (
                'permission_id' => 73,
                'role_id' => 1,
            ),
            177 =>
            array (
                'permission_id' => 73,
                'role_id' => 4,
            ),
            178 =>
            array (
                'permission_id' => 74,
                'role_id' => 1,
            ),
            179 =>
            array (
                'permission_id' => 74,
                'role_id' => 3,
            ),
            180 =>
            array (
                'permission_id' => 74,
                'role_id' => 4,
            ),
            181 =>
            array (
                'permission_id' => 75,
                'role_id' => 1,
            ),
            182 =>
            array (
                'permission_id' => 75,
                'role_id' => 4,
            ),
            183 =>
            array (
                'permission_id' => 76,
                'role_id' => 1,
            ),
            184 =>
            array (
                'permission_id' => 76,
                'role_id' => 4,
            ),
            185 =>
            array (
                'permission_id' => 77,
                'role_id' => 1,
            ),
            186 =>
            array (
                'permission_id' => 77,
                'role_id' => 4,
            ),
            187 =>
            array (
                'permission_id' => 78,
                'role_id' => 1,
            ),
            188 =>
            array (
                'permission_id' => 78,
                'role_id' => 4,
            ),
            189 =>
            array (
                'permission_id' => 79,
                'role_id' => 1,
            ),
            190 =>
            array (
                'permission_id' => 79,
                'role_id' => 2,
            ),
            191 =>
            array (
                'permission_id' => 79,
                'role_id' => 3,
            ),
            192 =>
            array (
                'permission_id' => 79,
                'role_id' => 4,
            ),
            193 =>
            array (
                'permission_id' => 80,
                'role_id' => 1,
            ),
            194 =>
            array (
                'permission_id' => 80,
                'role_id' => 3,
            ),
            195 =>
            array (
                'permission_id' => 80,
                'role_id' => 4,
            ),
            196 =>
            array (
                'permission_id' => 81,
                'role_id' => 1,
            ),
            197 =>
            array (
                'permission_id' => 81,
                'role_id' => 4,
            ),
            198 =>
            array (
                'permission_id' => 82,
                'role_id' => 1,
            ),
            199 =>
            array (
                'permission_id' => 82,
                'role_id' => 4,
            ),
            200 =>
            array (
                'permission_id' => 83,
                'role_id' => 1,
            ),
            201 =>
            array (
                'permission_id' => 83,
                'role_id' => 4,
            ),
            202 =>
            array (
                'permission_id' => 84,
                'role_id' => 1,
            ),
            203 =>
            array (
                'permission_id' => 84,
                'role_id' => 4,
            ),
            204 =>
            array (
                'permission_id' => 85,
                'role_id' => 1,
            ),
            205 =>
            array (
                'permission_id' => 85,
                'role_id' => 4,
            ),
            206 =>
            array (
                'permission_id' => 86,
                'role_id' => 1,
            ),
            207 =>
            array (
                'permission_id' => 86,
                'role_id' => 3,
            ),
            208 =>
            array (
                'permission_id' => 86,
                'role_id' => 4,
            ),
            209 =>
            array (
                'permission_id' => 87,
                'role_id' => 1,
            ),
            210 =>
            array (
                'permission_id' => 87,
                'role_id' => 4,
            ),
            211 =>
            array (
                'permission_id' => 88,
                'role_id' => 1,
            ),
            212 =>
            array (
                'permission_id' => 88,
                'role_id' => 4,
            ),
            213 =>
            array (
                'permission_id' => 89,
                'role_id' => 1,
            ),
            214 =>
            array (
                'permission_id' => 89,
                'role_id' => 4,
            ),
            215 =>
            array (
                'permission_id' => 90,
                'role_id' => 1,
            ),
            216 =>
            array (
                'permission_id' => 90,
                'role_id' => 4,
            ),
            217 =>
            array (
                'permission_id' => 91,
                'role_id' => 1,
            ),
            218 =>
            array (
                'permission_id' => 91,
                'role_id' => 4,
            ),
        ));


    }
}
