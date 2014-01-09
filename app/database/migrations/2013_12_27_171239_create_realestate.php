<?php
use Illuminate\Database\Migrations\Migration;

class CreateRealestate extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('realestates', function($table)
		{
			$table->increments('re_id');
	        $table->string('re_address1', 128);
	        $table->string('re_address2', 128)->nullable();
	        $table->string('re_city', 128)->nullable();
	        $table->string('re_state', 20)->nullable();
	        $table->string('re_zip', 10)->nullable();
	        $table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('realestates');
	}

}