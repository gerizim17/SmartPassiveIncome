<?php

class Accounting {	

	public static function calculateVacancyPercent($estimate_rent, $rent){
		return number_format(100-($estimate_rent/$rent)*100)."%";

	}

	public static function createIncomeAry($renttier, $vacancy_percent, $estimate, $title){

		$ary = array(
            trans("general.gpi") => ($title=="Yearly Income Statement")?$renttier->rent*12:$renttier->rent,
            trans("general.vac", array("vacancy" => $vacancy_percent)) => ($title=="Yearly Income Statement")?($renttier->rent - $estimate->rent)*12:$renttier->rent - $estimate->rent,
            trans("general.egi") => ($title=="Yearly Income Statement")?$estimate->rent*12:$estimate->rent,
            trans("general.oi") => 0,
            trans("general.goi") => ($title=="Yearly Income Statement")?$estimate->rent*12:$estimate->rent,
        );

        return $ary;
	}

	public static function createExpensesAry($fixedexpense, $estimate, $title){

		$ary = array(
            "general.propertytaxes" => ($title=="Yearly Income Statement")?$fixedexpense->taxes*12:$fixedexpense->taxes,
            "general.insurance" => ($title=="Yearly Income Statement")?$fixedexpense->insurance*12:$fixedexpense->insurance,
            "general.utilities" => ($title=="Yearly Income Statement")?$fixedexpense->utilities*12:$fixedexpense->utilities,
            "general.otherexpenses" => ($title=="Yearly Income Statement")?$fixedexpense->misc*12:$fixedexpense->misc,
            "general.repairs" => ($title=="Yearly Income Statement")?$estimate->repairs*12:$estimate->repairs,
            "general.propertymanagement" => ($title=="Yearly Income Statement")?($estimate->variable_expenses-$estimate->repairs)*12:$estimate->variable_expenses-$estimate->repairs,
        ); 

        return $ary;
	}

	public static function createIncomeStatement($realestate_id, $debt_service, $title, $mode, $ary_income, $ary_expenses){
		$total_operating_expenses = 0;		
		$mortgage = Mortgage::getByReId($realestate_id);
		$roi = Returnoninvestment::getByReId($realestate_id);		
		$price = $mortgage->sale_price;
		
        $renttier = Renttier::getByReId($realestate_id);        
        $fixedexpense = Fixedexpense::getByReId($realestate_id);
        if($mode=="average"){
        	$estimate = Estimate::getByReId($realestate_id);
        } else if($mode=="best") {
        	$estimate = Estimatebest::getByReId($realestate_id);
        } else if($mode=="worst") {
        	$estimate = Estimateworst::getByReId($realestate_id);
        }

        //Estimate only gets set when building income statements for Montecarlo. Estimate does not get set for Rental HIstory
        if(isset($estimate)){
        	$vacancy_percent = Accounting::calculateVacancyPercent($estimate->rent, $renttier->rent);
    		$ary_income = Accounting::createIncomeAry($renttier, $vacancy_percent, @$estimate, $title);
    		$ary_expenses = Accounting::createExpensesAry($fixedexpense, @$estimate,  $title);    
    	}

        $effective_gross_income = SmartPassiveIncome::cleanNumber($ary_income["Effective Gross Income"]);
		$gross_potential_income = SmartPassiveIncome::cleanNumber($ary_income["Gross Potential Income"]);

		?>
			<h3><?php echo $title; ?></h3>						
			<table class="table table-condensed">
				<tr><th>Income</th></tr>
				<?php
					foreach($ary_income as $name => $value){?>
						<tr>
							<td style="padding-left:20px"><?php echo $name; ?></td>
							<td align="right"><?php echo SmartPassiveIncome::money($value, false, false);?></td>
						</tr>
					<?php						
					}
				?>
				<tr class="active">
					<td style="padding-left:40px">Total Income</td>
					<td align="right"><?php echo SmartPassiveIncome::money($effective_gross_income, false);?></td>
				</tr>
				<tr><th>Expenses</th></tr>
				<?php
					foreach($ary_expenses as $name => $value){?>
						<tr>
							<td style="padding-left:20px"><?php echo trans($name); ?></td>
							<td align="right"><?php echo SmartPassiveIncome::money($value, false, false); ?></td>
						</tr>
					<?php
						$total_operating_expenses += $value*1;
					}
				?>	
				<tr class="active">
					<td style="padding-left:40px">Total Operating Expenses</td>
					<td align="right"><?php echo SmartPassiveIncome::money($total_operating_expenses, false);?></td>
				</tr>
				<tr><td>&nbsp;</td></tr>		
				<tr class="active">
					<td>Net Operating Income</td>
					<td align="right"><?php echo SmartPassiveIncome::money($effective_gross_income - $total_operating_expenses, false);?></td>
				</tr>

				<tr>
					<td>Debt Service</td>
					<td align="right"><?php echo SmartPassiveIncome::money($debt_service, false);?></td>
				</tr>
				
				<tr class="<?php echo ($effective_gross_income - $total_operating_expenses - $debt_service > 0)?'success':'danger' ?>">
					<td>Before-Tax Casfh Flow</td>
					<td align="right"><?php echo SmartPassiveIncome::money($effective_gross_income - $total_operating_expenses - $debt_service, false);?></td>
				</tr>

				<tr class="active">
					<?php if($title!="Monthly Income Statement" && $title!="Avg. Income Statement"){ ?>
						<td><?php echo trans("general.caprate");?></td>					
						<td align="right"><?php echo SmartPassiveIncome::percent(((($effective_gross_income - $total_operating_expenses)) / $price)*100);?></td>
					<?php } else { echo "<td>&nbsp;</td><td>&nbsp;</td>";} ?>
				</tr>
				<tr class="active">
					<?php if($title!="Monthly Income Statement" && $title!="Avg. Income Statement"){ ?>
						<td><?php echo trans("general.grm");?></td>
						<td align="right"><?php echo SmartPassiveIncome::percent($price / ($gross_potential_income));?></td>
						<?php } else { echo "<td>&nbsp;</td><td>&nbsp;</td>";} ?>
				</tr>
				<?php 
					$cashoncash = ((($effective_gross_income - $total_operating_expenses - $debt_service)) / $roi->init_investment)*100;
					if($title=="Monthly Income Statement" || $title=="Avg. Income Statement") {$cashoncash *= 12;}
				?>
				<tr class="active">
					<td><?php echo ($title!="Monthly Income Statement" && $title!="Avg. Income Statement")?trans("general.cashoncash"):trans("general.projectedcashoncash");?></td>
					<td align="right"><?php echo SmartPassiveIncome::percent($cashoncash);?></td>
				</tr>

				
			</table>
      	<?php		        
	}	
		
}