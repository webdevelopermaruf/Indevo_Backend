<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Goal extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'category',
        'deadline_date',
        'deadline_time',
        'note',
        'is_completed',
        'completion_date',
    ];
    protected static function booted(): void
    {
        static::addGlobalScope('user_goal', function ($builder) {
            $builder->where('user_id', auth()->id());
        });
    }
    #[Scope]
    protected function currentMonth(Builder $query):void
    {
        $query->whereYear('deadline_date', date('Y'))
            ->whereMonth('deadline_date', date('m'));
    }
    public function reminders(): HasMany
    {
        return $this->hasMany(Reminder::class, 'goal_id', 'id');
    }
    public function toResponseArray(): array
    {
        return [
            'id'              => $this->id,
            'title'           => $this->title,
            'category'        => $this->category,
            'deadline_date'   => $this->deadline_date,
            'deadline_time'   => $this->deadline_time,
            'completion_date' => $this->completion_date,
            'note'            => $this->note,
            'is_completed'    => $this->is_completed,
            'reminders'       => $this->reminders,
            'created_at'      => $this->created_at,
        ];
    }
}