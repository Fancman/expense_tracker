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
			$table->string('name', 50);
			$table->decimal('quantity', $precision = 8, $scale = 2)->default(1);
			$table->decimal('average_buy_price', $precision = 8, $scale = 2)->nullable();
			$table->decimal('current_price', $precision = 8, $scale = 2)->nullable();
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
        Schema::dropIfExists('account_items');
    }
};
