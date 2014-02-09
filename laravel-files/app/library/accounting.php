<?php

class Accounting {	

	public static function createIncomeStatement($realestate_id, $ary_income, $ary_expenses, $debt_service, $title = "Monthly Income Statement"){
		$total_operating_expenses = 0;		
		$mortgage = Mortgage::getByReId($realestate_id);
		$roi = Returnoninvestment::getByReId($realestate_id);		
		$price = $mortgage->sale_price;
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
				
				<tr class="success">
					<td>Before-Tax Casfh Flow</td>
					<td align="right"><?php echo SmartPassiveIncome::money($effective_gross_income - $total_operating_expenses - $debt_service, false);?></td>
				</tr>

				<tr class="active">
					<?php if($title!="Monthly Income Statement" && $title!="Averaged Income Statement"){ ?>
						<td><?php echo trans("general.caprate");?></td>					
						<td align="right"><?php echo SmartPassiveIncome::percent(((($effective_gross_income - $total_operating_expenses)) / $price)*100);?></td>
					<?php } else { echo "<td>&nbsp;</td><td>&nbsp;</td>";} ?>
				</tr>
				<tr class="active">
					<?php if($title!="Monthly Income Statement" && $title!="Averaged Income Statement"){ ?>
						<td><?php echo trans("general.grm");?></td>
						<td align="right"><?php echo SmartPassiveIncome::percent($price / ($gross_potential_income));?></td>
						<?php } else { echo "<td>&nbsp;</td><td>&nbsp;</td>";} ?>
				</tr>
				<?php 
					$cashoncash = ((($effective_gross_income - $total_operating_expenses - $debt_service)) / $roi->init_investment)*100;
					if($title=="Monthly Income Statement" || $title=="Averaged Income Statement") {$cashoncash *= 12;}
				?>
				<tr class="active">
					<td><?php echo ($title!="Monthly Income Statement" && $title!="Averaged Income Statement")?trans("general.cashoncash"):trans("general.projectedcashoncash");?></td>
					<td align="right"><?php echo SmartPassiveIncome::percent($cashoncash);?></td>
				</tr>

				
			</table>
      	<?php		        
	}	
		
}