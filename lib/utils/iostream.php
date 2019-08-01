<?php

/*
 * ******************************************************************************

  Developed by Daniel Jordao Santana (daniel.js@gmail.com)
  Copyright (c) 2017 - Zillius Solutions (www.zillius.com.br)
  Code changes not allowed, doing so will lose warranty of its functionality!

  All rights reserved.

 * ******************************************************************************
 */

abstract class CurrencyHelper
{
	public static function format($value)
	{
		if(is_numeric($value))
			return number_format($value, 2, ',', '.');
	}

	public static function convert_mask($value)
	{
		$value = str_replace("_", "", $value);
		$value = str_replace(".", "", $value);
		$value = str_replace(",", ".", $value);		

		return $value;
	}

}

abstract class StringHelper
{

	public static function ucname($string, $delimiters = array(' ', '-', '\'')) {
	    $string =ucwords(strtolower($string));

	    foreach (array('-', '\'') as $delimiter) {
	      if (strpos($string, $delimiter)!==false) {
	        $string =implode($delimiter, array_map('ucfirst', explode($delimiter, $string)));
	      }
	    }
	    return $string;
	}	

	public static function mask($val, $mask)
	{
		if($val == "")
			return;
		
		$masked = '';
		$k = 0;

		for($i = 0; $i<=strlen($mask)-1; $i++)
		{
			if($mask[$i] == '#')
			{
				if(isset($val[$k]))
					$masked .= $val[$k++];
			}
			else
			{
				if(isset($mask[$i]))
					$masked .= $mask[$i];
			}
		}

		return $masked;
	}

	public static function remove_mask($value)
	{
		$value = str_replace("_", "", $value);

		return $value;
	}

    public static function underscore_to_camel($string, $first_char_caps = false)
    {
        if ($first_char_caps == true)
        {
            $string[0] = strtoupper($string[0]);
        }

        $func = create_function('$c', 'return strtoupper($c[1]);');
        return preg_replace_callback('/_([a-z])/', $func, $string);
    }

	
	public static function get_youtube_id($link)
	{
		
		$link = str_replace("http://", "", $link);
		$link = str_replace("www.", "", $link);
		$link = str_replace("youtube.com", "", $link);
		$link = str_replace("youtu.be", "", $link);
		$link = str_replace("/watch?v=", "", $link);
		$link = str_replace("/", "", $link);
				
		return $link;

	}
	
	
	public static function make_random_string ($length = 8)
	{
	
		$string = "";
		
		$possible = "12346789bcdfghjkmnpqrtvwxyzBCDFGHJKLMNPQRTVWXYZ";
		
		$maxlength = strlen($possible);
		
		if ($length > $maxlength)
		$length = $maxlength;
		
		$i = 0; 
		
		while ($i < $length)
		{ 
			
			$char = substr($possible, mt_rand(0, $maxlength-1), 1);
			
			if (!strstr($string, $char))
			{ 
				$string .= $char;
				$i++;
			}
		
		}
		
		return $string;
	
	}	
	
	public static function left($var, $length)
	{

		return (substr($var, 0, $length));

	}
	
	public static function right($var, $length, $use_dots = false)
	{

		if(strlen($var) < $length)
		{
			return $var;
		}
		else
		{
			$ret_str  = ($use_dots) ? "..." : ""; 

			$startpos = strlen($var) - $length;			
			$ret_str .=(substr($var, $startpos, strlen($var) - $startpos));

			return $ret_str;
		}	
	}
			
	public static function truncate($str, $max)
	{
		if(strlen($str) > $max)
			$result = mb_substr($str, 0, $max) . "...";
		else
			$result = $str;
		
		return $result;
	}
	
	public static function string_mask($mask, $string)
	{
		$string = str_replace(" ", "", $string);

		for($i=0;$i<strlen($string);$i++)
		{
			$mask[strpos($mask,"#")] = $string[$i];
		}
		return $mask;
	}
	
	public static function remove_accent($string)
	{
		$a = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ'; 
		$b = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr'; 
		$string = utf8_decode($string);     
		$string = strtr($string, utf8_decode($a), $b); 
		$string = strtolower($string); 
		return utf8_encode($string);
	}
	
