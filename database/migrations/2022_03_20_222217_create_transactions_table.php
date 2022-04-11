<?php

use App\Models\User;
use App\Models\Account;
use App\Models\AddressBook;
use App\Models\Currency;
use App\Models\TransactionType;
use App\Models\Category;
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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class);
            $table->foreignIdFor(Category::class)->nullable();
            $table->foreignIdFor(TransactionType::class);
            $table->foreignIdFor(Currency::class);
            $table->foreignIdFor(AddressBook::class)->nullable();
            $table->foreignIdFor(Account::class, 'source_account_id')->nullable();
            $table->foreignIdFor(Account::class, 'end_account_id')->nullable();
			$table->timestamp('transaction_time', $precision = 0)->nullable()->useCurrent();
			$table->string('name', 50);
			$table->decimal('value', $precision = 8, $scale = 2);
			$table->string('repeating', 20)->nullable();
			$table->boolean('paid')->default(false)->nullable();
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
        Schema::dropIfExists('transactions');
    }
};
