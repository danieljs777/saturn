<?php

/*
 * ******************************************************************************

  Developed by Daniel Jordao Santana (daniel.js@gmail.com)
  Copyright (c) 2017 - Zillius Solutions (www.zillius.com.br)
  Code changes not allowed, doing so will lose warranty of its functionality!

  All rights reserved.

 * ******************************************************************************
 */

class Log {
	
	public function __construct() {}
	
	public static function verbose($message, $file = "debug.log")
	{
		$file_path = LIB_ROOT . 'log/' . $file;

		$handler = @fopen($file_path . $file_name, 'a+');
		
		if ($handler == false)
			return false;
		
		$message = date("Y-m-d") . "-" . $_SERVER['REQUEST_URI'] . " - " . $_SERVER['HTTP_USER_AGENT'] . "- verbose : " . $message;
		flock($handler, LOCK_EX);
		fwrite($handler, "\r\n" . $message);
		flock($handler, LOCK_UN);
		fclose($handler);
		
		return true;
		
	}
	
	/**
	 * Logs an event.
	 * 
	 * @param $severity http://www.php.net/manual/en/errorfunc.constants.php
	 * @param $message
	 * @param $file
	 * @param $line
	 */
	public static function event($severity = '', $message = '', $file = '', $line = '')
	{
		/**
		if ($severity == E_ERROR) {
			
			trigger_error($message, E_USER_ERROR);
		}
		
		if (ON_TEST === true) {
			
			echo $severity . ' - ' . $message . ' - ' . $file . ' - ' . $line;
			return;
		}

		
		 * is $severity greater than log_level config entry?
		 
		if ($severity > get_config_item('log_level', 'general')) {
			
			return false;
		}
		*/
		
		$file_path = LIB_ROOT . "/lib/";
		$file_name = date('Y-m-d') . '.php';
		
		$content = '';
		
		$message = str_replace('\'', '"', $message);
		
		if (file_exists($file_path . $file_name) == false)
		{
		
			$content.= '<?php' . "\n\n\t" . 'if (defined(\'HOMEPATH\') == false) {' . "\n\n\t\t" . 'header(\'Location: /\');' . "\n\t\t" . 'exit;' . "\n\t" . '}' . "\n\n\t" . '$array_log = array();';
		}
		
		$content.= "\n\t" . '$array_log[] = array(\'severity\' => ' . $severity . ', \'date\' => \'' . date('d-m-Y H:i:s') . '\', \'message\' => \'' . $message . '\', \'file\' => \'' . $file . '\', \'line\' => ' . $line . ');';
		
		$handler = @fopen($file_path . $file_name, 'a+');
		
		if ($handler == false) {
			
			return false;
		}
		
		flock($handler, LOCK_EX);
		fwrite($handler, $content);
		flock($handler, LOCK_UN);
		fclose($handler);
		
		return true;
	}
}