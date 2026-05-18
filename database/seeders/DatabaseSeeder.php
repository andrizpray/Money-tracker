<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::create([
            'name' => 'User',
            'email' => 'user@money.id',
            'password' => Hash::make('password'),
            'currency' => 'IDR',
        ]);

        $defaultCategories = [
            // Expense
            ['name' => 'Makanan & Minuman', 'type' => 'expense', 'icon' => '🍔', 'color' => '#ef4444'],
            ['name' => 'Transportasi', 'type' => 'expense', 'icon' => '🚗', 'color' => '#f59e0b'],
            ['name' => 'Belanja', 'type' => 'expense', 'icon' => '🛒', 'color' => '#ec4899'],
            ['name' => 'Tagihan & Utilitas', 'type' => 'expense', 'icon' => '💡', 'color' => '#8b5cf6'],
            ['name' => 'Hiburan', 'type' => 'expense', 'icon' => '🎮', 'color' => '#06b6d4'],
            ['name' => 'Kesehatan', 'type' => 'expense', 'icon' => '💊', 'color' => '#10b981'],
            ['name' => 'Pendidikan', 'type' => 'expense', 'icon' => '📚', 'color' => '#6366f1'],
            ['name' => 'Lainnya', 'type' => 'expense', 'icon' => '📦', 'color' => '#64748b'],
            // Income
            ['name' => 'Gaji', 'type' => 'income', 'icon' => '💰', 'color' => '#22c55e'],
            ['name' => 'Freelance', 'type' => 'income', 'icon' => '💻', 'color' => '#14b8a6'],
            ['name' => 'Investasi', 'type' => 'income', 'icon' => '📈', 'color' => '#3b82f6'],
            ['name' => 'Lainnya', 'type' => 'income', 'icon' => '💵', 'color' => '#a855f7'],
        ];

        foreach ($defaultCategories as $cat) {
            Category::create(array_merge($cat, [
                'user_id' => $user->id,
                'is_default' => true,
            ]));
        }
    }
}
