<?php

/*
 * ******************************************************************************

  Developed by Daniel Jordao Santana (daniel.js@gmail.com)
  Copyright (c) 2017 - Zillius Solutions (www.zillius.com.br)
  Code changes not allowed, doing so will lose warranty of its functionality!

  All rights reserved.

 * ******************************************************************************
 */

abstract class Javascript
{
	
	public static function window_open($wurl, $wname, $wparams)
	{
		$retVal = "<script language=\"JavaScript\">";
		$retVal = $retVal . " window.open(\"".wurl."\", \"".wname."\", \"".wparams."\");";
		$retVal = $retVal . "</script>";
		echo($retVal);
	}
	
	
	public static function show_message($vString, $dAction)
	{
		$retVal = "<script language='Javascript'>";
		if ($vString != " ")
		{
			$retVal = $retVal .  "	window.alert('".$vString."');";
		}
		if ($dAction == "back")
		{
			$retVal = $retVal .  "	history.go(-1);";
		}
		elseif ($dAction == "stop")
		{
			$retVal = $retVal .  "	parent.self.close();";
		}
		elseif ($dAction == "refresh_opnr")
		{
			$retVal = $retVal .  "	sourcex = window.opener.location.href;";
			$retVal = $retVal .  "	if (sourcex.indexOf('#') > 0)";
			$retVal = $retVal .  "		window.opener.location.href = sourcex.substr(0, sourcex.length-1);";
			$retVal = $retVal .  "	else";
			$retVal = $retVal .  "		window.opener.location.href = sourcex;";
			$retVal = $retVal .  "	parent.self.close();";
		}
		elseif (strpos($dAction,"[O]") > -1)
		{
			$retVal = $retVal .  "    window.opener.location.href='".substr($dAction, 4, strlen($dAction)-4)."';";
		}
		elseif (strpos($dAction,"[P]") > -1)
		{
			$retVal = $retVal .  "    parent.location.href='".substr($dAction, 4, strlen($dAction)-4)."';";
		}
		else
		{
			$retVal = $retVal .  "	document.location.href='".$dAction."';";
		}
		
	
		$retVal = $retVal .  "</script>";
		die($retVal);
	
	
	}
	
	public static function window_close()
	{
		$retVal = $retVal .  "<script language=\"Javascript\">";
		$retVal = $retVal .  " window.parent.close();";
		$retVal = $retVal .  "</script>";
		die($retVal);
	
	}
}

?>