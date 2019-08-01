<?php
/*
*******************************************************************************

Developed by Daniel Jordão Santana (daniel.js@gmail.com)
Copyright (c) 2012 - Aeon Systems (www.aeonsystems.com.br)
Code changes not allowed, doing so will lose warranty of its functionality!

All rights reserved.

*******************************************************************************
*/

require "lib/init.php";

$key_factory = KeyFactory::singleton();				
$key_factory->set('app_name', 'site');		

$uri = $_SERVER['REQUEST_URI'];

echo System::get_i18n_term("datagrid.statusbar.paging", 20, 60);
die();

if(APACHE_RWENGINE == 'On')
	@list($none, $page, $objid, $tag) = explode("/", $uri);
else
{
//	$page  = isset($_REQUEST['p']) ? $_REQUEST['p'] : 'index';
//	$id    = isset($_REQUEST['i']) ? $_REQUEST['i'] : '';
//	$tag   = isset($_REQUEST['t']) ? $_REQUEST['t'] : '';

	$original_request = explode("?", $_SERVER['REQUEST_URI']);
	
	@list($page, $objid, $tag) = explode("/", $original_request[1]);


}

$page = ($page == NULL) ? 'index' : strtolower($page);

if(file_exists(APP_ROOT . "/site/" . $page . ".php"))
	include APP_ROOT . "/site/" . $page . ".php";
else
	echo $page . " not found";
	header("Status: 404 Not Found");
	

