<?php

/*
 * ******************************************************************************

  Developed by Daniel Jordao Santana (daniel.js@gmail.com)
  Copyright (c) 2017 - Zillius Solutions (www.zillius.com.br)
  Code changes not allowed, doing so will lose warranty of its functionality!

  All rights reserved.

 * ******************************************************************************
 */

abstract class System
{

	public static function get_factory($prop_name)
	{
		$key_factory = KeyFactory::singleton();
		return $key_factory->get($prop_name);

	}

	public static function get_factory_config($module, $prop_name)
	{
		$key_factory = KeyFactory::singleton();
		$config_data = $key_factory->get($module . "_config");

		if(System::is_filled($config_data))
		{
			$key = $key_factory->get($module . "_config");
			
			if(isset($key[$prop_name]))
				return $key[$prop_name];
		}

	}	

	public static function is_mobile_agent()
	{
		$useragent = $_SERVER['HTTP_USER_AGENT'];

		return(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)
			||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)));
	}

	public static function sendmail($rcpt_to, $subject, $template_file, $template_data, $attachs = array())
	{

		if(file_exists(PATH_ROOT . "vendor/autoload.php"))
			require_once PATH_ROOT . "vendor/autoload.php";

		$strSource = Render::get_file(LIB_ROOT . "/templates/" . $template_file, $template_data, true);

        try
        {

			$mail = new PHPMailer();
			$mail->CharSet = 'UTF-8';

			// $mail->SMTPDebug = 3;                               // Enable verbose debug output

			$mail->SMTPOptions = array(
			    'ssl' => array(
			        'verify_peer' => false,
			        'verify_peer_name' => false,
			        'allow_self_signed' => true
			    )
			);		

			if(SMTP_IS_SMTP)
				$mail->isSMTP();

			$mail->SMTPAuth = TRUE;
			$mail->Host = SMTP_SERVER;
			$mail->Username = SMTP_USER;
			$mail->Password = SMTP_PASSWD;
			$mail->SMTPSecure = SMTP_PROTO;                            // Enable TLS encryption, `ssl` also accepted
			$mail->Port = SMTP_PORT;

			$mail->setFrom(SMTP_ADMIN, SYSTEM_NAME);
			// $mail->addAddress($rcpt_to, $rcpt_to);     
			// $mail->addCC('cc@example.com');
			// $mail->addBCC('bcc@example.com');

			// $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
			// $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

			$mail->isHTML(true);                                  // Set email format to HTML

			$mail->Subject = $subject;
			$mail->Body    = $strSource;
			$mail->AltBody = '';

			switch ($_SERVER['SERVER_NAME'])
			{
				//default:
				case "poseidon":
					$mail->addAddress("daniel.js@gmail.com");
					break;

				default:
					$mail->addAddress($rcpt_to);			
					break;
			}

			Log::verbose("Enviando email para " . $rcpt_to, "email.log");

            $return = $mail->Send();

			if(!$return) {
				Log::verbose($mail->ErrorInfo, "email.log");
				return false;
			} else {
			    return true;
			}
		}
		catch (Exception $e)
        {
            $mail_ex = '';

            if($mail->error_count > 0)
                $mail_ex = $mail->ErrorInfo;

            Log::verbose("Erro PHPMailer: " . $e->getMessage() . " " . $mail_ex, "email.log");
        }

	}

    public static function array_to_xml($array, &$xml)
    {

	    foreach ($array as $key => $value)
	    {
	        if(is_array($value))
	        {
	            if(is_int($key))
	            {
	                $key = "e";
	            }

	            $label = $xml->addChild($key);
	            self::array_to_xml($value, $label);
	        }
	        else {
	        	// echo "<br>";
	        	// var_dump($value);
	        	$value = str_replace("&", "e", $value);
	            $xml->addChild($key, mb_convert_encoding($value, "UTF-8"));
	        }
	    }

	    // return $xml;
	}
	
	public static function make_link($page, $id = "", $tag = "")
	{
		$str_link = (APACHE_RWENGINE == 'On') ? '/' : '/?';

		$str_link .= $page;
		if($id != "")
		{
			$str_link .= "/" . $id;
			
			if($tag != "")
				$str_link .= "/" . System::generate_seo_link($tag);
			
		}
		
		return $str_link;
		
	}
	
	public static function get_level_path($Level)
	{
		$Path = $_SERVER['PHP_SELF'];
		$Paths = array_reverse(explode("/", $Path));
		return $Paths[$Level];
	}

	public static function log_error($message)
	{
		Log::verbose($message);
	}
	
    public static function get_value(&$variable)
    {
        if (isset($variable) && $variable != NULL && $variable != "")
            return str_replace(array('\'', '<', '>', ';'), '', $variable);
        else
        	return false;
    }

    public static function request($variable)
    {
        return self::get_value($_REQUEST[$variable]);
    }

    public static function request_default($variable, $default = "")
    {
        return (!self::get_value($_REQUEST[$variable])) ? $default : self::get_value($_REQUEST[$variable]);
    }

    public static function get($variable)
    {
        return self::get_value($_GET[$variable]);
    }
	
	public static function is_filled(&$array_var)
	{
		if(is_array($array_var))
		{
			if(sizeof($array_var) > 0)
				return true;
			else
				return false;			
		}
		else
			return false;
	}
	
	public static function is_value(&$variable, $value)
	{
		return (isset($variable) && $variable == $value);
	}
	
	public static function is_not_empty(&$variable, $return_value = false, $return = "")
	{
		if($return_value)
		{
			return ((isset($variable) && $variable != NULL && $variable != "") ? $variable : $return);
		}
		else
			return (isset($variable) && $variable != NULL && $variable != "");
			
	}

	public static function get_last_split($string, $split)
	{
		$arr_string = explode($split, $string);
		
		return $arr_string[sizeof($arr_string) - 1];	
	}

	public static function get_first_split($string, $split = " ")
	{
		$arr_string = explode($split, $string);
		
		return $arr_string[0];	
	}	
	
	public static function islogged()
	{
		return (isset($_SESSION['app_user_logged']) && $_SESSION['app_user_logged'] == 1);
	}

	public static function islogged_shop()
	{
		return (isset($_SESSION['shop_user_logged']) && $_SESSION['shop_user_logged'] == 1);
	}	
	
	public static function islogged_admin()
	{
		return (isset($_SESSION['user_logged']) && $_SESSION['user_logged'] == 1);
	}

    public static function get_i18n_term()
    {
                
        $i18n = I18n::singleton(SYSTEM_LANGUAGE);
        $args = func_get_args();

        return call_user_func_array(array($i18n, "get_term"), $args);
    }    
	
	public static function get_session($param)
	{
		return (isset($_SESSION[$param]) ? $_SESSION[$param] : 0);
	}
	
	public static function load_controller($module)
	{
		$controller_class = ucwords($module) . "Controller";
		
		if(file_exists(LIB_ROOT . "/modules/$module/$module.controller.php"))
		{
			require_once LIB_ROOT . "/modules/$module/$module.controller.php";
			
			return new $controller_class();
		}
	}
	
	public static function do_action($module, $action, $params = "")
	{
		
		$controller_class = ucwords($module) . "Controller";
		
		if(file_exists(LIB_ROOT . "/modules/$module/$module.controller.php"))
		{
			require_once LIB_ROOT . "/modules/$module/$module.controller.php";
			
			$module_controller = new $controller_class($action);
			$action = $module_controller->get_method();
			
			if (!is_callable(array($module_controller, $action)))
			{
				Render::not_found("$module.$action not acessible!");
				exit();
			}
			
			if (($action != ""))
			{
				if($params != "")
				{
					if(strpos($params, "=") > 0)
					{
						$_params = str_replace("|", "&", $params);
						parse_str($_params, $params);
						
					}
					
					return $module_controller->$action($params);
					
				}
				else
					return $module_controller->$action();
			}
		}
		else
		{
			Render::not_found(LIB_ROOT . "/modules/$module/$module.controller.php. Module $module not found!");
		}
		
	}

    public static function get_menu()
    {
        Acl::load_menu();
    }	
	
	
	public static function generate_seo_link($input, $replace = '-', $remove_words = true, $words_array = array('a','and','the','an','it','is','with','can','of','why','not'))
	{
		$return = StringHelper::remove_accent($input);
		$return = str_replace(array('\'', '"', '”', "/", "%", "#", "", "(", ")"), "", $return);
		//$input = strtr($input, array(' ' => '-'));

		//make it lowercase, remove punctuation, remove multiple/leading/ending spaces
		//$return = trim(preg_replace("/[[:blank:]]+/", ' ', preg_replace('/[^a-zA-Z0-9\s]/', '', strtolower($input))));

		
		//remove words, if not helpful to seo
		//i like my defaults list in remove_words(), so I wont pass that array
		if($remove_words) { $return = StringHelper::remove_words($return, $replace, $words_array); }
		
		
		//convert the spaces to whatever the user wants
		//usually a dash or underscore..
		//...then return the value.
		return str_replace(' ', $replace, $return);
	}
	

	public static function generate_timezone_list()
	{
		static $regions = array(
			DateTimeZone::AFRICA,
			DateTimeZone::AMERICA,
			DateTimeZone::ANTARCTICA,
			DateTimeZone::ASIA,
			DateTimeZone::ATLANTIC,
			DateTimeZone::AUSTRALIA,
			DateTimeZone::EUROPE,
			DateTimeZone::INDIAN,
			DateTimeZone::PACIFIC,
		);
	
		$timezones = array();
		foreach( $regions as $region )
		{
			$timezones = array_merge( $timezones, DateTimeZone::listIdentifiers( $region ) );
		}
	
		$timezone_offsets = array();
		foreach( $timezones as $timezone )
		{
			$tz = new DateTimeZone($timezone);
			$timezone_offsets[$timezone] = $tz->getOffset(new DateTime);
		}
	
		// sort timezone by offset
		asort($timezone_offsets);
	
		$timezone_list = array();
		foreach( $timezone_offsets as $timezone => $offset )
		{
			$offset_prefix = $offset < 0 ? '-' : '+';
			$offset_formatted = gmdate( 'H:i', abs($offset) );
	
			$pretty_offset = "UTC${offset_prefix}${offset_formatted}";
	
			$timezone_list[$timezone] = "(${pretty_offset}) $timezone";
		}
	
		return $timezone_list;
	}	

}


