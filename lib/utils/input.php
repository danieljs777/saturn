<?php

/*
 * ******************************************************************************

  Developed by Daniel Jordao Santana (daniel.js@gmail.com)
  Copyright (c) 2017 - Zillius Solutions (www.zillius.com.br)
  Code changes not allowed, doing so will lose warranty of its functionality!

  All rights reserved.

 * ******************************************************************************
 */

require_once "iostream.php";

abstract class Input
{
	public static $error_code = 0;
	public static $error_msg = "";
	public static $break_line =  "<br />";
	
	private static $_CHAR                = '/^\w+$/';
	private static $_BLANK               = '/^\s+$/';
	private static $_LETTERS             = '/^[a-zA-Z]+$/';
	private static $_LETTERS_NUMBERS     = '/^[a-zA-Z0-9\s]+$/';
	private static $_NO_SPACE            = '/^[a-zA-Z0-9]+$/';
	private static $_NUMBERS             = '/^[0-9]+$/';
	private static $_INTEGERS            = '/^[0-9]+$/';
	private static $_LETTERS_OR_NUMBERS  = '/^([a-zA-Z]|[0-9])$/';
	private static $_FLOATING_POINT      = '/^((\d+(\.\d*)?)|((\d*\.)?\d+))$/';
	private static $_DECIMAL_POINT       = '/^((\d+(\,\d*)?)|((\d*\,)?\d+))$/';
	private static $_DECIMAL_POINT_2_DIG = '/^(\d+((,\d{1,2})|(\.\d{1,2}))?)$/';
	private static $_EMAIL               = '/^[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+.[a-zA-Z0-9-.]+$/';
	private static $_NOT_NULL            = '/^[\s|\S]+$/';
	private static $_DOCUMENT            = '/^[0-9]\.[0-9]{3}\.[0-9]{3}\-\d$/';
	private static $_PHONE_NUM           = '/^(\(?\+?[0-9]*\)?)?[0-9_\- \(\)]*$/';
	private static $_CNPJ                = '/^[0-9]\.[0-9]{3}\.[0-9]{3}\/[0-9]{4}-\d$/';
	private static $_DATE                = '/^([0-9]{2}\/[0-9]{2}\/[0-9]{4})$/';
	private static $_DATE_TIME           = '/^([0-9]{2}\/[0-9]{2}\/[0-9]{4}\s[0-9]{2}:[0-9]{2}:[0-9]{2})$/';
	private static $_ZIP_NUMBER          = '/^\d{5,5}-?\d{3,3}$/';	
	private static $_CURRENCY            = '/^[-+]?\d{1,3}(\.\d{3})*[,.]\d{2}$/';
	private static $_CREDITCARD          = '/^[0-9]{4}\s[0-9]{4}\s[0-9]{4}\s[0-9]{4}$/';
	
	public static function check_data($value, $type)
	{
		preg_match(self::get_constant("_" . $type), $value, $matched);
		if (count($matched) > 0)
			return $matched[0];
		else
			return NULL;
	}
	
    public static function get_constant($constant_name)
	{
        return self::$$constant_name;
    }	
	
	public static function validate_fields_container($container, $fields, $table)
	{
		$user_data = array();
		
		foreach ( $fields as $field_name => $field_param )
		{
			//$field_db = str_replace($table . "_", "", $field_name);
			$field_db = $field_name;
			$_value = isset($container->$field_name) ? $container->$field_name : NULL;
			
			if( $field_param[6] != 'file' || $field_param[6] == 'checkbox' && $_value !== NULL )
			{
				$user_data[$field_db] = self::validate($_value, $field_param[0], $field_param[1], $field_param[2], $field_param[3], $field_param[4], $field_param[5]);
			}
		}
		
		return $user_data;
	}			
	
	public static function validate_fields($fields, $table)
	{
		$user_data = array();
		
		foreach ( $fields as $field_name => $field_param )
		{
			//$field_db = str_replace($table . "_", "", $field_name);
			$field_db = $field_name;
			$_value = isset($_REQUEST[$field_name]) ? $_REQUEST[$field_name] : NULL;
			
			if( $field_param[6] != 'file' || $field_param[6] == 'checkbox' && $_value !== NULL )
			{
				$user_data[$field_db] = self::validate($_value, $field_param[0], $field_param[1], $field_param[2], $field_param[3], $field_param[4], $field_param[5]);
			}
		}
		
		return $user_data;
	}	

	public static function validate_field_array($field, $table, $config)
	{
		$user_data = array();
		
		$field_db = str_replace($table . "_", "", $field);
	
		$field_param = $config[$field_db];

		$x=0;
		foreach($_POST[$field] as $_field)
		{
			if($field_param[6] != 'file' && isset($_field))
			{
				$user_data[$x] = self::validate($_field, $field_param[0], $field_param[1], $field_param[2], $field_param[3], $field_param[4], $field_param[5]);
				$x++;
			}
		}

		return $user_data;
	}	
	
