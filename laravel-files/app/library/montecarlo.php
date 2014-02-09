<?php

class Montecarlo {	

	public static function calculateRepaymentSchedule($realestate_id){
		
		$estimate = Estimate::getByReId($realestate_id);
		$roi =  Returnoninvestment::getByReId($realestate_id);

     	$total_income = 0;
     	$years_passed = 0;
     	$ary_repayed = "[";
     	$ary_init_investment= "[";
		$ary_axis = "[";
     	 
     	$medianYearlyIncome = $estimate->cashflow;
     	$init_investment = $roi->init_investment;
     	$medianYearlyROI = $estimate->roi;
     	$medianYearlyIncomeAlternate = $estimate->cashflow2;
     	$medianYearlyROIAlternate = $estimate->roi2;

     	while($total_income <= $init_investment){
     		$total_income += $medianYearlyIncome;
     		$total_investment_left = $init_investment-$total_income;

     		//if total income exceed initial investment we set investment left to 0 so the graph doesn't show a negative value
     		if($total_income >= $init_investment){
     				$total_investment_left = 0;
     		}
     		$total_income_show = $total_income;		
     		
     		//echo "['".++$years_passed." Year (".$medianYearlyROI*$years_passed."% repayed)', ".$total_income_show.", ".$total_investment_left."]";
     		$ary_repayed .= $total_income_show;
     		$ary_init_investment .= $total_investment_left;
     		$ary_axis .= "'".++$years_passed." Year (".$medianYearlyROI*$years_passed."% repayed)'";
     		
     		//Don't add a comma to the last value in the array
     		if($total_income <= $init_investment){
     			$ary_repayed .= ",";
     			$ary_init_investment .= ",";
     			$ary_axis .= ",";
     		}else{
     			$ary_repayed .= "]";
     			$ary_init_investment .= "]";
     			$ary_axis .= "]";
     			//Once we reach the year in which the investment is repayed we calculate exactly in which month it will be repayed
     			$tmp_total_income = $total_income;
     			$medianMonthlyIncome = $medianYearlyIncome/12;
     			$total_months = 12;
     			while($init_investment <= $tmp_total_income){
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
     	$ary_init_investmentAlternate= "[";
     	$ary_axisAlternate = "[";
     	$total_income2 = 0;
     	$years_passed2 = 0;
      	while($total_income2 <= $init_investment){
     		$total_income2 += $medianYearlyIncomeAlternate;
     		$total_investment_left = $init_investment-$total_income2;

     		//if total income exceed initial investment we set investment left to 0 so the graph doesn't show a negative value
     		if($total_income2 >= $init_investment){
     				$total_investment_left = 0;
     		}
     		$total_income_show = $total_income2;		
     		
     		//echo "['".++$years_passed." Year (".$medianYearlyROI*$years_passed."% repayed)', ".$total_income_show.", ".$total_investment_left."]";
     		$ary_repayedAlternate .= $total_income_show;
     		$ary_init_investmentAlternate .= $total_investment_left;
     		$ary_axisAlternate .= "'".++$years_passed2." Year (".$medianYearlyROIAlternate*$years_passed2."% repayed)'";
     		
     		//Don't add a comma to the last value in the array
     		if($total_income2 <= $init_investment){
     			$ary_repayedAlternate .= ",";
     			$ary_init_investmentAlternate .= ",";
     			$ary_axisAlternate .= ",";
     		}else{
     			$ary_repayedAlternate .= "]";
     			$ary_init_investmentAlternate .= "]";
     			$ary_axisAlternate .= "]";
     			//Once we reach the year in which the investment is repayed we calculate exactly in which month it will be repayed
     			$tmp_total_income = $total_income2;
     			$medianMonthlyIncome = $medianYearlyIncomeAlternate/12;
     			$total_months2 = 12;
     			while($init_investment <= $tmp_total_income){
     				//we substract from the total income (which is gonna be above the investment) monthly income until we are at the investment amount
     				$tmp_total_income -= $medianMonthlyIncome;
     				$total_months2--;
     			}
     			$total_months2 += 1;
     			$years_passed2 -= 1;
     		}
      	}

      	$chart_values['ary_repayed'] = $ary_repayed;
     	$chart_values['ary_init_investment'] = $ary_init_investment;
     	$chart_values['ary_axis'] = $ary_axis;
     	$chart_values['ary_repayed2'] = $ary_repayedAlternate;
     	$chart_values['ary_init_investment2'] = $ary_init_investmentAlternate;
     	$chart_values['ary_axis'] = $ary_axisAlternate;

      	return $chart_values;
		        
	}

	public static function calculateEstimate($realestate_id, $total_scenarios) {
		//calculate monthlyFixedExpenses
		$rentaldetail = Rentaldetail::getByReId($realestate_id);
        $renttier = Renttier::getByReId($realestate_id);
        $mortgage = Mortgage::getByReId($realestate_id);
        $fixedexpense = Fixedexpense::getByReId($realestate_id); 
        $returnoninvestment = Returnoninvestment::getByReId($realestate_id);
        $estimate = Estimate::getByReId($realestate_id);
        if(!isset($estimate)){
        	$estimate = new Estimate;
        	$estimate->realestate_id = $realestate_id;
        }
        // $rentaldetail = compact('rentaldetail');         
        // $renttier = compact('renttier');
        // $mortgage = compact('mortgage'); 
        // $roi = compact('roi'); 
        // $fixedexpense = compact('fixedexpense');     

		$monthlyFixedExpenses = $fixedexpense->utilities + $fixedexpense->taxes + $fixedexpense->insurance;
		$yearlyFixedExpenses = $monthlyFixedExpenses*12;
		
		$monthlyFixedExpensesAlternate = $fixedexpense->utilities + $fixedexpense->taxes + $fixedexpense->insurance;
		$yearlyFixedExpensesAlternate = $monthlyFixedExpensesAlternate*12;

		$yearlyFixedExpensesDifference =  $yearlyFixedExpenses - $yearlyFixedExpensesAlternate;
	
		$losingMoneyScenarios = 0;

		//Run the scenarios		
		for($i = 0; $i < $total_scenarios; $i++){
			//revenue
			$monthsRentedIndex = 0;
			$yearlyRent = 0;
			//error_log("unit group count: ".count($ary_unitsAmount));
			//This level is for each group of aparments priced at the same rate
			$monthsRented = rand($rentaldetail->months_min, $rentaldetail->months_max);
			$yearlyRent = $renttier->rent * $monthsRented;
			// for($t = 0; $t <= count($ary_unitsAmount)-1; $t++) {	
			// 	//This level is for one specific group of apartments			
			// 	//error_log("number of units on this group: ".$ary_unitsAmount[$t]);
			// 	for($n = 0; $n <= intval($ary_unitsAmount[$t])-1; $n++){
			// 		$monthsRented[$monthsRentedIndex] = rand($rentaldetail->months_min, $rentaldetail->months_max);
			// 		//error_log("months rented: ".$monthsRented[$monthsRentedIndex]." @ ".$ary_monthlyRent[$t]." = ".$monthsRented[$monthsRentedIndex]*$ary_monthlyRent[$t]);
			// 		$yearlyRent += $ary_monthlyRent[$t] * $monthsRented[$monthsRentedIndex];
			// 		$monthsRentedIndex++;
			// 		//error_log("yearly rent: ".$yearlyRent);
			// 	}	
			// }

			//variable expenses

			$yearlyRepairs = rand($rentaldetail->repair_min*12, $rentaldetail->repair_max*12);
			$yearlyVacantExpense = 0;
			//for($t = 0; $t <= count($monthsRented)-1; $t++){

				$yearlyVacantExpense += (12 - $monthsRented) * $rentaldetail->pm_vacancy_charge;
			//}			
			$yearly_pm_monthly_charge = ($rentaldetail->pm_monthly_charge/100) * $yearlyRent;					
			$yearlyPropertyManagementExpense = $yearly_pm_monthly_charge + $yearlyVacantExpense;
			
			//$yearlyVariableExpenses = $yearlyRepairs + $yearlyPropertyManagementExpense;

			//income
			$yearlyIncome = $yearlyRent - $yearlyFixedExpenses - $yearlyRepairs - $yearlyPropertyManagementExpense;
			
			//build array
			//since each individual unit will have different months I can show just one row
			//$ary_monteCarloEstimates[$i]["monthsRented"] = $monthsRented;
			$ary_monteCarloEstimates[$i]["yearlyRent"] = $yearlyRent;	
			$ary_monteCarloEstimates[$i]["yearlyRepairs"] = $yearlyRepairs;
			$ary_monteCarloEstimates[$i]["yearlyPropertyManagementPercent"] = $yearly_pm_monthly_charge;
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

		// error_log("median yearly rent: ".$medianYearlyRent);
		// error_log("median monthly rent: ".$medianYearlyRent/12);

		// error_log("months rented: ".$medianYearlyRent/$renttier->rt_rent);


		$highestYearlyRent = SmartPassiveIncome::calculateHighest($ary_yearlyRent);	
		$highestYearlyRepairs = SmartPassiveIncome::calculateHighest($ary_yearlyRepairs);
		$highestYearlyPM = SmartPassiveIncome::calculateHighest($ary_yearlyPropertyManagementExpense);
		
		$lowestYearlyRent = SmartPassiveIncome::calculateLowest($ary_yearlyRent);		
		$lowestYearlyRepairs = SmartPassiveIncome::calculateLowest($ary_yearlyRepairs);
		$lowestYearlyPM = SmartPassiveIncome::calculateLowest($ary_yearlyPropertyManagementExpense);
		
		//income
		$medianYearlyIncome = $medianYearlyRent - $medianYearlyPM - $medianYearlyRepairs - $yearlyFixedExpenses - $mortgage->monthly_payment*12;		
		$highestYearlyIncome = $highestYearlyRent - $highestYearlyPM - $highestYearlyRepairs - $yearlyFixedExpenses - $mortgage->monthly_payment*12; 
		$lowestYearlyIncome = $lowestYearlyRent - $lowestYearlyPM - $lowestYearlyRepairs - $yearlyFixedExpenses - $mortgage->monthly_payment*12; 

		//calculate alternate income
		$medianYearlyIncomeAlternate = $medianYearlyIncome + ($yearlyFixedExpensesDifference) - $mortgage->monthly_payment2*12;
		$highestYearlyIncomeAlternate = $highestYearlyIncome + ($yearlyFixedExpensesDifference) - $mortgage->monthly_payment2*12;
		$lowestYearlyIncomeAlternate = $lowestYearlyIncome + ($yearlyFixedExpensesDifference) - $mortgage->monthly_payment2*12;



		//roi
		//if(strlen($mortgage->sale_price) > 0 && strlen($returnoninvestment->down_payment) < 0){
		//	$returnoninvestment->down_payment = $mortgage->sale_price * ($mortgage->percent_down / 100);
		//}

		//$returnoninvestment->init_investment = $returnoninvestment->down_payment + $returnoninvestment->misc_expenses + $returnoninvestment->closing_costs;

		if($returnoninvestment->init_investment > 0){
			$medianYearlyROI = number_format($medianYearlyIncome/$returnoninvestment->init_investment, 4)*100;
			$highestYearlyROI = number_format($highestYearlyIncome/$returnoninvestment->init_investment, 4)*100;
			$lowestYearlyROI = number_format($lowestYearlyIncome/$returnoninvestment->init_investment, 4)*100;

			//alternate roi
			$medianYearlyROIAlternate = number_format($medianYearlyIncomeAlternate/$returnoninvestment->init_investment, 4)*100;
			$highestYearlyROIAlternate = number_format($highestYearlyIncomeAlternate/$returnoninvestment->init_investment, 4)*100;
			$lowestYearlyROIAlternate = number_format($lowestYearlyIncomeAlternate/$returnoninvestment->init_investment, 4)*100;
		}

		//risk assesment
		$losingMoneyChance = ($losingMoneyScenarios/$total_scenarios)*100;

		//misc calculations PMI
		if($mortgage->percent_down > 20){
			$pmiPerMonth = SmartPassiveIncome::calculatePMIPerMonth($mortgage->sale_price - $mortgage->percent_down);
		}		

		//standard deviation
		//$time_start = microtime(true);
		//$standardDeviation = SmartPassiveIncome::calculateStandardDeviation($ary_yearlyIncome);
		//$time_end = microtime(true);
		//$time = $time_end - $time_start;
		//error_log("Standard Deviation Time: ".$time);

		$estimate->rent = $medianYearlyRent/12;
		$estimate->repairs = $medianYearlyRepairs/12;
		$estimate->variable_expenses = ($medianYearlyPM + $medianYearlyRepairs)/12;		

		$estimate->cashflow = $medianYearlyIncome/12;
		$estimate->fixed_expenses = $monthlyFixedExpenses;
		$estimate->roi = $medianYearlyROI;
		$estimate->cashflow2 = $medianYearlyIncomeAlternate/12;
		$estimate->fixed_expenses2 = $monthlyFixedExpensesAlternate/12;
		$estimate->roi2 = $medianYearlyROIAlternate;
		$estimate->save();



	}
}