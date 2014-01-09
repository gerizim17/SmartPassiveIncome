<?php

use Illuminate\Database\Migrations\Migration;

class AddMortgage2Fields extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('mortgages', function($table)
		{
			$table->decimal('mg_monthly_payment2');
			$table->decimal('mg_pmi2');	        
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
			$table->dropColumn('mg_monthly_payment2');
			$table->dropColumn('mg_pmi2');
		});
	}

}