	public static function validate($field_data, $optional, $max_len, $min_len, $data_type, $name, $default_value, $field_name = "")
	{
		$msg_short   = "Campo " . $name . " tem ". strlen($field_data) . " caracteres! Minimo é " . $min_len . "!";
		$msg_long    = "Campo " . $name . " tem ". strlen($field_data) . " caracteres! Máximo é " . $max_len . "!";
		$msg_empty   = "Preencha ".$name ."!";
			
		$field_data = chop(str_replace("'", "`", $field_data));

		if ($field_data == "" && $optional == 0)
		{
			self::$error_code = 1;
			self::$error_msg .= $msg_empty . self::$break_line;
			return false;
		}
		
		if($data_type != "HTML")
		{
				
			if ((strlen($field_data) < $min_len))
			{
				self::$error_code = 1;
				self::$error_msg .= $msg_short;
				return false;
			}
			elseif(strlen($field_data) > $max_len)
			{
				self::$error_code = 1;
				self::$error_msg .= $msg_long;
				return false;
			}
		}

		if($field_data != "")
		{
			switch (strtoupper($data_type))
			{
				case "ALPHA"     : $field_data = self::check_alpha($field_data, $optional, $max_len);         break;
				case "DATE"      : $field_data = self::check_date($name, $field_data, $optional);             break;
				case "DATETIME"  : $field_data = self::check_datetime($name, $field_data, $optional);         break;
				case "NUM"       : $field_data = self::check_number($name, $field_data, $optional);           break;
				case "DECIMAL"   : $field_data = self::check_decimal($name, $field_data, $optional);           break;
				case "CURRENCY"  : $field_data = self::check_currency($name, $field_data, $optional);         break;
				case "EMAIL"     : $field_data = self::check_mail($name, $field_data, $optional);             break;
				case "TEL"       : $field_data = self::check_phone($name, $field_data, $optional);            break;
				case "DOC"       : $field_data = self::check_document($name, $field_data, $optional);         break;
				case "CPF"       : $field_data = self::check_cpf($field_data);                      	  	  break;
				case "CC"        : $field_data = self::check_creditcard($name, $field_data, $optional);       break;
				case "ZIP"       : $field_data = self::check_zip($field_data, $optional);            		  break;
				default		     : $field_data = $field_data; 												  break;
			}
		}

		if (($field_data == "" || $field_data == "--") && $optional == 1)
			return $default_value;
		else
			return $field_data;
		
	}
	
