<?php

/*
 * ******************************************************************************

  Developed by Daniel Jordao Santana (daniel.js@gmail.com)
  Copyright (c) 2017 - Zillius Solutions (www.zillius.com.br)
  Code changes not allowed, doing so will lose warranty of its functionality!

  All rights reserved.

 * ******************************************************************************
 */

class I18n
{
    private static $instance;	
	private $dictionary = array();
	private $using_lang;
		
	public static function singleton($language)
	{
		if (!isset(self::$instance))
			self::$instance = new I18n($language);
			
		return self::$instance;
	}
	
	private function __construct($language)
	{
		$this->using_lang = $language;
		
		if(!isset($this->dictionary[$this->using_lang]))
		{
			$this->load_terms();
		}
		
	}
	
	private function load_terms()
	{
		$language = $this->using_lang;
		
		try
		{
			$file_handle = fopen(PATH_ROOT . "/lib/i18n/messages." . $language . ".inc", "r");
		}
		catch (Exception $e)
		{
			echo 'Caught exception: ',  $e->getMessage(), "\n";
		}		

		
		while (!feof($file_handle) )
		{
			$line = fgets($file_handle);
			
			if(trim($line) != "")
			{
				$term = array_map('trim', explode('=', $line, 2));
				$this->dictionary[$language][$term[0]] = $term[1];
			}
			
		}
	
		fclose($file_handle);
						
	}
	
	public function get_term()
	{		
		$args       = func_get_args();
		$term_name  = array_shift($args);
		
		$_term = $this->dictionary[$this->using_lang][$term_name];	
				
		return vsprintf($_term, $args);
	}
	
}
?>
