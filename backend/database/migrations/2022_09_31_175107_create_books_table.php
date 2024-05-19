<?php

use App\Models\Category;
use App\Models\Publisher;
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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->integer('year');
            $table->float('price')->nullable();
            $table->string('image')->nullable();
            $table->integer('quantity');
            $table->boolean('is_recommended')->default(false);
            $table->integer('order')->nullable();
            $table->boolean('is_marked')->nullable();
            $table->foreignIdFor(Category::class)
                ->constrained()->onDelete('restrict')->onUpdate('restrict');
            $table->foreignIdFor(Publisher::class)
                ->constrained()->onDelete('restrict')->onUpdate('restrict');
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
        Schema::dropIfExists('books');
    }
};
