<?
/*
*******************************************************************************

Developed by Daniel Jordão Santana (daniel.js@gmail.com)
Copyright (c) 2012 - Aeon Systems (www.aeonsystems.com.br)
Code changes not allowed, doing so will lose warranty of its functionality!

All rights reserved.

*******************************************************************************
*/

error_reporting(E_ALL);

// PHP Settings
ini_set('output_buffering', 4096);
ini_set('upload_max_filesize', '10M');
//ini_set('memory_limit', '5M');
ini_set('post_max_size', '16M');
ini_set('expose_php', 'Off');

ob_start();
session_start();


header("Content-Type:text/html; charset=utf-8");


require_once "config.php";
require_once PATH_ROOT . "/lib/system/nocache.php";
require_once PATH_ROOT . "/lib/system/system.php";
require_once PATH_ROOT . "/lib/database/mysql.php";
require_once PATH_ROOT . "/lib/engine/keyfactory.php";
require_once PATH_ROOT . "/lib/engine/render.php";
require_once PATH_ROOT . "/lib/engine/phpmailer.php";
require_once PATH_ROOT . "/lib/engine/basemodel.php";
require_once PATH_ROOT . "/lib/engine/basecontroller.php";
require_once PATH_ROOT . "/lib/engine/baseview.php";
require_once PATH_ROOT . "/lib/engine/i18n.php";
require_once PATH_ROOT . "/lib/system/input.php";
require_once PATH_ROOT . "/lib/system/log.php";
require_once PATH_ROOT . "/lib/system/javascript.php";
require_once PATH_ROOT . "/lib/system/builder.php";


if (version_compare(phpversion(), '5.1.0', '<') == true)
{
	exit('PHP5.1 is required!');
}

if (!ini_get('date.timezone'))
	date_default_timezone_set('America/Sao_Paulo');

if (!isset($_SERVER['DOCUMENT_ROOT']))
{
	if (isset($_SERVER['SCRIPT_FILENAME']))
		$_SERVER['DOCUMENT_ROOT'] = str_replace('\\', '/', substr($_SERVER['SCRIPT_FILENAME'], 0, 0 - strlen($_SERVER['PHP_SELF'])));
}

if (!isset($_SERVER['DOCUMENT_ROOT']))
{
	if (isset($_SERVER['PATH_TRANSLATED']))
		$_SERVER['DOCUMENT_ROOT'] = str_replace('\\', '/', substr(str_replace('\\\\', '\\', $_SERVER['PATH_TRANSLATED']), 0, 0 - strlen($_SERVER['PHP_SELF'])));
}

if (!isset($_SERVER['REQUEST_URI']))
{
	$_SERVER['REQUEST_URI'] = substr($_SERVER['PHP_SELF'], 1);

	if (isset($_SERVER['QUERY_STRING']))
		$_SERVER['REQUEST_URI'] .= '?' . $_SERVER['QUERY_STRING'];
}

$open_modules = array("admin/auth", "user/save", "realty/edit", "realty/save");



