<?php

use Illuminate\Database\Migrations\Migration;

class AddMortgageCalculator extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('mortgages', function($table)
		{
			$table->boolean('mg_calculator');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('mortgages', function($table)
		{
			$table->dropColumn('mg_calculator');
		});
	}

}