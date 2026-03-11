<?php

use App\Enums\ReminderCategories;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('description');
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->enum('category',   array_column(ReminderCategories::cases(),   'value'))->default(ReminderCategories::Other->value);
            $table->time('due_time')->default('12:00');
            $table->date('due_date');
            $table->enum('recurrence', ['once', 'daily', 'weekly', 'monthly'])->default('once');
            $table->string('place')->nullable();
            $table->text('note')->nullable();
            $table->boolean('is_completed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reminders');
    }
};
