<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class WalletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            Wallet::firstOrCreate(
                ['user_id' => $user->id, 'name' => 'Cash'],
                [
                    'uuid' => (string) Str::uuid(),
                    'type' => 'cash',
                    'balance' => 0,
                    'icon' => 'Banknote',
                    'color' => '#63d478',
                    'is_active' => true,
                ]
            );
        }
    }
}
