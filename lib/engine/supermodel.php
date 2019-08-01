<?php

/*
 * ******************************************************************************

  Developed by Daniel Jordao Santana (daniel.js@gmail.com)
  Copyright (c) 2017 - Zillius Solutions (www.zillius.com.br)
  Code changes not allowed, doing so will lose warranty of its functionality!

  All rights reserved.

 * ******************************************************************************
 */
  
class SuperModel
{	
	public $_magicProperties;

	public function __call($method, $parameters)
	{
		//for this to be a setSomething or getSomething, the name has to have 
		//at least 4 chars as in, setX or getX
		if(strlen($method) < 4) 
			throw new Exception('Method does not exist');
		
		//take first 3 chars to determine if this is a get or set
		$prefix = substr($method, 0, 3);
		
		//take last chars and convert first char to lower to get required property
		$suffix = substr($method, 3);
		$suffix[0] = strtolower($suffix[0]);
		
		if($prefix == 'get')
		{
			if($this->_hasProperty($suffix) && count($parameters) == 0) 
				return $this->_magicProperties[$suffix];
			else
				throw new Exception('Getter does not exist');
		}
	
		if($prefix == 'set')
		{
			if($this->_hasProperty($suffix) && count($parameters) == 1)
			{
				$this->_magicProperties[$suffix] = $parameters[0];
				return $this;
			}
			else
				throw new Exception('Setter does not exist');
		}
	}
	
	private function _hasProperty($name)
	{
		return array_key_exists($name, $this->_magicProperties);
	}

	public function fromArray(array $array)
	{
		foreach($array as $key => $value)
		{
			//We need to convert first char of key to upper to get the correct 
			//format required in the setter method name
			$property = $key;
			$property[0] = strtoupper($key);
			
			$mtd = 'set' . $property;
			$this->$mtd($value);
		}
	}	
	
	public function toArray() {
		return $this->_magicProperties;
	}	
	
	public function underscoreToCamelCase($row)
	{
		$convertedRow = array();
		//foo_bar -> fooBar
		foreach($row as $key => $value)
		{
			$parts = explode('_', $key);
			for($i = 1, $partCount = count($parts); $i < $partCount; $i++)
			{
			  $parts[$i][0] = strtoupper($parts[$i][0]);
			}
			
			$fixedKey = implode('', $parts);
			$convertedRow[$fixedKey] = $value;
		}
		
		return $convertedRow;
	}	
	
	public function camelCaseToUnderscore($row)
	{
		$convertedRow = array();
		foreach($row as $key => $value)
		{
			$newKey = strtolower(preg_replace('/([a-z])([A-Z])/','$1_$2',$key));
			$convertedRow[$newKey] = $value;
		}
	
		return $convertedRow;
	}	

}
