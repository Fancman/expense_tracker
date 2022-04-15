<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\User;
use App\Models\Category;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
			$table->foreignIdFor(User::class);
			$table->foreignIdFor(Category::class);
			$table->timestamp('start_time', $precision = 0);
			$table->string('budget_period', 50);
            $table->decimal('amount', $precision = 8, $scale = 2)->nullable();
			$table->boolean('reached')->default(false)->nullable();
			$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('budgets');
    }
};
