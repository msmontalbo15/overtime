<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Timeago{

	var $CI;

	public function __construct(){
		$this->CI =& get_instance();
	}


	public function get($time, $tense='ago') {
	    // declaring periods as static function var for future use
	    static $periods = array('year', 'month', 'day', 'hour', 'minute', 'second');

	    // checking time format
	    if(!(strtotime($time)>0)) {
	        return trigger_error("Wrong time format: '$time'", E_USER_ERROR);
	    }

	    // getting diff between now and time
	    $now  = new DateTime('now');
	    $time = new DateTime($time);
	    $diff = $now->diff($time)->format('%y %m %d %h %i %s');
	    // combining diff with periods
	    $diff = explode(' ', $diff);
	    $diff = array_combine($periods, $diff);
	    // filtering zero periods from diff
	    $diff = array_filter($diff);
	    // getting first period and value
	    $period = key($diff);
	    $value  = current($diff);

	    // if input time was equal now, value will be 0, so checking it
	    if(!$value) {
	        $period = 'seconds';
	        $value  = 0;
	    } else {
	        // converting days to weeks
	        if($period=='day' && $value>=7) {
	            $period = 'week';
	            $value  = floor($value/7);
	        }
	        // adding 's' to period for human readability
	        if($value>1) {
	            $period .= 's';
	        }
	    }

	    // returning timeago
	    return "$value $period $tense";
	}





}