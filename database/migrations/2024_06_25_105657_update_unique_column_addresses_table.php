<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUniqueColumnAddressesTable extends Migration
{
    public function up()
    {
        Schema::table('addresses', function (Blueprint $table) {
            if (!Schema::hasColumn('addresses', 'specified_in_order')) {
                $table->boolean('specified_in_order')->default(false);
            }

            $table->unique(['customer_id', 'type', 'specified_in_order'], 'unique_billing_address')->where('type', 'billing')->where('specified_in_order', false);
        });
    }

    public function down()
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->dropUnique('unique_billing_address');

            if (Schema::hasColumn('addresses', 'specified_in_order')) {
                $table->dropColumn('specified_in_order');
            }
        });
    }
}

