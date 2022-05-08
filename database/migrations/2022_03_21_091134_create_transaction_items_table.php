<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Transaction;
use App\Models\ItemType;
use App\Models\Currency;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_items', function (Blueprint $table) {
            $table->id();
			$table->foreignIdFor(Transaction::class);
            $table->foreignIdFor(ItemType::class);
			$table->foreignIdFor(Currency::class);
			$table->foreignIdFor(Currency::class, 'fees_currency_id');
			$table->string('name', 50);
			$table->decimal('quantity', $precision = 8, $scale = 2)->default(1);
			$table->decimal('price', $precision = 8, $scale = 2)->nullable();
			$table->decimal('fees', $precision = 8, $scale = 2)->nullable();
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
        Schema::dropIfExists('transaction_items');
    }
};
