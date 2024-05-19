<?php

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
        Schema::create('user_details', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)
                ->constrained()->onDelete('restrict')->onUpdate('restrict');
            $table->string('identity_number', 16)->nullable();
            $table->string('address', 16)->nullable();
            $table->string('phone', 16);
            $table->string('occupation')->nullable();
            $table->date('birth_date');
            $table->string('photo_url')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_details');
    }
};
