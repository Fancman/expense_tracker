<?php

use App\Models\Account;
use App\Models\Currency;
use App\Models\ItemType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_items', function (Blueprint $table) {
            $table->id();
			$table->foreignIdFor(Account::class);
			$table->foreignIdFor(ItemType::class);
			$table->foreignIdFor(Currency::class);
			$table->string('name', 50)->unique();
			$table->unsignedInteger('quantity')->default(1);
			$table->string('average_buy_price', 50)->decimal('value', $precision = 8, $scale = 2)->nullable();
			$table->string('current_price', 50)->decimal('value', $precision = 8, $scale = 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_items');
    }
};
