<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create(config('rupiah.table_prefix').'banks', function ($table) {
            $table->id();
            $table->string('name', 100);
            $table->string('alt_name', 100)->nullable();
            $table->string('code', 5);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::drop(config('rupiah.table_prefix').'banks');
    }
};