	public static function strtr_unicode($str, $a = null, $b = null)
	{
		 $translate = $a;

		 if (!is_array($a) && !is_array($b))
		 {
			 $a = (array) $a;
			 $b = (array) $b;
			 $translate = array_combine(
				 array_values($a),
				 array_values($b)
			 );
		 }
		 // again weird, but accepts an array in this case
		 return strtr($str, $translate);
	}	

		
	/* takes an input, scrubs unnecessary words */
	public static function remove_words($input,$replace,$words_array = array(),$unique_words = true)
	{
		//separate all words based on spaces
		$input_array = explode(' ',$input);
		
		//create the return array
		$return = array();
		
		//loops through words, remove bad words, keep good ones
		foreach($input_array as $word)
		{
		//if it's a word we should add...
			if(!in_array($word,$words_array) && ($unique_words ? !in_array($word,$return) : true))
			{
			  $return[] = $word;
			}
		}
		
		//return good words separated by dashes
		return implode($replace,$return);
	}	
	
	public static function split_half($string, $center = 0.4)
	{
			$length2 = strlen($string) * $center;
			$tmp = explode(' ', $string);
			$index = 0; 
			$result = array(0 => '', 1 => '');
			foreach($tmp as $word) {
				if(!$index && strlen($result[0]) > $length2) $index++;
				$result[$index] .= $word.' ';
			}
			return $result;
	}

	
}

/* ***************************************************************************************** */

abstract class DateHelper
{

	public static function get_weekday_calendar($date_object)
	{
		$week_day = $date_object->format("D");	
		
		return strtoupper(substr($week_day, 0, 1));
	}
		
	public static function add($date_given, $count, $unit = "D", $str_format = DEFAULT_DATE_FORMAT)
	{
		$_date = new DateTime($date_given);	
		$_date->add(new DateInterval('P'.$count.$unit));
		
		return $_date->format($str_format);	
	}

	public static function minus($date_given, $count, $unit = "D", $str_format = DEFAULT_DATE_FORMAT)
	{
		$_date = new DateTime($date_given);	
		$_interval = new DateInterval('P'.$count.$unit);
		$_interval->invert = 1;

		$_date->add($_interval);
		
		return $_date->format($str_format);	
	}
	
	public static function format($date_given, $str_format = DEFAULT_DATE_FORMAT)
	{
		if($date_given != "" && $date_given != "0000-00-00 00:00:00" && $date_given != "0000-00-00" && $date_given !== NULL)
		{
			$_date = new DateTime($date_given);
			return $_date->format($str_format);
		}
		else
			return "";
	}
	
	public static function diff_days($current_date, $start_point, $abs = false, $unit = "%a")
	{
		try
		{
			$date1 = new DateTime($current_date);
			$date2 = new DateTime($start_point);
			
			$interval = $date2->diff($date1, $abs);
			return $interval->format($unit);
		}
		catch(Exception $e)
		{
		}
	}	
	
	public static function datetime_to_timestamp($str)
	{ 

		@list($date, $time)            = @explode(' ', $str); 
		@list($year, $month, $day)     = @explode('-', $date); 
		@list($hour, $minute, $second) = @explode(':', $time); 
		 
		$timestamp = mktime((int)$hour, (int)$minute, (int)$second, (int)$month, (int)$day, (int)$year); 

		return $timestamp; 
	}
	
