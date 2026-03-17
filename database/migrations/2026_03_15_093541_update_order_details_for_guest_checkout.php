<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('order_details', function (Blueprint $table) {
            if (!Schema::hasColumn('order_details', 'order_id')) {
                $table->foreignId('order_id')->nullable()->constrained('orders')->cascadeOnDelete();
            }
            if (!Schema::hasColumn('order_details', 'email')) {
                $table->string('email')->nullable();
            }
            $table->string('address2', 255)->nullable()->change();
            $table->string('zipcode', 45)->nullable()->change();
            $table->string('country_code', 3)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('order_details', function (Blueprint $table) {
            if (Schema::hasColumn('order_details', 'order_id')) {
                $table->dropForeign(['order_id']);
                $table->dropColumn('order_id');
            }
            if (Schema::hasColumn('order_details', 'email')) {
                $table->dropColumn('email');
            }
            $table->string('address2', 255)->nullable(false)->change();
            $table->string('zipcode', 45)->nullable(false)->change();
            $table->string('country_code', 3)->nullable(false)->change();
        });
    }
};
