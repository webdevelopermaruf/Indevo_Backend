<?php

use App\Enums\SkillCategories;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rules\Enum;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('skills', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('achievement')->comment('icon'); // this will be using in user profile
            $table->string('poster')->nullable(); // primary poster of the skill
            $table->integer('duration')->comment('in minutes'); // how long would the skill takes to complete.
            $table->integer('reward')->comment('xp')->nullable(); // how much xp will be in user profile.
            $table->enum('difficulty', ['easy', 'medium', 'hard']);
            $table->enum('category', array_column(SkillCategories::cases(), 'value'))->default(SkillCategories::Other->value);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skills');
    }
};
