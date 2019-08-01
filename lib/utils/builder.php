<?php

/*
 * ******************************************************************************

  Developed by Daniel Jordao Santana (daniel.js@gmail.com)
  Copyright (c) 2017 - Zillius Solutions (www.zillius.com.br)
  Code changes not allowed, doing so will lose warranty of its functionality!

  All rights reserved.

 * ******************************************************************************
 */

abstract class Builder
{
	public static function buildAllUfInBR($fname, $sValue, $Params="", $label="", $display = true)
	{
		$UF = array();
		$UF[] = "AC";
		$UF[] = "AL";
		$UF[] = "AM";
		$UF[] = "AP";
		$UF[] = "BA";
		$UF[] = "CE";
		$UF[] = "DF";
		$UF[] = "ES";
		$UF[] = "GO";
		$UF[] = "MA";
		$UF[] = "MG";
		$UF[] = "MS";
		$UF[] = "MT";
		$UF[] = "PA";
		$UF[] = "PB";
		$UF[] = "PE";
		$UF[] = "PI";
		$UF[] = "PR";
		$UF[] = "RJ";
		$UF[] = "RN";
		$UF[] = "RO";
		$UF[] = "RR";
		$UF[] = "RS";
		$UF[] = "SC";
		$UF[] = "SE";
		$UF[] = "SP";
		$UF[] = "TO";

		$html = "<select name='".$fname."' id='".$fname."' ". $Params .">";
		if($label != "")
		{
			$html .= "<option value=''>$label</option>";
		}
		else if($sValue == "")
			$html .= "<option value=''></option>";


		for($x=0;$x<count($UF);$x++)
		{
			$opt = ($UF[$x] == $sValue) ? " selected" : "";
			$html .= "<option value='".$UF[$x]."'".$opt.">". $UF[$x] ."</option>";
		}
		$html .= "</select>";

		if($display)
			echo $html;
		else
			return $html;
	}

	public static function buildListFrTo($nName, $nFr, $nTo, $sValue, $label="", $params="")
	{
		echo ("<select name=" . $nName . " $params>");

		if($label != "" && $label != "-")
		{
			$opt = ($sValue == "") ? " selected" : "";
			
			echo "<option value=''" . $opt . ">$label</option>";
		}

		for($x=$nFr; $x<=$nTo; $x++)
		{
			$opt = ($x == $sValue) ? " selected" : "";
			echo "<option value='$x'" . $opt . ">$x</option>";
		}

		echo ("</select>");

	}

	public static function buildListArray($nName, $data, $sValue, $label="", $params="")
	{
		echo ("<select name=" . $nName . " $params>");

		if($label != "" && $label != "-")
		{
			$opt = ($sValue == "") ? " selected" : "";
			
			echo "<option value=''" . $opt . ">$label</option>";
		}

		foreach($data as $_id => $_value)
		{
			$opt = ($_id == $sValue) ? " selected" : "";
			echo "<option value='$_id'" . $opt . ">$_value</option>";
		}

		echo ("</select>");

	}

	public static function buildMonthList($nName, $sValue, $label="", $params="")
	{
		echo ("<select name=" . $nName . " $params>");

		for($x=1; $x<=12; $x++)
		{
			$opt = ($x == $sValue) ? " selected" : "";
			echo "<option value='$x'" . $opt . ">" . date("F", mktime(0, 0, 0, $x, 10)) . "</option>";
		}

		echo ("</select>");

	}

	public static function buildListTable($Name, $Table, $fieldId, $fieldLabel, $strCriteria, $intSelected, $label="", $params="", $selected="='selected'")
	{
		$database = PDOHelper::singleton();

		$SelList = "Select $fieldId, $fieldLabel from $Table $strCriteria order by $fieldLabel";
		$q_SelList = $database->execute($SelList);

		echo ("<select name=\"".$Name."\" id=\"".str_replace('[]', '', $Name)."\" $params>");

		if(!is_array($intSelected))
		{
			if ($intSelected == '' && $label =='')
			{
				echo "<option value=''>Selecione</option>";
				echo "<option value='' disabled>------------</option>";
			}
		}

		if($label != "" && $label != "-")
		{
			echo "<option value=''>$label</option>";
		}

		foreach($q_SelList as $a_SelList)
		{
			if (is_array($intSelected))
				$opt = (array_search((int)$a_SelList[$fieldId], $intSelected) > -1) ? " selected$selected" : "";
			else
				if(is_numeric($a_SelList[$fieldId]))
					$opt = ((int)$a_SelList[$fieldId] == (int)$intSelected) ? " selected$selected" : "";
				else
					$opt = ($a_SelList[$fieldId] == $intSelected) ? " selected$selected" : "";

			echo "<option value='".$a_SelList[$fieldId]."'".$opt.">".$a_SelList[$fieldLabel]."</option>";
		}

		echo ("</select>");

	}
	
	public static function buildTimeZoneList($Name, $intSelected, $label="", $params="")
	{
		
		echo ("<select name=\"".$Name."\" id=\"".$Name."\" $params>");
		
		$tz_list = System::generate_timezone_list();
		
		foreach($tz_list as $timezone)
		{
			$opt = ((int)$timezone[0] == (int)$intSelected) ? " selected" : "";
			echo "<option value='" . $timezone[0] . "'" . $opt . ">" . $timezone[1] . "</option>";
		}

		echo ("</select>");
		
	}
	
}
?>