<?php

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
        Schema::create('galleries', function (Blueprint $table) {
            $table->id(); // Auto-incrementing ID
            $table->enum('category', ['media', 'photos', 'team', 'about'])->default('media');
            $table->string('name');
            $table->string('about_us');
            $table->boolean('status')->default(true);
            $table->integer('added_by')->nullable(); 
            $table->string('request_ip')->nullable(); 
            $table->timestamps(); 
        });
    }

    public function down()
    {
      
    }
};
