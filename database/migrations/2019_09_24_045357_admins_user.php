<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Http\Controllers\Controller;
use App\Admin;
use Illuminate\Support\Facades\Hash;

class AdminsUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Admin::create([
            'email' => 'admin@task.local',
            'password' => Hash::make("1100"),
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Admin::all()->each->delete();
    }
}
