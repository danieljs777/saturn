<?php

/*
 * ******************************************************************************

  Developed by Daniel Jordao Santana (daniel.js@gmail.com)
  Copyright (c) 2017 - Zillius Solutions (www.zillius.com.br)
  Code changes not allowed, doing so will lose warranty of its functionality!

  All rights reserved.

 * ******************************************************************************
 */

class Api
{
	private $post;
	private $request;
	private $user_token;
	private $user_secret;
	private $headers;

	public function __construct($method = "GET", $check_mobile_session = false)
	{
		try
		{

			if($this->get_method() != strtoupper($method))
			{
				//header("HTTP/1.0 405 Method Not Allowed");
				// self::return_error("Method Not Allowed", true, 405);
				die();

			}

			foreach (getallheaders() as $name => $value)
			{
			    $this->headers[strtoupper(str_replace("-", "_", $name))] = $value;
			}

			if($method == "POST" || $method == "PUT")
			{

				$this->request = json_decode(utf8_encode(file_get_contents('php://input')));

				// var_dump($this->request);

				// var_dump(mb_detect_encoding(file_get_contents('php://input')));

				if(isset($this->request->POST_DATA))
				{
					if(mb_detect_encoding(file_get_contents('php://input')) == "UTF-8")
					{
						foreach($this->request->POST_DATA as $key => $value)
						{
							if(is_array($value))
							{
								foreach ($value as $_key => $_value)
								{
									$this->request->POST_DATA->$key[$_key] = $_value;
								}
							}
							else
								$this->request->POST_DATA->$key = utf8_decode($value);
						}
					}
					else
					{
						foreach($this->request->POST_DATA as $key => $value)
						{
							

							if(is_array($value))
							{
								foreach ($value as $_key => $_value)
								{
									$this->request->POST_DATA->$key[$_key] = $_value;
								}
							}
							else
								$this->request->POST_DATA->$key = utf8_decode($value);

						}
					}

					$this->post = $this->request->POST_DATA;
				}

			}

			if($check_mobile_session)
			{
				$this->user_token = $this->get_header("TOKEN");
				$this->user_secret = $this->get_header("SECRET");

				if(!$this->auth_session())
					self::return_error("Token não encontrado!");
				
			}

		}
		catch(Exception $e)
		{
			self::return_error("Erro durante o parse do Payload : " . $e->getMessage());
		}

	}

	public function get_header($name)
	{
		if(isset($this->headers[$name]))
			return $this->headers[$name];

	}

	public function get_method()
	{
		return $_SERVER["REQUEST_METHOD"];
	}

	public function get_token()
	{
		return $this->user_token;		
	}

	public function set_token($token)
	{
		$this->user_token = $token;
	}

	public function get_user_secret()
	{
		return $this->user_secret;		
	}

	public function auth_session()
	{
		//var_dump($_SESSION);
		if(!isset($_SESSION["user_logged"]))
		{
			if($this->user_token != "")
			{
				$user_model = Action::load_model("user");

				$user_data = $user_model->get_by_token($this->user_token);

				if(System::is_filled($user_data))
				{
					$_SESSION["app_user_logged"]  = 1;
					$_SESSION["app_user_id"]      = $user_data['dup_id'];
					$_SESSION["app_user_name"]    = $user_data['name'];
					$_SESSION["app_user_token"]   = $user_data['user_token'];
					
					return true;
				}
				else					
					return false;
			}
			else
			{
				self::return_error("Token não enviado");
				return false;
			}

		}

		return true;
	}	

	public function get_post()
	{
		
		if(isset($this->post))
			return $this->post;

	}

    public function get_hash()
    {

    	$hash = md5(date("Y-m-d H") . $this->token . $this->uuid);
    	return $hash;

    }	

    public static function check_permissions($module, $action)
    {
        $_api_modules["user"]["register"] = 1;
        $_api_modules["user"]["get_info"] = 1;
        $_api_modules["user"]["get_dashboard"] = 1;
        $_api_modules["user"]["auth"] = 1;
        $_api_modules["user"]["auth_by_facebook"] = 1;
        $_api_modules["user"]["update"] = 1;
        $_api_modules["user"]["check_email"] = 1;
        $_api_modules["transaction"]["get_all"] = 1;
        $_api_modules["invite"]["get_all"] = 1;
        $_api_modules["invite"]["register"] = 1;
        $_api_modules["parameter"]["get_insurances"] = 1;
        $_api_modules["award"]["get_all"] = 1;

        if (array_key_exists($module, $_api_modules))
        {

            if (isset($_api_modules[$module][$action]))
                return true;
            elseif (isset($_api_modules[$module]["*"]))
                return true;
            else
                return false;
        }
        else
            return false;   
    }    

	public static function return_object($name, $data)
	{
		$json_return = array("success" => true, $name => $data);
		header('Content-Type: application/json');
		die(json_encode($json_return));
	}

	public static function return_error($message, $finish = true, $error_code = 400)
	{

		if(function_exists("http_response_code"))
			http_response_code($error_code);
		else
			header("HTTP/1.0 " . $error_code);

		$message = str_replace(Input::$break_line, "\n", $message);

		$json_return = array("success" => false, "message" => $message);
		
		if($finish)
		{
			header('Content-Type: application/json');
			die(json_encode($json_return));
		}
		else
			return json_encode($json_return);
	}

	public static function return_success($message, $id = "", $finish = true)
	{
		if(function_exists("http_response_code"))
			http_response_code(200);
		else
			header("HTTP/1.0 200");

		$message = str_replace(Input::$break_line, "\n", $message);
		
		$json_return = array("success" => true, "message" => $message, "id"=> $id);
		if($finish)
		{
			header('Content-Type: application/json');
			die(json_encode($json_return));
		}
		else
			return json_encode($json_return);
	}

}