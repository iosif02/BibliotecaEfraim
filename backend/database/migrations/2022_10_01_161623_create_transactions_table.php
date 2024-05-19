<?php

use App\Models\Book;
use App\Models\User;
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
            $table->foreignIdFor(User::class)
                ->constrained()->onDelete('restrict')->onUpdate('restrict');
            $table->foreignIdFor(Book::class)
                ->constrained()->onDelete('restrict')->onUpdate('restrict');
            $table->dateTime('borrow_date');
            $table->boolean('is_returned')->default(false);
            $table->dateTime('return_date');
            $table->string('lender_name', 64);
            $table->string('receiver_name', 64)->default(false);
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
