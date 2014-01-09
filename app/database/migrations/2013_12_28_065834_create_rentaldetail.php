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
		Schema::create('rentaldetails', function($table)
		{
			$table->increments('rd_id');
	        $table->integer('rd_re_id')->unsigned();
	        $table->foreign('rd_re_id')->references('re_id')->on('realestates');
	        $table->integer('rd_months_min')->default(8);
	        $table->integer('rd_months_max')->default(12);
	        $table->integer('rd_repair_min')->default(0);
	        $table->integer('rd_repair_max')->default(0);
	        $table->integer('rd_pm_monthly_charge')->default(0);
	        $table->integer('rd_pm_vacancy_charge')->default(0);	        
	        $table->timestamps();
		});

		Schema::create('renttiers', function($table)
		{
			$table->increments('rt_id');
	        $table->integer('rt_re_id')->unsigned();
	        $table->foreign('rt_re_id')->references('re_id')->on('realestates');
	        $table->integer('rt_units')->default(1);
	        $table->decimal('rt_rent')->default(0);	        
	        $table->timestamps();
		});

		Schema::create('mortgages', function($table)
		{
			$table->increments('mg_id');
	        $table->integer('mg_re_id')->unsigned();
	        $table->foreign('mg_re_id')->references('re_id')->on('realestates');
	        $table->decimal('mg_monthly_payment')->default(0);
	        $table->decimal('mg_sale_price');
	        $table->decimal('mg_interest_rate')->nullable();
	        $table->decimal('mg_percent_down')->nullable();
	        $table->decimal('mg_pmi')->nullable();
	        $table->decimal('mg_term')->nullable();
	        $table->decimal('mg_term2')->nullable();
	        $table->timestamps();
		});

		Schema::create('fixedexpenses', function($table)
		{
			$table->increments('fe_id');
	        $table->integer('fe_re_id')->unsigned();
	        $table->foreign('fe_re_id')->references('re_id')->on('realestates');
	        $table->decimal('fe_taxes')->default(0);
	        $table->decimal('fe_insurance')->default(0);
	        $table->decimal('fe_utilities')->default(0);
	        $table->decimal('fe_misc')->default(0);
	        $table->timestamps();
		});

		Schema::create('returnoninvestments', function($table)
		{
			$table->increments('roi_id');
	        $table->integer('roi_re_id')->unsigned();
	        $table->foreign('roi_re_id')->references('re_id')->on('realestates');
	        $table->decimal('roi_down_payment')->default(0);
	        $table->decimal('roi_closing_costs')->default(0);
	        $table->decimal('roi_misc_expenses')->default(0);
	        $table->decimal('roi_init_investment')->default(0);
	        $table->timestamps();
		});

		Schema::create('rentalhistories', function($table)
		{
			$table->increments('rh_id');
	        $table->integer('rh_re_id')->unsigned();
	        $table->foreign('rh_re_id')->references('re_id')->on('realestates');
	        $table->date('rh_date');
	        $table->decimal('rh_rent');
	        $table->decimal('rh_mortgage')->default(0);
	        $table->decimal('rh_property_management')->default(0);
	        $table->decimal('rh_tax'->default(0);
	        $table->decimal('rh_insurance')->default(0);
	        $table->decimal('rh_electricity')->default(0);
	        $table->decimal('rh_water')->default(0);
	        $table->decimal('rh_cashflow')->default(0);
	        $table->decimal('rh_repairs')->default(0);
	        $table->timestamps();
		});
	

		Schema::create('estimates', function($table)
		{
			$table->increments('est_id');
	        $table->integer('est_re_id')->unsigned();
	        $table->foreign('est_re_id')->references('re_id')->on('realestates');
	        $table->decimal('est_rent');
	        $table->decimal('est_repairs');
	        $table->decimal('est_cashflow');        
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
		Schema::drop('renttiers');
		Schema::drop('rentaldetails');		
		Schema::drop('mortgages');
		Schema::drop('fixedexpenses');
		Schema::drop('returnoninvestments');
		Schema::drop('rentalhistorys');
		Schema::drop('estimates');
	}

}