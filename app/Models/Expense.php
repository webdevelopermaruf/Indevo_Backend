<?php

namespace App\Models;

use App\Enums\ExpenseCategories;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Expense extends Model
{
    protected $fillable = [
        'user_id',
        'description',
        'category',
        'amount',
        'currency',
        'recurring_type',
        'expense_date',
        'note'
    ];

    protected $hidden = [
        'user_id',
    ];

    protected $casts = [
        'category' => ExpenseCategories::class,
        'amount' => 'decimal:2',
        'expense_date' => 'date',
    ];

    #[Scope]
    protected function current(Builder $query): void
    {
        $query->whereYear('expense_date', date('Y'))
            ->whereMonth('expense_date', date('m'));
    }

    protected static function booted(): void
    {
        static::addGlobalScope('user_expense', function ($builder) {
            $builder->where('user_id', auth()->id());
        });
    }

    // app/Models/Expense.php

    public function toResponseArray(): array
    {
        return [
            'id'             => $this->id,
            'description'    => $this->description,
            'category'       => $this->category,
            'amount'         => $this->amount,
            'currency'       => $this->currency,
            'recurring_type' => $this->recurring_type,
            'expense_date'   => $this->expense_date,
            'note'          => $this->note,
            'created_at'     => $this->created_at,
        ];
    }

}
