<?php

namespace App\Http\Controllers;

use App\Enums\ExpenseCategories;
use App\Http\Constants\HttpStatus;
use App\Http\Requests\ExpenseRequest;
use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    /**
     * Display this month expenses in the system.
     */
    public function index()
    {
        try {
            // Getting current month all expenses in descending order.
            $recent_expenses = Expense::current()->orderBy('created_at', 'desc')->get();

            // Getting total spent amount.
            $total = $recent_expenses->sum('amount');

            // Getting categories amounts separately
            $categories = [
                'food'             => $recent_expenses->where('category', ExpenseCategories::Food)->sum('amount'),
                'transport'        => $recent_expenses->where('category', ExpenseCategories::Transport)->sum('amount'),
                'bills'            => $recent_expenses->where('category', ExpenseCategories::Bills)->sum('amount'),
                'health_entertain' => $recent_expenses->whereIn('category', [ExpenseCategories::Entertainment, ExpenseCategories::Health])->sum('amount'),
                'other'            => $recent_expenses->where('category', ExpenseCategories::Other)->sum('amount'),
            ];

            $breakdown = collect($categories)->map(fn($amount) => [
                'amount'     => $amount,
                'percentage' => $total > 0 ? round(($amount / $total) * 100, 1) : 0,
            ]);
            $data = [
                'dashboard_expense' => [
                    'total_spent' => $total,
                    'breakdown'   => $breakdown,
                ],
                'recent_expenses' => $recent_expenses,
            ];

            return $this->success('User Expenses', $data);
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage(), null, HttpStatus::INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Inserting new expenses in the system.
     */
    public function store(ExpenseRequest $request)
    {
        try {
            $req = $request->validated();
            $expense = Expense::create([
                ...$req,
                'user_id' => auth()->id(),
            ]);
            return $this->success('Expense created', $expense->toResponseArray(), HttpStatus::CREATED);
        }catch (\Exception $exception){
            return $this->error($exception->getMessage(), null, HttpStatus::INTERNAL_SERVER_ERROR);
        }
    }
}
