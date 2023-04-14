<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $hasAdminRole = DB::table('roles')->where('name', 'admin')->exists();
        $hasUserRole = DB::table('roles')->where('name', 'user')->exists();
        if (!$hasAdminRole && !$hasUserRole) {
            DB::table('roles')->insert([
                ['name' => 'admin'],
                ['name' => 'user'],
            ]);
        }
    }
}
