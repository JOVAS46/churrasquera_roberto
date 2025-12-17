<?php

namespace Database\Seeders;

use App\Models\Mesa;
use Illuminate\Database\Seeder;

class MesaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear 15 mesas
        for ($i = 1; $i <= 15; $i++) {
            Mesa::firstOrCreate(
                ['numero_mesa' => $i],
                [
                    'capacidad' => $i <= 5 ? 2 : ($i <= 10 ? 4 : 6),
                    'estado' => 'disponible',
                ]
            );
        }
    }
}
