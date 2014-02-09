<?php

class SmartPassiveIncome {

	public static function calculateMedian($arr) {
		sort($arr);
		$count = count($arr); //total numbers in array
		$middleval = floor(($count-1)/2); // find the middle value, or the lowest middle value
		if($count % 2) { // odd number, middle is the median
			$median = $arr[$middleval];
		} else { // even number, calculate avg of 2 medians
			$low = $arr[$middleval];
			$high = $arr[$middleval+1];
			$median = (($low+$high)/2);
		}
		return $median;
	}
	public static function calculateAverage($arr) {
		$count = count($arr); //total numbers in array
		$total = 0;
		foreach ($arr as $value) {
			$total = $total + $value; // total value of array numbers
		}
		$average = ($total/$count); // get average value
		return $average;
	}

	public static function calculateLowest($arr){
		sort($arr);
		return $arr[0];
	}

	public static function calculateHighest($arr){
		sort($arr);
		$count = count($arr);
		return $arr[$count-1];
	}

	public static function calculateStandardDeviation($arr){
		//first calculate the mean
		$mean = SmartPassiveIncome::calculateAverage($arr);

		error_log("Mean: ".$mean);

		//calculate deviations
		for($i = 0; $i < count($arr); $i++){
			$ary_deviations[$i] = $arr[$i] - $mean;
		}

		//error_log("Deviations: ".print_r($ary_deviations));

		//square all deviations
		foreach($ary_deviations as $key => $value){
			$ary_deviations[$key] = $value * $value;
		}

		//error_log("Deviations squared: ".print_r($ary_deviations))	;

		//sum all squared deviations
		$squared_deviations_summed = 0;
		foreach($ary_deviations as $value){
			$squared_deviations_summed += $value;
		}

		//error_log("Deviations squared summ: ".$squared_deviations_summed);

		//divide by one less than the number of items in the list
		$squared_deviations_summed = $squared_deviations_summed/count($ary_deviations);

		//error_log("Deviations divided: ".$squared_deviations_summed);

		//square root of the last number
		$standard_deviation = sqrt($squared_deviations_summed);
		
		error_log("Standard Deviation: ".$standard_deviation);	

		return $standard_deviation;
	}

	/**
	 * Calculates actual mortgage calculations by plotting a PVIFA table
	 * (Present Value Interest Factor of Annuity)
	 *
	 * @param  float  length, in years, of mortgage
	 * @param  float  monthly interest rate
	 * @return float  denominator used to calculate monthly payment
	 */
	public static function getInterestFactor($year_term, $monthly_interest_rate) {	
		$factor      = 0;
		$base_rate   = 1 + $monthly_interest_rate;
		$denominator = $base_rate;
		for ($i=0; $i < ($year_term * 12); $i++) {
			$factor += (1 / $denominator);
			$denominator *= $base_rate;
		}
		return $factor;
	}

	/**
	 * Formats input as string of money ($n.nn)
	 *
	 * @param  float  number
	 * @return string number formatted as US currency
	 */
	public static function money($input, $decimals = true, $sign = true) {		
		//return '$' . number_format($input, "2", ".", ",");
		$dollar_sign = "";
		$decimal = 0;
		$negative = "";
		if($input < 0){
			$negative = "-";
		}
		$clean_input = SmartPassiveIncome::cleanNumber($input);


		if($sign){
			$dollar_sign = "$";
		}
		if($decimals){
			$decimal = 2;
		}
		return $negative.$dollar_sign.number_format($clean_input, $decimal, ".", ",");
	}

	public static function percent($input) {
		//return '$' . number_format($input, "2", ".", ",");
		return number_format($input, "2", ".", ",")."%";
	}

	/**
	 * Cleans input from any non-float charachters
	 *
	 * @param  mixed Any string or number
	 * @return float
	 */
	public static function cleanNumber($input) 
	{
		return (float) preg_replace('/[^0-9.]/', '', $input);
	}

	/**
	 * Calculates monthly mortgage payment
	 *
	 * 
	 */
	public static function calculateMortgage($downPercent, $salePrice, $mortgageInterestPercent, $yearTerm){		
		$downPayment = $salePrice * ($downPercent / 100);
		$financingPrice = $salePrice - $downPayment;
		
		// interest rates
		$annualInterestRate = $mortgageInterestPercent / 100;
		$monthlyInterestRate = $annualInterestRate / 12;

		// Principal & Interest monthly payment: financing & interest numbers from above as well as $year_term (length of mortgage, entered by user)
		$monthlyPayment = $financingPrice / SmartPassiveIncome::getInterestFactor($yearTerm, $monthlyInterestRate);		
		
		// taxes
		//$property_yearly_tax     = ($assessed_value / 1000) * $property_tax_rate;
		//$property_monthly_tax    = $property_yearly_tax / 12;
		
		// PMI, if necessary 
		if ($downPercent < 20) { 
			$pmiPerMonth = SmartPassiveIncome::calculatePMIPerMonth($financingPrice);
		} else {
			$pmiPerMonth = 0;
		}
		
		// Total principal, interest, pmi, taxes, fees
		$mortgageMonthlyPayment = $monthlyPayment + $pmiPerMonth; //+ $property_monthly_tax + $condo_fee;
		$mortgageMonthlyPayment = SmartPassiveIncome::money($mortgageMonthlyPayment, true, false);		

		return $mortgageMonthlyPayment;

	}

	public static function calculatePMIPerMonth($financingPrice){
		//error_log("calculating pmi...");
		$pmiPerMonth  = 55 * ($financingPrice / 100000);
		//error_log("PMI: 55 * ".$financingPrice." / 100000: ".$pmiPerMonth);
		return $pmiPerMonth;
	}

	public static function dateToMonthYearReadable($date){		
		$date = date_format(date_create($date), 'M Y');
		return $date;
	}

	public static function dateToMonthYear($date){		
		$date = date_format(date_create($date), 'm/Y');
		return $date;
	}
}