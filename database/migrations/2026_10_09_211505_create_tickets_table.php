<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('agent_id')->nullable()->constrained('users')->nullOnDelete();
            // kategorii z przypisanymi zgłoszeniami nie można usunąć
            $table->foreignId('category_id')->constrained('categories')->restrictOnDelete();
            $table->string('title', 150);
            $table->text('description');
            $table->string('priority', 20)->default('Średni');
            $table->string('status', 30)->default('Nowe');
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('priority');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};