<?php

/*
 * ******************************************************************************

  Developed by Daniel Jordao Santana (daniel.js@gmail.com)
  Copyright (c) 2017 - Zillius Solutions (www.zillius.com.br)
  Code changes not allowed, doing so will lose warranty of its functionality!

  All rights reserved.

 * ******************************************************************************
 */

class KeyFactory
{
	private $keyfactory = array();
    private static $instance;		
	
	public static function singleton()
	{
		if (!isset(self::$instance))
			self::$instance = new KeyFactory();
			
		return self::$instance;
	}

	public function get($key)
	{
		return (isset($this->keyfactory[$key]) ? $this->keyfactory[$key] : NULL);
	}

	public function set($key, $value)
	{
		$this->keyfactory[$key] = $value;
	}

	public function has($key)
	{
    	return isset($this->keyfactory[$key]);
  	}
}



?>