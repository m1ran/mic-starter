<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExpenseRequest;
use App\Http\Resources\ExpenseResource;
use App\Models\Expense;
use Illuminate\Http\Request;
use App\Repositories\ExpenseRepository;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ExpenseController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $expenses = Expense::orderBy('created_at', 'desc')
            ->paginate($request->input('per_page', 10));

        return ExpenseResource::collection($expenses);
    }

    public function show(Request $request, $id, ExpenseRepository $expenseRepository)
    {
        try {
            $expense = $expenseRepository->find($id);

            return ExpenseResource::make($expense);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Failed to load expense.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(ExpenseRequest $request, ExpenseRepository $expenseRepository)
    {
        try {
            $expense = $expenseRepository->create($request->validated(), $request->user()->id);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Failed to create expense.',
                'error' => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'message' => 'Expense created successfully.',
            'expense' => ExpenseResource::make($expense),
        ]);
    }

    public function update(ExpenseRequest $request, Expense $expense,  ExpenseRepository $expenseRepository)
    {
        $this->authorize('update', $expense);

        try {
            $expense = $expenseRepository->update($expense, $request->validated());
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Failed to update expense.',
                'error' => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'message' => 'Expense updated successfully.',
            'expense' => ExpenseResource::make($expense),
        ]);
    }
}
