<?php
	function days_in_month($_m, $_y) {

		if (($_m == 9) ||
			($_m == 4) ||
			($_m == 6) ||
			($_m == 11) ) {
			return 30;
		}

		if (($_m != 2)) {
			return 31;
		}

		if ( ( (($_y % 4) == 0) && (($_y % 100) != 0) ) ||
			 ( ($_y % 400) == 0 )) {
			return 29;
		}

		return 28;
	}

	$month = (isset($_GET['month']) ) ? $_GET['month'] : date('m');
	$year = (isset($_GET['year']) ) ? $_GET['year'] : date('Y');

	if ($month == 1) {
		$pre_month = '12';
		$pre_year = ($year - 1);
	} else {
		$pre_month = ($month - 1);
		$pre_year = $year;
	}

	if ($month == 12) {
		$next_month = '1';
		$next_year = ($year + 1);
	} else {
		$next_month = ($month + 1);
		$next_year = $year;
	}

	$preview_month_day = days_in_month($pre_month, $pre_year);
	$current_month_day = days_in_month($month, $year);

	$pre_month = ($pre_month > 9) ? $pre_month : '0'.$pre_month;
	$next_month = ($next_month > 9) ? $next_month : '0'.$next_month;

	$start_day = ((int)date('w', strtotime($month . '/01/' . $year )) + 1);
?>
<div class="portlet-body" style="padding-top: 0px;">
	<div class="calender-container">
		<table>
			<thead>
				<th>일요일</th>
				<th>월요일</th>
				<th>화요일</th>
				<th>수요일</th>
				<th>목요일</th>
				<th>금요일</th>
				<th>토요일</th>
			</thead>
			<tbody>
				<?php for($i = 1, $j = 1; $i <= 42; $i++) { ?>
					<?php if ( ($i == 0) || ($i == 8) || ($i == 15) || ($i == 22) || ($i == 29) || ($i == 36) ){ ?>
						<tr>
					<?php } ?>
						<?php if ($i < $start_day) { ?>
							<td>
								<?php calender_item( ($preview_month_day - ($start_day - $i) + 1), 'another-month', '0', '0' ); ?>
							</td>
						<?php } else if (($i - $start_day + 1) <= $current_month_day) { ?>
							<td>
								<?php
									$day_orgn = ($i - $start_day + 1);
									$day_orgn2 = ($day_orgn<10) ? '0'.$day_orgn : $day_orgn;
									$thedate = $year.'-'.$month.'-'.$day_orgn2;

									$signup	= $this->connector_m->get('member', array('%created_date_time%' => $thedate, 'status' => 'A', 'count' =>  true));
									$today_purchase	= $this->connector_m->get('purchase', array('%created_date_time%' => $thedate, 'select' => 'idx,total_cost,processing_status', 'status' => 'A' ), 'list');
									$total_cost = 0;
									foreach($today_purchase as $purchase) {
										if ( ($purchase->processing_status != '0') && ($purchase->processing_status <= 500 ) ) {
											$total_cost += $purchase->total_cost;
										}
									}
								?>
								<?php calender_item( $day_orgn, '', array(
									'signup'		=> $signup,
									'purchase_cnt'	=> count($today_purchase),
									'total_cost'	=> $total_cost
								));  ?>
							</td>
						<?php } else { ?>
							<td>
								<?php calender_item( $j++, 'another-month', '0', '0' ); ?>
							</td>
						<?php } ?>
					<?php if ( ($i == 7) || ($i == 14) || ($i == 21) || ($i == 28) || ($i == 35) || ($i == 42) ){ ?>
						</tr>
					<?php } ?>
				<?php } ?>
			</tbody>
		</table>
	</div>
</div>
<?php
	function calender_item($date, $another_month, $options) {
?>
	<div class="date_number <?php echo $another_month; ?>">
		<?php echo $date; ?>
		<div class="inside-tem" style="font-size: 12px; text-align: left; padding-bottom: 5px; padding-top: 3px;">
			<div <?php if ( $another_month == '' ) { ?> style=" border-top: 1px solid #efefef; padding-top: 5px; padding-bottom: 5px;"<?php } ?>>
				새가입자
				<div class="pull-right">
					<?php echo number_format($options['signup']); ?>  명
				</div>
			</div>
			<div <?php if ( $another_month == '' ) { ?> style=" border-top: 1px solid #efefef; padding-top: 5px; padding-bottom: 5px;"<?php } ?>>
				결제건수
				<div class="pull-right">
					<?php echo number_format($options['purchase_cnt']); ?> 개
				</div>
			</div>
			<div <?php if ( $another_month == '' ) { ?> style=" border-top: 1px solid #efefef; padding-top: 5px; padding-bottom: 5px;"<?php } ?>>
				결제금액
				<div class="pull-right">
					<?php echo number_format($options['total_cost']); ?> 원
				</div>
			</div>
		</div>
	</div>
<?php
	}
?>
