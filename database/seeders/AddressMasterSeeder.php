<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddressMasterSeeder extends Seeder
{
    public function run(): void
    {
        $path = storage_path('app/indiapincode.csv');

        if (!file_exists($path)) {
            return;
        }

        $file = fopen($path, 'r');

        // Read header row and skip it
        fgetcsv($file);

        while (($row = fgetcsv($file)) !== false) {

            // Safety: skip invalid rows
            if (count($row) < 6) {
                continue;
            }

            DB::table('address_master')->insert([
                'village_name' => trim($row[0]),
                'pincode'      => trim($row[1]),
                'post_so_name' => trim($row[2]),
                'taluka_name'  => trim($row[3]),
                'District_name'=> trim($row[4]),
                'state_name'   => trim($row[5]),
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
        }

        fclose($file);
    }
}
