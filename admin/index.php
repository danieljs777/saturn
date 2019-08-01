<?php
/*
*******************************************************************************

Developed by Daniel Jordão Santana (daniel.js@gmail.com)
Copyright (c) 2012 - Aeon Systems (www.aeonsystems.com.br)
Code changes not allowed, doing so will lose warranty of its functionality!

All rights reserved.

*******************************************************************************
*/

require "../lib/init.php";

$key_factory = KeyFactory::singleton();				
$key_factory->set('app_name', 'admin');		

$original_request = $_SERVER['REQUEST_URI'];
$original_request = explode("?", $original_request);

@list($module, $action, $params) = explode("/", $original_request[1]);

$params_arr = explode("&", $params);

$params = $params_arr[0];

if (($module == "") && ($action == ""))
{
	$module = isset($_REQUEST['m']) ? $_REQUEST['m'] : '';
	$action = isset($_REQUEST['a']) ? $_REQUEST['a'] : '';
	$params	= isset($_REQUEST['i']) ? $_REQUEST['i'] : '';
}

if (!System::islogged_admin() && !in_array($module."/".$action, $open_modules))
	System::do_action("admin", "request_login");

$module = ($module == null) ? 'home' : strtolower($module);
$action = strtolower($action);

System::do_action($module, $action, $params);



