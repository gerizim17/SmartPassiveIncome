<?php

class Flowchart {

	public static function drawPieChart($realestate_id, $container_id, $mode) {				
		if($mode == "best"){
			$estimate = Estimatebest::getByReId($realestate_id);				
		} else if($mode == "worst"){
			$estimate = Estimateworst::getByReId($realestate_id);		
		} else  if($mode == "median"){
			$estimate = Estimate::getByReId($realestate_id);		
		}
		
		$fixedexpenses = Fixedexpense::getByReId($realestate_id);
		$mortgage = Mortgage::getByReId($realestate_id);		
		?>		

		<script type="text/javascript">
			$(function () {
			    var chart;
			    var currentTerm = 1;
			    $(document).ready(function() {

			  //   	Radialize the colors
					// Highcharts.getOptions().colors = Highcharts.map(Highcharts.getOptions().colors, function(color) {
					//     return {
					//         radialGradient: { cx: 0.5, cy: 0.3, r: 0.7 },
					//         stops: [
					//             [0, color],
					//             [1, Highcharts.Color(color).brighten(-0.3).get('rgb')] // darken
					//         ]
					//     };
					// });

			// Make monochrome colors and set them as default for all pies
					Highcharts.getOptions().plotOptions.pie.colors = (function () {
			            var colors = [],
			                base = Highcharts.getOptions().colors[0],
			                i

			            for (i = 0; i < 10; i++) {
			                // Start out with a darkened base color (negative brighten), and end
			                // up with a much brighter color
			                colors.push(Highcharts.Color(base).brighten((i - 3) / 7).get());
			            }
			            return colors;
					}());

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
				            text: 'Monthly: $<?php echo number_format($estimate->cashflow)."<br /> Yearly: $".number_format($estimate->cashflow*12);?>'
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
			                    ['Repairs', <?php echo number_format($estimate->repairs); ?>],
			                    ['Property Management', <?php echo number_format($estimate->variable_expenses-$estimate->repairs); ?>],			                   
						        ['Fixed Expenses', <?php echo number_format($estimate->fixed_expenses); ?>],
						        ['Debt Service', <?php echo number_format($mortgage->monthly_payment); ?>],
						        ['Income', <?php echo number_format($estimate->cashflow); ?>]
			                ]
			            }]
			        });
			   	});
			});
		</script>
	<?php
	}

	public static function drawColumnChart($realestate_id, $container_id, $mode) {
		if($mode == "best"){
			$estimate = Estimatebest::getByReId($realestate_id);		
		} else if($mode == "worst"){
			$estimate = Estimateworst::getByReId($realestate_id);		
		} else  if($mode == "median"){
			$estimate = Estimate::getByReId($realestate_id);		
		}
		$fixedexpenses = Fixedexpense::getByReId($realestate_id);
		$mortgage = Mortgage::getByReId($realestate_id);
		$roi = Returnoninvestment::getByReId($realestate_id);

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
		                color: '#910000'
		            }, {
		                name: 'Repayed',	
		                color:'#5891C8'	                
		            }]	
				};

				<?php 
		         	$total_income = 0;
		         	$years_passed = 0;
		         	$ary_repayed = "[";
		         	$ary_init_investment= "[";
					$ary_axis = "[";
					$init_investment = $roi->init_investment;
					$medianYearlyIncome = $estimate->cashflow*12;
					$medianYearlyROI = $estimate->roi;
		         	 // set up the two data series
                	//echo "total_returned = [];";
                	//echo "total_investment_left = [];";
                	//error_log("estcash: ".$estimate->cashflow);
					if($estimate->cashflow > 0){						
			         	while($total_income <= $init_investment){
			         		$total_income += $medianYearlyIncome;
			         		$total_investment_left = $init_investment-$total_income;

			         		//if total income exceed initial investment we set investment left to 0 so the graph doesn't show a negative value
			         		if($total_income >= $init_investment){
			         				$total_investment_left = 0;
			         		}
			         		$total_income_show = $total_income;		
			         		
			         		//echo "['".++$years_passed." Year (".$medianYearlyROI*$years_passed."% repayed)', ".$total_income_show.", ".$total_investment_left."]";
			         		$ary_repayed .= number_format($total_income_show, 2, '.', '');		         		
			         		$ary_init_investment .= number_format($total_investment_left, 2, '.', '');
			         		$ary_axis .= "'".++$years_passed." Year (".number_format($medianYearlyROI*$years_passed)."% repayed)'";
			         		
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
		          	}

		          	if($estimate->cashflow2 > 0){
		          		//CALCULATE ALTERNATE
			          	$ary_repayedAlternate = "[";
			         	$ary_init_investmentAlternate= "[";
			         	$ary_axisAlternate = "[";
			         	$total_income2 = 0;
			         	$years_passed2 = 0;

			         	$medianYearlyIncomeAlternate = $estimate->cashflow2*12;
						$medianYearlyROIAlternate = $estimate->roi2;

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
			         		$ary_axisAlternate .= "'".++$years_passed2." Year (".number_format($medianYearlyROIAlternate*$years_passed2)."% repayed)'";
			         		
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
		          	}
		         ?>
		         <?php
				if($estimate->cashflow > 0){ ?>
			      	options.series[1].data =  <?php echo $ary_repayed; ?>; //Repayed
					options.series[0].data = <?php echo $ary_init_investment; ?>; //Initial Investment

			        var chart2 = new Highcharts.Chart(options);
			       	chart2.setTitle({ text: "Your investment will be repayed in"});
	       			chart2.setTitle(null, { text: "<?php echo $years_passed; ?> year(s) and <?php echo $total_months; ?> months"});
					chart2.xAxis[0].setCategories(<?php echo $ary_axis; ?>);
				<?php } ?>
			    
		   
			   	});
			});
    
		</script>
			<?php

	}

	public static function drawGoalsCharts($container_id) {

		$rentalhistories = Rentalhistory::orderBy('date', 'DESC')->get();

		$goal = 25000;
		$first_year;	
		$ary_yearly_cashflows = array();
		$total_cashflow = array();
		foreach ($rentalhistories as $key => $value) {
			$current_year = substr($value['date'],0,4);				
			if(!isset($first_year)){	$first_year = $current_year; }
			if(!isset($ary_yearly_cashflows[$value['realestate_id']]['years'][$current_year])){
				$ary_yearly_cashflows[$value['realestate_id']]['years'][$current_year] = 0;
			}			
			if(!isset($total_cashflow[$current_year])){
				$total_cashflow[$current_year] = 0;
			}			
			$realestate = Realestate::find($value['realestate_id']);
			$ary_yearly_cashflows[$value['realestate_id']]['years'][$current_year] += number_format($value['cashflow'], '0', '.', '');
			$ary_yearly_cashflows[$value['realestate_id']]['address'] = $realestate->address1;
			$total_cashflow[$current_year] += number_format($value['cashflow'], '0', '.', '');
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
	                echo "name: '".$rental_history['address']."',";
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