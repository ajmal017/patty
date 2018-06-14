<?php

class Calendar {

    static $singleton;
    var $year = 1991;
    var $month = 2;
    var $lang = 'en';

    public static function new() {
        if ( Calendar::$singleton == null) {
			Calendar::$singleton = new Calendar();
		}
		return Calendar::$singleton;
    }

    public function generate($month, $year) {
        $this->month    = $month;
        $this->year     = $year;


    }

    public function getDaysInMonth() {

        if (($this->month == 9) || ($this->month == 4) ||
			($this->month == 6) || ($this->month == 11) ) {
			return 30;
		}

		if (($this->month != 2)) { return 31; }

		if ( ( (($this->year % 4) == 0) && (($this->year % 100) != 0) ) ||
			 ( ($this->year % 400) == 0 )) {
			return 29;
		}

		return 28;
    }

    public function getFrontBackMonth() {

    	if ($this->month == 1) {
    		$pre_month = '12';
    		$pre_year = ($this->year - 1);
    	} else {
    		$pre_month = ($this->month - 1);
    		$pre_year = $this->year;
    	}

    	if ($this->month == 12) {
    		$next_month = '1';
    		$next_year = ($this->year + 1);
    	} else {
    		$next_month = ($this->month + 1);
    		$next_year = $this->year;
    	}

    	$preview_month_day = days_in_month($pre_month, $pre_year);
    	$current_month_day = days_in_month($month, $year);

    	$pre_month = ($pre_month > 9) ? $pre_month : '0'.$pre_month;
    	$next_month = ($next_month > 9) ? $next_month : '0'.$next_month;

    	$start_day = ((int)date('w', strtotime($month . '/01/' . $year )) + 1);
    }

    public function getWeekLabel() {
        if ($this->lang == 'en') {
            return array('Sun', 'Mon', 'Tue', 'Wed', 'Thur', 'Fri', 'Sat');
        } else {
            return array('일요일', '월요일', '화요일', '수요일', '목요일', '금요일', '토요일');
        }
    }

    private function day_view($date, $another_month, $options) {
        $html  = "</div>";
        $html .= "</div>";
        return $html;
    }

    public function create_view() {
        $html = '';


        $html .= '<div class="auto-cal">';
        $html .= '<table>';
        $html .= '<thead>';
        foreach($this->getWeekLabel() as $label) {
            $html .= '<th>$label</th>';
        }
        $html .= '</thead>';
        $html .= '<tbody>';

        for($i = 1, $j = 1; $i <= 42; $i++) {
            if ( ($i == 0) || ($i == 8) || ($i == 15) || ($i == 22) || ($i == 29) || ($i == 36) ) {
                $html .= '<tr>';
            }
            if ($i < $start_day) {
                $html .= $this->day_view( ($preview_month_day - ($start_day - $i) + 1), 'another-month', '0', '0' );
            } else if (($i - $start_day + 1) <= $current_month_day) {
                $html .= $this->day_view( $day_orgn, '', array(
                    'signup'		=> $signup,
                    'purchase_cnt'	=> count($today_purchase),
                    'total_cost'	=> $total_cost
                ));
            } else {
                $html .= $this->day_view( $j++, 'another-month', '0', '0' );
            }
            if ( ($i == 7) || ($i == 14) || ($i == 21) || ($i == 28) || ($i == 35) || ($i == 42) ){
                $html .= '</tr>';
            }
        }

        $html .= '</tbody>';
        $html .= '</table>';
        $html .= '</div>';
        $html .= '<!--/.auto-cal-->';
        return $html;
    }
}
