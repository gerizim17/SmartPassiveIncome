<?php

use Illuminate\Database\Migrations\Migration;

class AddEstimateFiels extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('estimates', function($table)
		{
			$table->decimal('est_variable_expenses');
			$table->decimal('est_fixed_expenses');			
			$table->decimal('est_roi');
			$table->decimal('est_cashflow2');
			$table->decimal('est_fixed_expenses2');			
			$table->decimal('est_roi2');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('estimates', function($table)
		{
			$table->drop('est_variable_expenses');
			$table->drop('est_fixed_expenses');
			$table->drop('est_roi');
			$table->drop('est_cashflow2');
			$table->drop('est_fixed_expenses2');			
			$table->drop('est_roi2');
		});
	}

}