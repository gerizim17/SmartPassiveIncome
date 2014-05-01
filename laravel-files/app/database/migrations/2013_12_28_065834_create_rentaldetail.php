<?php

use Illuminate\Database\Migrations\Migration;

class CreateRentaldetail extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('realestate', function($table)
		{
			$table->increments('id');
			$table->foreign('user_id')->references('id')->on('users');
	        $table->string('address1', 128);
	        $table->string('address2', 128)->nullable();
	        $table->string('city', 128)->nullable();
	        $table->string('state', 20)->nullable();
	        $table->string('zip', 10)->nullable();
	        $table->timestamps();
		});

		Schema::create('rentaldetail', function($table)
		{
			$table->increments('id');
	        $table->integer('realestate_id')->unsigned();
	        $table->foreign('realestate_id')->references('id')->on('realestate');
	        $table->integer('months_min')->default(8);
	        $table->integer('months_max')->default(12);
	        $table->integer('repair_min')->default(0);
	        $table->integer('repair_max')->default(0);
	        $table->integer('pm_monthly_charge')->default(0);
	        $table->integer('pm_vacancy_charge')->default(0);	        
	        $table->timestamps();
		});

		Schema::create('renttier', function($table)
		{
			$table->increments('id');
	        $table->integer('realestate_id')->unsigned();
	        $table->foreign('realestate_id')->references('id')->on('realestate');
	        $table->integer('units')->default(1);
	        $table->decimal('rent')->default(0);	        
	        $table->timestamps();
		});

		Schema::create('mortgage', function($table)
		{
			$table->increments('id');
	        $table->integer('realestate_id')->unsigned();
	        $table->foreign('realestate_id')->references('id')->on('realestate');
	        $table->decimal('monthly_payment')->default(0);
	        $table->decimal('monthly_payment2')->default(0);
	        $table->decimal('sale_price');
	        $table->decimal('interest_rate')->nullable();
	        $table->decimal('percent_down')->nullable();
	        $table->decimal('pmi')->nullable();
	        $table->decimal('pmi2')->nullable();
	        $table->decimal('term')->nullable();
	        $table->decimal('term2')->nullable();
	        $table->boolean('calculator')->default(1);
	        $table->timestamps();
		});

		Schema::create('users', function($table)
		{
		    $table->increments('id');
		    $table->string('firstname', 20);
		    $table->string('lastname', 20);
		    $table->string('email', 100)->unique();
		    $table->string('password', 64);
		    $table->timestamps();
		}

		Schema::create('fixedexpense', function($table)
		{
			$table->increments('id');
	        $table->integer('realestate_id')->unsigned();
	        $table->foreign('realestate_id')->references('id')->on('realestate');
	        $table->decimal('taxes')->default(0);
	        $table->decimal('insurance')->default(0);
	        $table->decimal('utilities')->default(0);
	        $table->decimal('misc')->default(0);
	        $table->timestamps();
		});

		Schema::create('returnoninvestment', function($table)
		{
			$table->increments('id');
	        $table->integer('realestate_id')->unsigned();
	        $table->foreign('realestate_id')->references('id')->on('realestate');
	        $table->decimal('down_payment')->default(0);
	        $table->decimal('closing_costs')->default(0);
	        $table->decimal('misc_expenses')->default(0);
	        $table->decimal('init_investment')->default(0);
	        $table->timestamps();
		});

		Schema::create('rentalhistory', function($table)
		{
			$table->increments('id');
	        $table->integer('realestate_id')->unsigned();
	        $table->foreign('realestate_id')->references('id')->on('realestate');
	        $table->date('date');
	        $table->decimal('rent');
	        $table->decimal('mortgage')->default(0);
	        $table->decimal('property_management')->default(0);
	        $table->decimal('tax')->default(0);
	        $table->decimal('insurance')->default(0);
	        $table->decimal('electricity')->default(0);
	        $table->decimal('water')->default(0);
	        $table->decimal('cashflow')->default(0);
	        $table->decimal('repairs')->default(0);
	        $table->timestamps();
		});
	

		Schema::create('estimate', function($table)
		{
			$table->increments('id');
	        $table->integer('realestate_id')->unsigned();
	        $table->foreign('realestate_id')->references('id')->on('realestate');
	        $table->decimal('rent');
	        $table->decimal('repairs');
	        $table->decimal('cashflow');        
	        $table->decimal('variable_expenses');
			$table->decimal('fixed_expenses');			
			$table->decimal('roi');
			$table->decimal('cashflow2');
			$table->decimal('fixed_expenses2');			
			$table->decimal('roi2');
			$table->decimal('risk');
			$table->decimal('risk2');
	        $table->timestamps();
		});

		Schema::create('estimatebest', function($table)
		{
			$table->increments('id');
	        $table->integer('realestate_id')->unsigned();
	        $table->foreign('realestate_id')->references('id')->on('realestate');
	        $table->decimal('rent');
	        $table->decimal('repairs');
	        $table->decimal('cashflow');        
	        $table->decimal('variable_expenses');
			$table->decimal('fixed_expenses');			
			$table->decimal('roi');
			$table->decimal('cashflow2');
			$table->decimal('fixed_expenses2');			
			$table->decimal('roi2');
	        $table->timestamps();
		});

		Schema::create('estimateworst', function($table)
		{
			$table->increments('id');
	        $table->integer('realestate_id')->unsigned();
	        $table->foreign('realestate_id')->references('id')->on('realestate');
	        $table->decimal('rent');
	        $table->decimal('repairs');
	        $table->decimal('cashflow');        
	        $table->decimal('variable_expenses');
			$table->decimal('fixed_expenses');			
			$table->decimal('roi');
			$table->decimal('cashflow2');
			$table->decimal('fixed_expenses2');			
			$table->decimal('roi2');
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
		Schema::drop('renttier');
		Schema::drop('rentaldetail');		
		Schema::drop('mortgage');
		Schema::drop('fixedexpense');
		Schema::drop('returnoninvestment');
		Schema::drop('rentalhistory');
		Schema::drop('estimate');
		Schema::drop('users');
		Schema::drop('realestate');
	}

}