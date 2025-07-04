<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoginsTable extends Migration
{
    public function up()
    {
        Schema::create('logins', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('email')->unique(); // Unique email
            $table->string('password'); // Hashed password
            $table->string('name')->nullable(); // Optional full name
            $table->string('profile')->nullable(); // ✅ Image URL from internet
            $table->timestamps(); // created_at & updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('logins');
    }
}
