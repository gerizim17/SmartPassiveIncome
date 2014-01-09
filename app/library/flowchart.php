<?php

class Flowchart {

	public static function drawColumnChart($re_id, $container_id) {
		$estimate = Estimate::getByReId($re_id);
		$fixedexpenses = Fixedexpense::getByReId($re_id);
		$mortgage = Mortgage::getByReId($re_id);
		?>		

		<script type="text/javascript">
			$(function () {
			    var chart;
			    var currentTerm = 1;
			    $(document).ready(function() {

			    	// Radialize the colors
					// Highcharts.getOptions().colors = Highcharts.map(Highcharts.getOptions().colors, function(color) {
					//     return {
					//         radialGradient: { cx: 0.5, cy: 0.3, r: 0.7 },
					//         stops: [
					//             [0, color],
					//             [1, Highcharts.Color(color).brighten(-0.3).get('rgb')] // darken
					//         ]
					//     };
					// });

			        /*************************************************
					   PIE CHART DATA
	      			**************************************************/

			        chart = new Highcharts.Chart({
			            chart: {
			                renderTo: '<?php echo $container_id ?>',
			                plotBackgroundColor: null,
			                plotBorderWidth: null,
			                animation: {
	             			   duration: 1000,
	             			   easing: 'swing'
	            			},
			                plotShadow: false
			            },
			            title: {
			                text: 'Monthly breakdown'
			            },
			            subtitle: {
				            text: 'Monthly: $<?php echo number_format($estimate->est_cashflow)."<br /> Yearly: $".number_format($estimate->est_cashflow*12);?>'
				        },
			            tooltip: {
			        	    pointFormat: '{series.name}: <b>${point.y}</b>',
			            	percentageDecimals: 1
			            },
			            plotOptions: {
			                pie: {
			                    allowPointSelect: true,
			                    cursor: 'pointer',
			                    dataLabels: {
			                        enabled: true,
			                        color: '#000000',
			                        connectorColor: '#000000',
			                        formatter: function() {
			                            return '<b>'+ this.point.name +'</b>:<br /> $'+ this.point.y;
			                        }
			                    }
			                }
			            },
			            series: [{
			                type: 'pie',
			                name: 'Revenue Percent',
			                data: [
			                    ['Repairs', <?php echo number_format($estimate->est_repairs); ?>],
			                    ['Property Management', <?php echo number_format($estimate->est_variable_expenses-$estimate->est_repairs); ?>],
						        ['Fixed Expenses', <?php echo number_format($estimate->est_fixed_expenses-$fixedexpenses->fe_taxes-$fixedexpenses->fe_insurance-$mortgage->mg_monthly_payment); ?>],
						        ['PITI', <?php echo number_format($fixedexpenses->fe_taxes+$fixedexpenses->fe_insurance+$mortgage->mg_monthly_payment); ?>],
						        ['Income', <?php echo number_format($estimate->est_cashflow); ?>]
			                ]
			            }]
			        });
			   	});
			});
		</script>
	<?php
	}

