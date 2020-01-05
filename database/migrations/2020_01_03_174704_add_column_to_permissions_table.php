<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->string('description')->after('name');
            $table->unsignedBigInteger('parent_id')->nullable()->after('description');
            $table->tinyInteger('order')->after('parent_id');
            $table->foreign('parent_id')->references('id')->on('permissions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->dropForeign('permissions_parent_id');
            $table->dropColumn('description');
            $table->dropColumn('parent_id');
            $table->dropColumn('order');
        });
    }
}
