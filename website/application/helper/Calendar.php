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

        return $this->create_view();
    }

    public function getDaysInMonth($month, $year) {

        if (($month == 9) || ($month == 4) ||
			($month == 6) || ($month == 11) ) {
			return 30;
		}

		if (($this->month != 2)) { return 31; }

		if ( ( (($year % 4) == 0) && (($year % 100) != 0) ) ||
			 ( ($year % 400) == 0 )) {
			return 29;
		}

		return 28;
    }

    public function getPrevDate() {
        if ($this->month == 1) {
            return array('12', ($this->year - 1));
    	}
        return array(($this->month - 1), $this->year);
    }

    public function getNextDate() {
        if ($this->month == 12) {
            return array('1', ($this->year + 1));
        }
        return array(($this->month + 1), $this->year);
    }

    public function getWeekLabel() {
        if ($this->lang == 'en') {
            return array('Sun', 'Mon', 'Tue', 'Wed', 'Thur', 'Fri', 'Sat');
        } else {
            return array('일요일', '월요일', '화요일', '수요일', '목요일', '금요일', '토요일');
        }
    }

    public function create_view() {

        // date list
        $date_list = array();

        list($pre_month, $pre_year) = $this->getPrevDate();
        list($next_month, $next_year) = $this->getNextDate();

    	$preview_month_day = $this->getDaysInMonth($pre_month, $pre_year);
    	$current_month_day = $this->getDaysInMonth($this->month, $this->year);

    	$pre_month = ($pre_month > 9) ? $pre_month : '0'.$pre_month;
    	$next_month = ($next_month > 9) ? $next_month : '0'.$next_month;

    	$start_day = ((int)date('w', strtotime($this->month . '/01/' . $this->year )) + 1);

        for($i = 1, $j = 1; $i <= 42; $i++) {
            if ($i < $start_day) {
                array_push($date_list, $pre_year.'-'.$pre_month.'-'.($preview_month_day - ($start_day - $i) + 1));
            } else if (($i - $start_day + 1) <= $current_month_day) {
                $day_orgn = ($i - $start_day + 1);
				$day_orgn2 = ($day_orgn<10) ? '0'.$day_orgn : $day_orgn;
                array_push($date_list, $this->year.'-'.$this->month.'-'.$day_orgn2);
            } else {
                $d = ($j >= 10) ? $j : '0'.$j;
                $j++;
                array_push($date_list, $next_year.'-'.$next_month.'-'.$d);
            }
        }

        return $date_list;
    }
}
