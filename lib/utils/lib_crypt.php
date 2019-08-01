<?php

/*
 * ******************************************************************************

  Developed by Daniel Jordao Santana (daniel.js@gmail.com)
  Copyright (c) 2017 - Zillius Solutions (www.zillius.com.br)
  Code changes not allowed, doing so will lose warranty of its functionality!

  All rights reserved.

 * ******************************************************************************
 */

class lib_crypt {

    private static function safe_b64encode($string) {
    	
        $data = base64_encode($string);
        $data = str_replace(array('+','/','='), array('-','_',''), $data);
        
        return $data;
    }

    private static function safe_b64decode($string) {
    	
        $data = str_replace(array('-','_'), array('+','/'), $string);
        $mod4 = strlen($data) % 4;
        
        if ($mod4) {
        	
            $data.= substr('====', $mod4);
        }
        
        return base64_decode($data);
    }

    public static function encode($value, $key = 'kalm1n8894z1ca') {
    	
        if (!$value) {
        	
        	return false;
       	}
        
        $iv_size 	= mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv 		= mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $crypttext 	= mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $value, MCRYPT_MODE_ECB, $iv);
        
        return trim(self::safe_b64encode($crypttext)); 
    }

    public static function decode($value, $key = 'kalm1n8894z1ca') {
    	
		if (!$value) {
        	
        	return false;
       	}
       	
        $value = self::safe_b64decode($value);
         
        $iv_size 		= mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv 			= mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $decrypttext 	= mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $value, MCRYPT_MODE_ECB, $iv);
        
        return trim($decrypttext);
    }
}