	public static function user_to_datetime($date, $format = DEFAULT_DATE_FORMAT)
	{
		$arr_DateTime = explode(" ", $date);
		$arr_Date = explode("/", $arr_DateTime[0]);
		
		switch ($format)
		{
			case "m/d/Y" : $Date  = chop($arr_Date[2]) . "-" . chop($arr_Date[0]) . "-" . chop($arr_Date[1]); break;
			case "d/m/Y" : $Date  = chop($arr_Date[2]) . "-" . chop($arr_Date[1]) . "-" . chop($arr_Date[0]); break;
		}
	
		if(isset($arr_DateTime[1]))
		{
			$arr_Time = explode(":", $arr_DateTime[1]);
			if(isset($arr_Time[2]))
				$Date .= " ".$arr_Time[0].":".$arr_Time[1].":".$arr_Time[2];
			else
				$Date .= " ".$arr_Time[0].":".$arr_Time[1].":00";		
		}
		
		return ($Date);
	
	}
	
				
	/**
	  * Fixes the date so that it is valid. Can handle negative values. Great for creating date ranges.
	  * NOTE: Changes the passed variables' values.
	  * Ex. 2-29-2001 will become 3-1-2001
	  * 15-6-2011 will become 3-6-2012
	  * 3-0-2001 will become 2-28-2001
	  * -10-29-2002 will become 3-1-2001
	  * @param integer $month 0 values decrement a year and is set to 12.
	  * @param integer $day 0 values decrement a month and is set to the last day of that month.
	  * @param integer $year
	  * @param boolean $unix Optional. Default is true. Use UNIX date format
	  * @return string The resulting string
	  */
	public static function date_fix_date(&$month,&$day,&$year,$unix=true){
		 if($month>12){
			 while ($month>12){
				 $month-=12;//subtract a $year
				 $year++;//add a $year
			 }
		 } else if ($month<1){
			 while ($month<1){
				 $month +=12;//add a $year
				 $year--;//subtract a $year
			 }
		 }
		 if ($day>31){
			 while ($day>31){
				 if ($month==2){
					 if (DateHelper::is_leap_year($year)){//subtract a $month
						 $day-=29;
					 } else{
						 $day-=28;
					 }
					 $month++;//add a $month
				 } else if (DateHelper::month_hasThirtyOneDays($month)){
					 $day-=31;
					 $month++;
				 } else{
					 $day-=30;
					 $month++;
				 }
			 }//end while
			 while ($month>12){ //recheck $months
				 $month-=12;//subtract a $year
				 $year++;//add a $year
			 }
		 } else if ($day<1){
			 while ($day<1){
				 $month--;//subtract a $month
				 if ($month==2){
					 if (DateHelper::is_leap_year($year)){//add a $month
						 $day+=29;
					 }else{
						 $day+=28;
					 }
				 } else if (DateHelper::month_hasThirtyOneDays($month)){
					 $day+=31;
				 } else{
					 $day+=30;
				 }
			 }//end while
			 while ($month<1){//recheck $months
				 $month+=12;//add a $year
				 $year--;//subtract a $year
			 }
		 } else if ($month==2){
			 if (DateHelper::is_leap_year($year)&&$day>29){
				 $day-=29;
				 $month++;
			 } else if($day>28){
				 $day-=28;
				 $month++;
			 }
		 } else if (!DateHelper::month_hasThirtyOneDays($month)&&$day>30){
			 $day-=30;
			 $month++;
		 }
		 if ($year<1900) $year=1900;
		 if ($unix){
			 return "$year-$month-$day";
		 } else{
			 return "$month-$day-$year";
		 }
	 }
	/**
	  * Checks to see if the month has 31 days.
	  * @param integer $month
	  * @return boolean True if the month has 31 days
	  */
	public static function month_hasThirtyOneDays($month)
	{
		 if ($month<8)
			 return $month%2==1;
		 else
			 return $month%2==0;
	}
	
	/**
	  * Checks to see if the year is a leap year.
	  * @param integer $year
	  * @return boolean True if the year is a leap year
	  */
	public static function is_leap_year($year)
	{
		return (0 ==$year%4&&0!=$year%100 || 0 ==$year%400);
	}

    public static function get_abbrev_date($date, $only_month_year = false)
    {
        $_date = DateHelper::format($date, "n");
        $str_month = DateHelper::get_month_name($_date);
        $str_month = substr($str_month, 0, 3);
        if (!$only_month_year)
            return DateHelper::format($date, "d") . ", " . $str_month . ", " . DateHelper::format($date, "Y");
        else
            return $str_month . ", " . DateHelper::format($date, "Y");
    }

	
	public static function get_abbrev_month_name($date)
	{
		$_date = DateHelper::format($date, "n");
		$str_month = DateHelper::get_month_name($_date);		
		$str_month = substr($str_month, 0, 3);
		return $str_month;
	}

	public static function get_month_name($month, $language = 'pt_br')
	{

		$months['pt_br'] = array(
			1 => 'Janeiro',
			2 => 'Fevereiro',
			3 => 'Março',
			4 => 'Abril',
			5 => 'Maio',
			6 => 'Junho',
			7 => 'Julho',
			8 => 'Agosto',
			9 => 'Setembro',
			10 => 'Outubro',
			11 => 'Novembro',
			12 => 'Dezembro',
		);

		$months['en_us'] = array(
			1 => 'January',
			2 => 'February',
			3 => 'March',
			4 => 'April',
			5 => 'May',
			6 => 'June',
			7 => 'July',
			8 => 'August',
			9 => 'September',
			10 => 'October',
			11 => 'November',
			12 => 'December',
		);
		
		return $months[$language][$month];
	}
	
