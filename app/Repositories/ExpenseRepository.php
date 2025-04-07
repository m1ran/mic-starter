<?php

namespace App\Repositories;

use App\Models\Expense;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ExpenseRepository
{
    /**
     * Find an expense by ID.
     */
    public function find($id)
    {
        return Cache::remember("expense:{$id}", now()->addMinutes(10), function () use ($id) {
            return Expense::with('items')->findOrFail($id);
        });
    }

    /**
     * Create a new expense.
     *
     * NOTE: Added ExpenseItemObserver to handle the calculation of amount and total_amount
     */
    public function create(array $data,  int $userId): Expense
    {
        if (empty($data['items'])) {
            throw new \Exception('Expense must have at least one item.');
        }

        return DB::transaction(function () use ($data, $userId) {
            $expense = Expense::create([
                'date' => $data['date'],
                'comment' => $data['comment'] ?? null,
                'user_id' => $userId,
            ]);

            foreach ($data['items'] as $item) {
                $expense->items()->create([
                    'title' => $item['title'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            }

            return $expense->load('items');
        });
    }

    /**
     * Update an existing expense.
     *
     * NOTE: Added ExpenseItemObserver to handle the calculation of amount and total_amount
     */
    public function update(Expense $expense, array $data): Expense
    {
        if (empty($data['items'])) {
            throw new \Exception('Expense must have at least one item.');
        }

        return DB::transaction(function () use ($expense, $data) {
            $expense->update([
                'date' => $data['date'],
                'comment' => $data['comment'] ?? null,
            ]);
            // delete all existing items
            $expense->items()->delete();
            // create new items
            foreach ($data['items'] as $item) {
                $expense->items()->create([
                    'title' => $item['title'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            }

            Cache::forget("expense:{$expense->id}");

            return $expense->fresh('items');
        });
    }
}
