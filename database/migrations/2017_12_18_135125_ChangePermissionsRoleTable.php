<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangePermissionsRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('permissions_role', function (Blueprint $table) {
            $table->integer('role_id')->unsigned()->default(1);
            $table->foreign('role_id')->references('id')->on('roles');

            $table->integer('permissions_id')->unsigned()->default(1);
            $table->foreign('permissions_id')->references('id')->on('permissions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_permissions_role', function (Blueprint $table) {
            //
        });
    }
}
