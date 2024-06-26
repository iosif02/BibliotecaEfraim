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
            $table->integer('year')->nullable();
            $table->float('price')->nullable();
            $table->string('image')->nullable();
            $table->integer('quantity')->nullable();
            $table->boolean('is_recommended')->default(false);
            $table->integer('order')->nullable();
            $table->foreignIdFor(Category::class)->nullable()
                ->constrained()->onDelete('restrict')->onUpdate('restrict');
            $table->foreignIdFor(Publisher::class)->nullable()
                ->constrained()->onDelete('restrict')->onUpdate('restrict');
            $table->string('mark', 64);
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
