<?php

class Montecarlo {	

	public static function calculateRepaymentSchedule($re_id){
		
		$estimate = Estimate::getByReId($re_id);
		$roi =  Returnoninvestment::getByReId($re_id);

     	$total_income = 0;
     	$years_passed = 0;
     	$ary_repayed = "[";
     	$ary_roi_init_investment= "[";
		$ary_axis = "[";
     	 
     	$medianYearlyIncome = $estimate->est_cashflow;
     	$roi_init_investment = $roi->roi_init_investment;
     	$medianYearlyROI = $estimate->est_roi;
     	$medianYearlyIncomeAlternate = $estimate->est_cashflow2;
     	$medianYearlyROIAlternate = $estimate->est_roi2;

     	while($total_income <= $roi_init_investment){
     		$total_income += $medianYearlyIncome;
     		$total_investment_left = $roi_init_investment-$total_income;

     		//if total income exceed initial investment we set investment left to 0 so the graph doesn't show a negative value
     		if($total_income >= $roi_init_investment){
     				$total_investment_left = 0;
     		}
     		$total_income_show = $total_income;		
     		
     		//echo "['".++$years_passed." Year (".$medianYearlyROI*$years_passed."% repayed)', ".$total_income_show.", ".$total_investment_left."]";
     		$ary_repayed .= $total_income_show;
     		$ary_roi_init_investment .= $total_investment_left;
     		$ary_axis .= "'".++$years_passed." Year (".$medianYearlyROI*$years_passed."% repayed)'";
     		
     		//Don't add a comma to the last value in the array
     		if($total_income <= $roi_init_investment){
     			$ary_repayed .= ",";
     			$ary_roi_init_investment .= ",";
     			$ary_axis .= ",";
     		}else{
     			$ary_repayed .= "]";
     			$ary_roi_init_investment .= "]";
     			$ary_axis .= "]";
     			//Once we reach the year in which the investment is repayed we calculate exactly in which month it will be repayed
     			$tmp_total_income = $total_income;
     			$medianMonthlyIncome = $medianYearlyIncome/12;
     			$total_months = 12;
     			while($roi_init_investment <= $tmp_total_income){
     				//we substract from the total income (which is gonna be above the investment) monthly income until we are at the investment amount
     				$tmp_total_income -= $medianMonthlyIncome;
     				$total_months--;
     			}
     			$total_months += 1;
     			$years_passed -= 1;
     		}
      	}

      	//CALCULATE ALTERNATE
      	$ary_repayedAlternate = "[";
     	$ary_roi_init_investmentAlternate= "[";
     	$ary_axisAlternate = "[";
     	$total_income2 = 0;
     	$years_passed2 = 0;
      	while($total_income2 <= $roi_init_investment){
     		$total_income2 += $medianYearlyIncomeAlternate;
     		$total_investment_left = $roi_init_investment-$total_income2;

     		//if total income exceed initial investment we set investment left to 0 so the graph doesn't show a negative value
     		if($total_income2 >= $roi_init_investment){
     				$total_investment_left = 0;
     		}
     		$total_income_show = $total_income2;		
     		
     		//echo "['".++$years_passed." Year (".$medianYearlyROI*$years_passed."% repayed)', ".$total_income_show.", ".$total_investment_left."]";
     		$ary_repayedAlternate .= $total_income_show;
     		$ary_roi_init_investmentAlternate .= $total_investment_left;
     		$ary_axisAlternate .= "'".++$years_passed2." Year (".$medianYearlyROIAlternate*$years_passed2."% repayed)'";
     		
     		//Don't add a comma to the last value in the array
     		if($total_income2 <= $roi_init_investment){
     			$ary_repayedAlternate .= ",";
     			$ary_roi_init_investmentAlternate .= ",";
     			$ary_axisAlternate .= ",";
     		}else{
     			$ary_repayedAlternate .= "]";
     			$ary_roi_init_investmentAlternate .= "]";
     			$ary_axisAlternate .= "]";
     			//Once we reach the year in which the investment is repayed we calculate exactly in which month it will be repayed
     			$tmp_total_income = $total_income2;
     			$medianMonthlyIncome = $medianYearlyIncomeAlternate/12;
     			$total_months2 = 12;
     			while($roi_init_investment <= $tmp_total_income){
     				//we substract from the total income (which is gonna be above the investment) monthly income until we are at the investment amount
     				$tmp_total_income -= $medianMonthlyIncome;
     				$total_months2--;
     			}
     			$total_months2 += 1;
     			$years_passed2 -= 1;
     		}
      	}

      	$chart_values['ary_repayed'] = $ary_repayed;
     	$chart_values['ary_roi_init_investment'] = $ary_roi_init_investment;
     	$chart_values['ary_axis'] = $ary_axis;
     	$chart_values['ary_repayed2'] = $ary_repayedAlternate;
     	$chart_values['ary_roi_init_investment2'] = $ary_roi_init_investmentAlternate;
     	$chart_values['ary_axis'] = $ary_axisAlternate;

      	return $chart_values;
		        
	}