	////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	public static function check_alpha($field_data, $optional = 0, $strlength = 100)
	{
						
		$field_data = str_replace("'", "´", $field_data);
		$field_data = str_replace("<", "&lt;", $field_data);
		$field_data = str_replace(">", "&gt;", $field_data);
		
		return $field_data;
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////////

	public static function check_decimal($name, $field_data, $optional)
	{
		if(!self::check_data($field_data, "DECIMAL_POINT"))
		{
			self::$error_msg .= $name . " está em formato invalido!".self::$break_line;
			self::$error_code = 1;
			return false;
		}
		
		if ($field_data == "" && $optional == 0)
			return false;
		else
			return str_replace(",", ".", str_replace(".", "", $field_data));
	}	
	
	////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	public static function check_currency($name, $field_data, $optional)
	{
		if(!self::check_data($field_data, "CURRENCY"))
		{
			self::$error_msg .= $name . " está em formato de moeda invalido!".self::$break_line;
			self::$error_code = 1;
			return false;
		}
		
		if ($field_data == "" && $optional == 0)
			return false;
		else
			return str_replace(",", ".", str_replace(".", "", $field_data));
	}
	
	public static function check_currency_real($field_data)
	{
		return str_replace(".", ",", $field_data);
	}
	
	////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	public static function check_mail($name, $field_data, $optional)
	{
		
		if(!self::check_data($field_data, "EMAIL"))
		{
			self::$error_msg .= $name . " é email invalido!".self::$break_line;
			self::$error_code = 1;
			return false;
		}
		
		return strtolower($field_data);
		
	}
	
	////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	public static function check_zip($name, $field_data, $optional)
	{
		
		if(!self::check_data($field_data, "ZIP_NUMBER"))
		{
			self::$error_msg .= $name . " é CEP inválido!".self::$break_line;
			self::$error_code = 1;
			return false;
		}
		
		return $field_data;
		
	}
	
	////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	public static function check_phone($name, $field_data, $optional)
	{
		if(!self::check_data($field_data, "PHONE_NUM"))
		{
			self::$error_msg .= $name . " é telefone inválido".self::$break_line;
			self::$error_code = 1;
			return false;
		}
		
		/*		
		if ((strlen($comp_string) > 6 && strlen($comp_string) < 10) && (strpos($comp_string, "-") > 0 || strpos($comp_string, " ") > 0))
		{
			$comp_string = str_replace("-", "", $comp_string);
			return (StringHelper::left($comp_string, strlen($comp_string)-4)."-". StringHelper::right($comp_string, 4));
		}
		else*/

		return $field_data;
		
		
	}
	
	////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	public static function check_document($name, $field_data, $optional)
	{
		if(!self::check_data($field_data, "DOCUMENT"))
		{
			self::$error_msg .= $name . " é documento inválido!".self::$break_line;
			self::$error_code = 1;
			return false;
		}
		
		return $field_data;
		
	}
	
	////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	public static function check_date($name, $field_data, $optional)
	{

		if(!self::check_data($field_data, "DATE"))
		{
			self::$error_msg .= $name . ": " . $field_data . " é data inválida!".self::$break_line;
			self::$error_code = 1;
			return false;
		}

		$arrDateTime = explode(" ", $field_data);
		$arrDate = explode("/", $arrDateTime[0]);
		
		if ((sizeof($arrDate) != 3) || !(checkdate($arrDate[1], $arrDate[0], $arrDate[2])))
		{
			self::$error_code = 1;
			self::$error_msg .= $name . " é data inválida!" . self::$break_line;
			return false;
		}
		else
		{
			$Date = DateHelper::user_to_datetime($field_data);
			return $Date;
		}
		
	}
	
	////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	public static function check_datetime($name, $field_data, $optional)
	{
		if(strlen($field_data) < 17)
			$field_data .= ":00";

		if(!self::check_data($field_data, "DATE_TIME"))
		{
			self::$error_msg .= $name . ">" . $field_data . " está em formato de data inválida!".self::$break_line;
			self::$error_code = 1;
			return false;
		}

		$arrDateTime = explode(" ", $field_data);
		$arrDate = explode("/", $arrDateTime[0]);
		
		
		if ((sizeof($arrDate) != 3) || !(checkdate($arrDate[1], $arrDate[0], $arrDate[2])))
		{
			self::$error_code = 1;
			self::$error_msg .= $name . " é data inválida!" . self::$break_line;
			return false;
		}
		else
		{
			$Date = DateHelper::user_to_datetime($field_data);
			return $Date;
		}
		
	}
	
	////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	public static function check_number($name, $field_data, $optional)
	{
		// var_dump($field_data);
		// var_dump((is_numeric(intval($field_data))));
		// var_dump(self::check_data($field_data, "NUMBERS"));
		// var_dump((!self::check_data($field_data, "NUMBERS") || !(is_numeric(intval($field_data)))));

		if(self::check_data($field_data, "NUMBERS") === NULL || !(is_numeric(intval($field_data))))
		{
			self::$error_msg .= $name . " não informado!".self::$break_line;
			self::$error_code = 1;
			return false;
		}
		
		return intval($field_data);

	}
		
	////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	public static function check_creditcard($name, $field_data, $optional)
	{
		
		if(!self::check_data($field_data, "CREDITCARD"))
		{
			self::$error_msg .= $name . " está inválido!".self::$break_line;
			self::$error_code = 1;
			return false;
		}
		
		return $field_data;
		
	
	}
	
	
	
	//////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	public static function check_cpf($cpf)
	{		

	    // Verifica se um número foi informado
	    if(empty($cpf)) {
	        return false;
	    }
	 
	    // Elimina possivel mascara
	    $cpf = preg_replace('/[^0-9]+/', '', $cpf);
	    $cpf = str_pad($cpf, 11, '0', STR_PAD_LEFT);
	     
	    // Verifica se o numero de digitos informados é igual a 11 
	    if (strlen($cpf) != 11)
	    {
			self::$error_msg .= "CPF está em formato inválido!".self::$break_line;
			self::$error_code = 1;	    

	        return false;
	    }
	    // Verifica se nenhuma das sequências invalidas abaixo 
	    // foi digitada. Caso afirmativo, retorna falso
	    else if ($cpf == '00000000000' || 
	        $cpf == '11111111111' || 
	        $cpf == '54300000000' ||
	        $cpf == '22222222222' || 
	        $cpf == '33333333333' || 
	        $cpf == '44444444444' || 
	        $cpf == '55555555555' || 
	        $cpf == '66666666666' || 
	        $cpf == '77777777777' || 
	        $cpf == '88888888888' || 
	        $cpf == '99999999999')
	    {
			self::$error_msg .= "CPF inválido!".self::$break_line;
			self::$error_code = 1;	    
				    	
	        return false;
	    }
	    else
	    {   
	        for ($t = 9; $t < 11; $t++)
	        {
	            for ($d = 0, $c = 0; $c < $t; $c++)
	            {
	                $d += $cpf{$c} * (($t + 1) - $c);
	            }

	            $d = ((10 * $d) % 11) % 10;
	            
	            if ($cpf{$c} != $d)
	            {
					self::$error_msg .= "CPF inválido!".self::$break_line;
					self::$error_code = 1;	    

	                return false;
	            }
	        }
	 
	        return StringHelper::mask($cpf, "###.###.###-##");
	    }
	}	
}
