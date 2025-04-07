<?php

namespace Database\Seeders;

use App\Models\Expense;
use App\Models\ExpenseItem;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StartDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory(10)->create()->each(function ($user) {
            Expense::factory(rand(1,  5))->create([
                'user_id' => $user->id,
            ])->each(function ($expense) {
                $items = ExpenseItem::factory(rand(1, 5))->create([
                    'expense_id' => $expense->id,
                ]);

                foreach ($items as $item) {
                    $item->save();
                }

                $expense->save();
            });
        });
    }
}