	public static function calculateEstimate($re_id, $total_scenarios) {
		//calculate monthlyFixedExpenses
		$rentaldetail = Rentaldetail::getByReId($re_id);
        $renttier = Renttier::getByReId($re_id);
        $mortgage = Mortgage::getByReId($re_id);
        $fixedexpense = Fixedexpense::getByReId($re_id); 
        $returnoninvestment = Returnoninvestment::getByReId($re_id);
        $estimate = Estimate::getByReId($re_id);
        if(!isset($estimate)){
        	$estimate = new Estimate;
        	$estimate->est_re_id = $re_id;
        }
        // $rentaldetail = compact('rentaldetail');         
        // $renttier = compact('renttier');
        // $mortgage = compact('mortgage'); 
        // $roi = compact('roi'); 
        // $fixedexpense = compact('fixedexpense');     

		$monthlyFixedExpenses = $fixedexpense->fe_utilities + $mortgage->mg_monthly_payment + $fixedexpense->fe_taxes + $fixedexpense->fe_insurance;
		$yearlyFixedExpenses = $monthlyFixedExpenses*12;

		//calculate monthlyFixedExpensesAlternate for the comparison with 15yr vs 30yr mortgage
		$mortgage->mg_monthly_payment = SmartPassiveIncome::calculateMortgage($mortgage->mg_percent_down, $mortgage->mg_sale_price, $mortgage->mg_interest_rate, $mortgage->mg_term2);
		$monthlyFixedExpensesAlternate = $fixedexpense->fe_utilities + $mortgage->mg_monthly_payment + $fixedexpense->fe_taxes + $fixedexpense->fe_insurance;
		$yearlyFixedExpensesAlternate = $monthlyFixedExpensesAlternate*12;

		$yearlyFixedExpensesDifference =  $yearlyFixedExpenses - $yearlyFixedExpensesAlternate;
	
		$losingMoneyScenarios = 0;

		//Run the scenarios
		error_log("repair min-max: ".$rentaldetail->rd_repair_min." - ".$rentaldetail->rd_repair_max);
		error_log("rented min-max: ".$rentaldetail->rd_months_min." - ".$rentaldetail->rd_months_max);
		for($i = 0; $i < $total_scenarios; $i++){
			//revenue
			$monthsRentedIndex = 0;
			$yearlyRent = 0;
			//error_log("unit group count: ".count($ary_unitsAmount));
			//This level is for each group of aparments priced at the same rate
			$monthsRented = rand($rentaldetail->rd_months_min, $rentaldetail->rd_months_max);
			$yearlyRent = $renttier->rt_rent * $monthsRented;
			// for($t = 0; $t <= count($ary_unitsAmount)-1; $t++) {	
			// 	//This level is for one specific group of apartments			
			// 	//error_log("number of units on this group: ".$ary_unitsAmount[$t]);
			// 	for($n = 0; $n <= intval($ary_unitsAmount[$t])-1; $n++){
			// 		$monthsRented[$monthsRentedIndex] = rand($rentaldetail->rd_months_min, $rentaldetail->rd_months_max);
			// 		//error_log("months rented: ".$monthsRented[$monthsRentedIndex]." @ ".$ary_monthlyRent[$t]." = ".$monthsRented[$monthsRentedIndex]*$ary_monthlyRent[$t]);
			// 		$yearlyRent += $ary_monthlyRent[$t] * $monthsRented[$monthsRentedIndex];
			// 		$monthsRentedIndex++;
			// 		//error_log("yearly rent: ".$yearlyRent);
			// 	}	
			// }

			//variable expenses

			$yearlyRepairs = rand($rentaldetail->rd_repair_min*12, $rentaldetail->rd_repair_max*12);
			$yearlyVacantExpense = 0;
			//for($t = 0; $t <= count($monthsRented)-1; $t++){

				$yearlyVacantExpense += (12 - $monthsRented) * $rentaldetail->rd_pm_vacancy_charge;
			//}			
			$yearlyrd_pm_monthly_charge = ($rentaldetail->rd_pm_monthly_charge/100) * $yearlyRent;			
			$yearlyPropertyManagementExpense = $yearlyrd_pm_monthly_charge + $yearlyVacantExpense;
			
			//$yearlyVariableExpenses = $yearlyRepairs + $yearlyPropertyManagementExpense;

			//income
			$yearlyIncome = $yearlyRent - $yearlyFixedExpenses - $yearlyRepairs - $yearlyPropertyManagementExpense;
			
			//build array
			//since each individual unit will have different months I can show just one row
			//$ary_monteCarloEstimates[$i]["monthsRented"] = $monthsRented;
			$ary_monteCarloEstimates[$i]["yearlyRent"] = $yearlyRent;	
			$ary_monteCarloEstimates[$i]["yearlyRepairs"] = $yearlyRepairs;
			$ary_monteCarloEstimates[$i]["yearlyPropertyManagementPercent"] = $yearlyrd_pm_monthly_charge;
			$ary_monteCarloEstimates[$i]["yearlyVacantExpense"] = $yearlyVacantExpense;
			$ary_monteCarloEstimates[$i]["yearlyPropertyManagementExpense"] = $yearlyPropertyManagementExpense;
			//$ary_monteCarloEstimates[$i]["yearlyIncome"] = $yearlyIncome;	
			$ary_monteCarloEstimates[$i]["yearlyFixedExpenses"] = $yearlyFixedExpenses;	
			//$ary_monteCarloEstimates[$i]["yearlyVariableExpenses"] = $yearlyVariableExpenses;	
			//error_log("yearly variable expenses: ".$yearlyVariableExpenses);


			$ary_yearlyPropertyManagementExpense[$i] = $yearlyPropertyManagementExpense;
			//$ary_yearlyIncome[$i] = $yearlyIncome;
			//$ary_yearlyVariableExpenses[$i] = $yearlyVariableExpenses;
			$ary_yearlyRepairs[$i] = $yearlyRepairs;
			$ary_yearlyRent[$i] = $yearlyRent;

			if($yearlyIncome < 0){
				$losingMoneyScenarios++;
			}
		}
				
		//expenses
		$medianYearlyRent = SmartPassiveIncome::calculateMedian($ary_yearlyRent);		
		$medianYearlyRepairs = SmartPassiveIncome::calculateMedian($ary_yearlyRepairs);
		$medianYearlyPM = SmartPassiveIncome::calculateMedian($ary_yearlyPropertyManagementExpense);
		
		$highestYearlyRent = SmartPassiveIncome::calculateHighest($ary_yearlyRent);	
		$highestYearlyRepairs = SmartPassiveIncome::calculateHighest($ary_yearlyRepairs);
		$highestYearlyPM = SmartPassiveIncome::calculateHighest($ary_yearlyPropertyManagementExpense);
		
		$lowestYearlyRent = SmartPassiveIncome::calculateLowest($ary_yearlyRent);		
		$lowestYearlyRepairs = SmartPassiveIncome::calculateLowest($ary_yearlyRepairs);
		$lowestYearlyPM = SmartPassiveIncome::calculateLowest($ary_yearlyPropertyManagementExpense);
		
		//income
		$medianYearlyIncome = $medianYearlyRent - $medianYearlyPM - $medianYearlyRepairs - $yearlyFixedExpenses; 
		$highestYearlyIncome = $highestYearlyRent - $highestYearlyPM - $highestYearlyRepairs - $yearlyFixedExpenses; 
		$lowestYearlyIncome = $lowestYearlyRent - $lowestYearlyPM - $lowestYearlyRepairs - $yearlyFixedExpenses; 

		//calculate alternate income
		$medianYearlyIncomeAlternate = $medianYearlyIncome + ($yearlyFixedExpensesDifference);
		$highestYearlyIncomeAlternate = $highestYearlyIncome + ($yearlyFixedExpensesDifference);
		$lowestYearlyIncomeAlternate = $lowestYearlyIncome + ($yearlyFixedExpensesDifference);



		//roi
		//if(strlen($mortgage->mg_sale_price) > 0 && strlen($returnoninvestment->roi_down_payment) < 0){
		//	$returnoninvestment->roi_down_payment = $mortgage->mg_sale_price * ($mortgage->mg_percent_down / 100);
		//}

		//$returnoninvestment->roi_init_investment = $returnoninvestment->roi_down_payment + $returnoninvestment->roi_misc_expenses + $returnoninvestment->roi_closing_costs;

		if($returnoninvestment->roi_init_investment > 0){
			$medianYearlyROI = number_format($medianYearlyIncome/$returnoninvestment->roi_init_investment, 4)*100;
			$highestYearlyROI = number_format($highestYearlyIncome/$returnoninvestment->roi_init_investment, 4)*100;
			$lowestYearlyROI = number_format($lowestYearlyIncome/$returnoninvestment->roi_init_investment, 4)*100;

			//alternate roi
			$medianYearlyROIAlternate = number_format($medianYearlyIncomeAlternate/$returnoninvestment->roi_init_investment, 4)*100;
			$highestYearlyROIAlternate = number_format($highestYearlyIncomeAlternate/$returnoninvestment->roi_init_investment, 4)*100;
			$lowestYearlyROIAlternate = number_format($lowestYearlyIncomeAlternate/$returnoninvestment->roi_init_investment, 4)*100;
		}

		//risk assesment
		$losingMoneyChance = ($losingMoneyScenarios/$total_scenarios)*100;

		//misc calculations PMI
		if($mortgage->mg_percent_down > 20){
			$pmiPerMonth = SmartPassiveIncome::calculatePMIPerMonth($mortgage->mg_sale_price - $mortgage->mg_percent_down);
		}

		error_log("Median Income: ".$medianYearlyRent." - ".$yearlyFixedExpenses." - ".$medianYearlyPM." - ".$medianYearlyRepairs." = ".$medianYearlyIncome);

		//standard deviation
		//$time_start = microtime(true);
		//$standardDeviation = SmartPassiveIncome::calculateStandardDeviation($ary_yearlyIncome);
		//$time_end = microtime(true);
		//$time = $time_end - $time_start;
		//error_log("Standard Deviation Time: ".$time);

		$estimate->est_rent = $medianYearlyRent/12;
		$estimate->est_repairs = $medianYearlyRepairs/12;
		$estimate->est_variable_expenses = ($medianYearlyPM + $medianYearlyRepairs)/12;		

		$estimate->est_cashflow = $medianYearlyIncome/12;
		$estimate->est_fixed_expenses = $monthlyFixedExpenses;
		$estimate->est_roi = $medianYearlyROI;
		$estimate->est_cashflow2 = $medianYearlyIncomeAlternate/12;
		$estimate->est_fixed_expenses2 = $monthlyFixedExpensesAlternate/12;
		$estimate->est_roi2 = $medianYearlyROIAlternate;
		$estimate->save();



	}
}