	public static function get_prev_month_last_days($month, $year) {
		
		$prev_month = self::get_prev_month($month);
		$prev_year 	= self::get_prev_year($month, $year);
		
		$last_weekday_in_month 	= self::get_last_weekday_in_month($prev_month, $prev_year);
		$prev_month_range 		= array();
		
		if ($last_weekday_in_month < 6) {
					
			$prev_day 			= self::get_max_days_in_month($prev_month, $prev_year);
			$prev_month_range 	= range($prev_day - $last_weekday_in_month, $prev_day);
			
		}
		
		return $prev_month_range;
	}
	
	public static function get_next_month_first_days($month, $year) {
		
		$next_month = self::get_next_month($month);
		$next_year 	= self::get_next_year($month, $year);
		
		$first_weekday_in_month = self::get_first_weekday_in_month($next_month, $next_year);
		$next_month_range 		= array();
		
		if ($first_weekday_in_month > 0 && $first_weekday_in_month < 7) {
			
			$next_month_range = range(1, 7 - $first_weekday_in_month);
			
		}
		
		return $next_month_range;
	}
	
	public static function get_prev_month($month) {
		
		return ($month == 1 ? 12 : ($month - 1));
	}
	
	public static function get_prev_year($month, $year) {
		
		return ($month == 1 ? ($year - 1) : $year);
	}
	
	public static function get_next_month($month) {
		
		return ($month == 12 ? 1 : ($month + 1));
	}
	
	public static function get_next_year($month, $year) {
		
		return ($month == 12 ? ($year + 1) : $year);
	}
	
	/**
	 * Return max weeks in a month
	 * 
	 * @param $month
	 * @param $year
	 */
	public static function get_max_weeks_in_month($month, $year) {
		
		return (
				count(self::get_prev_month_last_days($month, $year))
				+
				self::get_max_days_in_month($month, $year)
				+
				count(self::get_next_month_first_days($month, $year))
			) / 7;
	}
	
	public static function get_week_range($month_week, $month, $year) {
		
		if ($month_week == 1) {
			
			$prev_month_last_days = self::get_prev_month_last_days($month, $year);
			
			if (count($prev_month_last_days) == 0) {
				
				return array();
			}
			
			return array_merge(array_fill(0, count($prev_month_last_days), ''), range(1, 7 - count($prev_month_last_days)));
		}
		
		$max_week = self::get_max_weeks_in_month($month, $year);
		
		if ($max_week == $month_week) {
			
			$next_month_first_days = self::get_next_month_first_days($month, $year);
			
			$last_month_day = self::get_max_days_in_month($month, $year);
			
			return array_merge(range($last_month_day - count($next_month_first_days), $last_month_day), array_fill(0, count($next_month_first_days), ''));
		}
		
		$week_of_year = self::get_week_of_year($month_week, $month, $year);
					
		$week_start = strtotime('+ ' . ($week_of_year - 2) . ' weeks', strtotime($year . '0101'));			
		$week_start = date('d', $week_start);
		
		return range($week_start, $week_start + 6);
	}
	
	/**
	 * Return first weekday in a month
	 * 
	 * @param $month
	 * @param $year
	 */
	public static function get_first_weekday_in_month($month, $year) {
		
		$getdate = getdate(mktime(null, null, null, $month, 1, $year));
		
		return $getdate['wday'];
	}
	
	public static function get_first_weekdayname_in_month($month, $year) {
		
		$getdate = getdate(mktime(null, null, null, $month, 1, $year));
		
		return $getdate['weekday'];
	}
	
	/**
	 * Return last weekday in a month
	 * 
	 * @param $month
	 * @param $year
	 */
	public static function get_last_weekday_in_month($month, $year) {
		
		$getdate = getdate(mktime(null, null, null, $month + 1, 0, $year));
		
		return $getdate['wday'];
	}
	
