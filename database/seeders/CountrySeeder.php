<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CountrySeeder extends Seeder {

    /**
     * Run the database seeds.
     */
    public function run(): void {
        $sql_path = base_path('database/seeds/countries.sql');
        $sql = File::get($sql_path);
        DB::unprepared($sql);
    }
}