	public static function drawPieChart($re_id, $container_id) {
		$estimate = Estimate::getByReId($re_id);
		$fixedexpenses = Fixedexpense::getByReId($re_id);
		$mortgage = Mortgage::getByReId($re_id);
		$roi = Returnoninvestment::getByReId($re_id);

		?>
		<script type="text/javascript">
			$(function () {
			    var chart;
			    var currentTerm = 1;
			    $(document).ready(function() {

			    	var options = {
				    chart: {
				        renderTo: '<?php echo $container_id; ?>',
				        defaultSeriesType: 'column'
				    },
				    title: {
				        text: '<?php echo "test" ?>'
				    },
				    xAxis: {
				        categories:[]
				    },
				    yAxis: {
				        title: {
				            text: 'Money'
				        }
				    },
				    plotOptions: {
		                series: {
		                    stacking: 'normal'
		                },
		            },
		            tooltip:{		            	
		            	
		            },
		            series: [{		            	
		                name: 'Investment',
		                color: '#AA4643'
		            }, {
		                name: 'Repayed',	
		                color:'#4572A7'	                
		            }]	
				};

				<?php 
		         	$total_income = 0;
		         	$years_passed = 0;
		         	$ary_repayed = "[";
		         	$ary_roi_init_investment= "[";
					$ary_axis = "[";
					$roi_init_investment = $roi->roi_init_investment;
					$medianYearlyIncome = $estimate->est_cashflow*12;
					$medianYearlyROI = $estimate->est_roi;
		         	 // set up the two data series
                	//echo "total_returned = [];";
                	//echo "total_investment_left = [];";

		         	while($total_income <= $roi_init_investment){
		         		$total_income += $medianYearlyIncome;
		         		$total_investment_left = $roi_init_investment-$total_income;

		         		//if total income exceed initial investment we set investment left to 0 so the graph doesn't show a negative value
		         		if($total_income >= $roi_init_investment){
		         				$total_investment_left = 0;
		         		}
		         		$total_income_show = $total_income;		
		         		
		         		//echo "['".++$years_passed." Year (".$medianYearlyROI*$years_passed."% repayed)', ".$total_income_show.", ".$total_investment_left."]";
		         		$ary_repayed .= number_format($total_income_show, 2, '.', '');		         		
		         		$ary_roi_init_investment .= number_format($total_investment_left, 2, '.', '');
		         		$ary_axis .= "'".++$years_passed." Year (".number_format($medianYearlyROI*$years_passed)."% repayed)'";
		         		
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

		         	$medianYearlyIncomeAlternate = $estimate->est_cashflow2*12;
					$medianYearlyROIAlternate = $estimate->est_roi2;

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
		         		$ary_axisAlternate .= "'".++$years_passed2." Year (".number_format($medianYearlyROIAlternate*$years_passed2)."% repayed)'";
		         		
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
		         ?>
				
		      	options.series[1].data =  <?php echo $ary_repayed; ?>; //Repayed
				options.series[0].data = <?php echo $ary_roi_init_investment; ?>; //Initial Investment

		        var chart2 = new Highcharts.Chart(options);
		       	chart2.setTitle({ text: "Your investment will be repayed in"});
       			chart2.setTitle(null, { text: "<?php echo $years_passed; ?> year(s) and <?php echo $total_months; ?> months"});
				chart2.xAxis[0].setCategories(<?php echo $ary_axis; ?>);
			    
		   
			   	});
			});
    
		</script>
			<?php

	}

	public static function drawGoalsCharts($container_id) {

		$rentalhistories = Rentalhistory::all();

		$goal = 25000;
		$first_year;	
		$ary_yearly_cashflows = array();
		$total_cashflow = array();
		foreach ($rentalhistories as $key => $value) {
			$current_year = substr($value['rh_date'],0,4);				
			if(!isset($first_year)){	$first_year = $current_year; }
			if(!isset($ary_yearly_cashflows[$value['rh_re_id']]['years'][$current_year])){
				$ary_yearly_cashflows[$value['rh_re_id']]['years'][$current_year] = 0;
			}			
			if(!isset($total_cashflow[$current_year])){
				$total_cashflow[$current_year] = 0;
			}			
			$realestate = Realestate::find($value['rh_re_id']);
			$ary_yearly_cashflows[$value['rh_re_id']]['years'][$current_year] += number_format($value['rh_cashflow'], '0', '.', '');
			$ary_yearly_cashflows[$value['rh_re_id']]['rh_address'] = $realestate->re_address1;
			$total_cashflow[$current_year] += number_format($value['rh_cashflow'], '0', '.', '');
		}		
		?>
		<script type="text/javascript">
		//var container_id = 
	    $(function () {
	        $('#<?php echo $container_id; ?>').highcharts({
	            chart: {
	                type: 'bar'
	            },
	            title: {
	                text: 'Annual Goal: $<?php echo $goal."<br />Current Total: $".$total_cashflow[$first_year];?>'
	            },
	            xAxis: {
	                categories: [
	                 <?php 	
	                $comma = false;
	                $top_year = $first_year;      	               
	                while($current_year <= $top_year){	
	                	if($comma){
							echo ",";
						}
	                	echo "'".$top_year."'";
	                	$comma = true;
	                	$top_year--;
	                	if($top_year < 0){
	                		break;
	                	}

	                }
	                $top_year = $first_year;
	                ?>
	                ]
	            },
	            yAxis: {
	                min: 0,
	                title: {
	                    text: 'Total Cashflow'
	                }
	            },
	            legend: {
	                backgroundColor: '#FFFFFF',
	                reversed: true
	            },
	            plotOptions: {
	                series: {
	                    stacking: 'normal'
	                }
	            },
	                series: [{
	                name: 'To Go',
	                data: [
	                <?php 	
	                $top_year = $first_year; 
	                $comma = false;             
	                while($current_year <= $top_year){           	
	                	if($comma){
							echo ",";
						}
	                	echo $goal-$total_cashflow[$top_year];
	                	$comma = true;
	                	$top_year--;
	                	if($top_year < 0){
	                		break;
	                	}
	                }
	                $top_year = $first_year;
	                ?>
	                ]
	            }

	           	<?php 
	           	foreach ($ary_yearly_cashflows as $rental_history) {
					echo ",{";
	                echo "name: '".$rental_history['rh_address']."',";
	                echo "data: [";
	                	$comma = false;
	                	foreach ($rental_history['years'] as $key2 => $value2) {			
	                		if($comma){
								echo ",";
							}
							echo number_format($value2, '0', '.', '');
							$comma = true;
						}	
						
	                echo "]";
	                echo "}"; 					
				}	           
				?>
	            ]
	        });
	    });	   
    </script>  
		<?php
	}

	public static function updateColumnChart(){
		//for the 15 vs 30 yr mortgage comparison
	}

	public static function updatePieChart(){

	}

}