if (!function_exists('fputcsv'))
{
     function fputcsv(&$handle, $fields = array(), $delimiter = ',', $enclosure = '"') {

         // Sanity Check
         if (!is_resource($handle)) {
             trigger_error('fputcsv() expects parameter 1 to be resource, ' .
                 gettype($handle) . ' given', E_USER_WARNING);
             return false;
         }

         if ($delimiter!=NULL) {
             if( strlen($delimiter) < 1 ) {
                 trigger_error('delimiter must be a character', E_USER_WARNING);
                 return false;
             }elseif( strlen($delimiter) > 1 ) {
                 trigger_error('delimiter must be a single character', E_USER_NOTICE);
             }

             /* use first character from string */
             $delimiter = $delimiter[0];
         }

         if( $enclosure!=NULL ) {
              if( strlen($enclosure) < 1 ) {
                 trigger_error('enclosure must be a character', E_USER_WARNING);
                 return false;
             }elseif( strlen($enclosure) > 1 ) {
                 trigger_error('enclosure must be a single character', E_USER_NOTICE);
             }

             /* use first character from string */
             $enclosure = $enclosure[0];
        }

         $i = 0;
         $csvline = '';
         $escape_char = '\\';
         $field_cnt = count($fields);
         $enc_is_quote = in_array($enclosure, array('"',"'"));
         reset($fields);

         foreach( $fields AS $field ) {

             /* enclose a field that contains a delimiter, an enclosure character, or a newline */
             if( is_string($field) && ( 
                 strpos($field, $delimiter)!==false ||
                 strpos($field, $enclosure)!==false ||
                 strpos($field, $escape_char)!==false ||
                 strpos($field, "\n")!==false ||
                 strpos($field, "\r")!==false ||
                 strpos($field, "\t")!==false ||
                 strpos($field, ' ')!==false ) ) {

                 $field_len = strlen($field);
                 $escaped = 0;

                 $csvline .= $enclosure;
                 for( $ch = 0; $ch < $field_len; $ch++ )    {
                     if( $field[$ch] == $escape_char && $field[$ch+1] == $enclosure && $enc_is_quote ) {
                         continue;
                     }elseif( $field[$ch] == $escape_char ) {
                         $escaped = 1;
                     }elseif( !$escaped && $field[$ch] == $enclosure ) {
                         $csvline .= $enclosure;
                     }else{
                         $escaped = 0;
                     }
                     $csvline .= $field[$ch];
                 }
                 $csvline .= $enclosure;
             } else {
                 $csvline .= $field;
             }

             if( $i++ != $field_cnt ) {
                 $csvline .= $delimiter;
             }
         }

         $csvline .= "\n";

         return fwrite($handle, $csvline);
     }
	 
	 
 }

