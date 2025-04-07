<?php

namespace App\Observers;

use App\Models\ExpenseItem;

class ExpenseItemObserver
{
    /**
     * Handle the ExpenseItem "creating" event.
     */
    public function creating(ExpenseItem $expenseItem): void
    {
        // Calculate the amount before saving
        $expenseItem->amount = $expenseItem->quantity * $expenseItem->price;
    }

    /**
     * Handle the ExpenseItem "updating" event.
     */
    public function updating(ExpenseItem $expenseItem): void
    {
        // Calculate the amount before saving
        $expenseItem->amount = $expenseItem->quantity * $expenseItem->price;
    }

    /**
     * Handle the ExpenseItem "created" event.
     */
    public function created(ExpenseItem $expenseItem): void
    {
        $this->recalculateTotal($expenseItem);
    }

    /**
     * Handle the ExpenseItem "updated" event.
     */
    public function updated(ExpenseItem $expenseItem): void
    {
        $this->recalculateTotal($expenseItem);
    }

    /**
     * Handle the ExpenseItem "deleted" event.
     */
    public function deleted(ExpenseItem $expenseItem): void
    {
        $this->recalculateTotal($expenseItem);
    }

    /**
     * Recalculate total amount for Expense.
     */
    protected function recalculateTotal(ExpenseItem $item)
    {
        $expense = $item->expense;

        $total = $expense->items()->sum('amount');
        $expense->update(['total_amount' => $total]);
    }
}