	public static function get_last_weekdayname_in_month($month, $year) {
		
		$getdate = getdate(mktime(null, null, null, $month + 1, 0, $year));
		
		return $getdate['weekday'];
	}
	
	public static function adjust_month($month) {
		
		$month 	= (int) $month;
		$month 	= $month <= 0 ? 1 : $month;
		$month 	= $month >= 12 ? 12 : $month;
		
		return $month;
	}
	
	public static function adjust_year($year) {
		
		$year = (int) $year;
		$year = (string) ($year <= 0 ? date('Y') : $year);
		$year = (int) (strlen($year) <= 3 ? date('Y') : $year);
		
		return $year;
	}
	
	public static function adjust_week($week, $month, $year) {
		
		$max_week = self::get_max_weeks_in_month($month, $year);
			
		$week = (int) $week;
		$week = $week <= 0 ? 1 : $week;
		$week = $week >= $max_week ? $max_week : $week;	
		
		return $week;
	}
	
	public static function adjust_quarter($quarter) {
		
		$quarter = (int) $quarter;
		$quarter = $quarter <= 0 ? 1 : $quarter;
		$quarter = $quarter >= 4 ? 1 : $quarter;
		
		return $quarter;
	}
	
	/**
	 * Return last day in a month
	 * 
	 * @param $month
	 * @param $year
	 */
	public static function get_max_days_in_month($month, $year) {
		
		$getdate = getdate(mktime(null, null, null, $month + 1, 0, $year));
		
		return $getdate['mday'];
	}
	
	/**
	 * Return week number in year
	 * 
	 * @param $month_week (1, 2, 3, 4, 5, 6)
	 * @param $month
	 * @param $year
	 */
	public static function get_week_of_year($month_week, $month, $year) {
		
		if ($month_week == 1 && $month == 1) {
			
			return 1;
		}
		
		$month--;
		
		for ($i = $month; $i > 0; $i--) {
			
			$month_week+= self::get_max_weeks_in_month($month, $year);
		}
		
		return $month_week;
	}
	
	
}

/* ***************************************************************************************** */

abstract class FileHelper
{
	public static function csv_to_array($path)
	{
		$handle = fopen($path, "r");
		$matrix = array();
		
		if ($handle)
		{
			$matrix = explode("\n", fread($handle, filesize($path)));
			for($x = 0; $x < count($matrix); $x++)
			{
				$matrix[$x] = explode(",", $matrix[$x]);
			}
		}
		
		return $matrix;
	}
	
	public static function xls_to_array($path)
	{
		require_once LIB_ROOT . "/components/php_excelreader/reader.php";
		
		$data = new Spreadsheet_Excel_Reader($path);
		$data->read($path);
		
		$total_lines   = $data->sheets[0]['numRows'];
		$total_columns = $data->sheets[0]['numCols'];
		 
		for($i = 0; $i < $total_lines; $i++)
		{
			for($j = 0; $j < $total_columns; $j++)
			{
				$a[$i][$j] = $data->sheets[0]['cells'][$i+1][$j+1];
			}
		}

		return $a;
	}

}

/* ***************************************************************************************** */

abstract class EDIHelper
{

	public static function fixedVar($var, $dirct, $char, $totchars)
	{
		if(strlen($var)<$totchars)
		{
			$modvar   = $var;
			$length   = strlen($var);
			$numchars = abs($length - $totchars);
		
			for($x=1; $x<=$numchars; $x++)
			{
				if($dirct=="R")
					$modvar = $modvar . $char;
				else
					$modvar = $char . $modvar;
			}
		}
		else
		{
			$modvar = substr($var, 0, $totchars);
		}
		return ($modvar);
		
	}
	
	public static function readVar($posI, $posL, $line)
	{
		$retval = substr($line, $posI, $posL);
		return ($retval);
	
	}
	
	public static function remChars($str)
	{
		$x = $str;
		$x = str_replace("-", "", $x);
		$x = str_replace(".", "", $x);
		$x = str_replace("/", "", $x);
		return($x);
	}
	
	public static function TrimLeftChar($str, $char)
	{
		for($x=1;$x<=strlen($str);$x++)
		{
			if (strcmp(substr($str, ($x-1), 1), $char) != 0)
			{
				$modstr = substr($str, ($x-1), strlen($str)-($x-1));
				break;
			}
		}
		return $modstr;
		
	}
}
