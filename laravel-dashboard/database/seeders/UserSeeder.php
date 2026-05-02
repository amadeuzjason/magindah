<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Load signatures
        $sigPath = storage_path('app/signatures.json');
        $sigs = file_exists($sigPath) ? json_decode(file_get_contents($sigPath), true) : [];

        $users = [
            [
                'name' => 'Admin System',
                'username' => 'admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('admin123'),
                'jabatan' => 'Administrator',
                'signature' => null
            ],
            [
                'name' => 'NOP Palu User',
                'username' => 'NOP-PALU',
                'email' => 'nop.palu@example.com',
                'password' => Hash::make('palu123'),
                'jabatan' => 'User NOP Palu',
                'signature' => null
            ],
            [
                'name' => 'Manager NOP Makassar',
                'username' => 'NOP-MKS',
                'email' => 'amadeusgwh1705@gmail.com',
                'password' => Hash::make('nop123'),
                'jabatan' => 'Manager NOP Makassar',
                'signature' => $sigs['manager_nop'] ?? null
            ],
            [
                'name' => 'Manager SQA Sulawesi',
                'username' => 'manager_sqa',
                'email' => 'ggwpblackhole@gmail.com',
                'password' => Hash::make('sqa123'),
                'jabatan' => 'Manager SQA Sulawesi',
                'signature' => $sigs['manager_sqa'] ?? null
            ],
            [
                'name' => 'Manager MBA Sulawesi',
                'username' => 'manager_mba',
                'email' => 'ggwpblackhole@gmail.com',
                'password' => Hash::make('mba123'),
                'jabatan' => 'Manager MBA Sulawesi',
                'signature' => $sigs['manager_mba'] ?? null
            ],
            [
                'name' => 'Manager NOS Sulawesi',
                'username' => 'manager_nos',
                'email' => 'ggwpblackhole@gmail.com',
                'password' => Hash::make('nos123'),
                'jabatan' => 'Manager NOS Sulawesi',
                'signature' => $sigs['manager_nos'] ?? null
            ],
            [
                'name' => 'GM RNOP Sulawesi',
                'username' => 'manager_gm',
                'email' => 'ggwpblackhole@gmail.com',
                'password' => Hash::make('gm123'),
                'jabatan' => 'GM RNOP Sulawesi',
                'signature' => $sigs['manager_gm'] ?? null
            ]
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['username' => $userData['username']],
                $userData
            );
        }
    }
}
