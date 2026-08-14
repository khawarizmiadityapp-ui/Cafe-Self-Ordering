<?php

namespace Database\Seeders;

use App\Models\Table;
use Illuminate\Database\Seeder;

class TableSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            $num = sprintf("%02d", $i);
            Table::updateOrCreate(
                ['table_number' => $num],
                [
                    'name' => "Meja $num",
                    'code' => $num,
                    'status' => 'active',
                ]
            );
        }
    }
}
