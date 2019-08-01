<?php

/*
 * ******************************************************************************

  Developed by Daniel Jordao Santana (daniel.js@gmail.com)
  Copyright (c) 2017 - Zillius Solutions (www.zillius.com.br)
  Code changes not allowed, doing so will lose warranty of its functionality!

  All rights reserved.

 * ******************************************************************************
 */

class POI {
	private $latitude;
	private $longitude;
	
	public function __construct($latitude, $longitude)
	{
		$this->latitude = deg2rad($latitude);
		$this->longitude = deg2rad($longitude);
	}
	
	public function getLatitude() { return $this->latitude; }
	
	public function getLongitude() { return $this->longitude; }

	public function getDistanceInMetersTo(POI $other)
	{
		$radiusOfEarth = 6371000;// Earth's radius in meters.
		$diffLatitude = $other->getLatitude() - $this->latitude;
		$diffLongitude = $other->getLongitude() - $this->longitude;
	
		$a = sin($diffLatitude / 2) * sin($diffLatitude / 2) +
		    cos($this->latitude) * cos($other->getLatitude()) *
		    sin($diffLongitude / 2) * sin($diffLongitude / 2);
	
		$c = 2 * asin(sqrt($a));
	
		$distance = $radiusOfEarth * $c;
	
		return $distance;
	}

	public function getDistanceInKmTo(POI $other)
	{
		return $this->getDistanceInMetersTo($other) / 1000;
	}

	// $user = new POI($_GET["latitude"], $_GET["longitude"]);
	// $poi = new POI(19,69276, -98,84350); // Piramide del Sol, Mexico
	// echo $user->getDistanceInMetersTo($poi);	
}

class Geolocation
{
	
	private static $api_key = "";
	
	public static function geo2address($lat,$long)
	{
		$url = "https://maps.googleapis.com/maps/api/geocode/json?address=?latlng=$lat,$long";
		$curlData=file_get_contents($url);
		$address = json_decode($curlData);
		$a=$address->results[0];
		return explode(",",$a->formatted_address);
	}
	
	public static function address2geo($address)
	{
		//$address = urlencode($row["building_street"]." ".$row["building_street_nr"]." ".$row["building_city"]." Czech republic");
		$address = str_replace(" ", "+", $address);
	
		$link = "https://maps.googleapis.com/maps/api/geocode/json?address=".$address."&key=".self::$api_key."";
		
		$page = file_get_contents($link);
		
		$addr_data = (json_decode($page));

		if(is_object($addr_data))
		{
			$data['latitude'] = ($addr_data->results[0]->geometry->location->lat);
			$data['longitude'] = ($addr_data->results[0]->geometry->location->lng);
			$data['formatted_address'] = ($addr_data->results[0]->formatted_address);
			
		}

		return $data;
		
	}

	public static function full_details($address)
	{
		//$address = urlencode($row["building_street"]." ".$row["building_street_nr"]." ".$row["building_city"]." Czech republic");
		$address = str_replace(" ", "+", $address);
	
		$link = "https://maps.google.com/maps/api/geocode/json?address=".$address."&key=".self::$api_key."";
		
		$page = file_get_contents($link);
		
		return (json_decode($page));
		
	}	

	public static function search_cep($cep)
	{
		$cep = str_replace("-", "", $cep);

		if(strlen($cep) == 8)
		{
			$link = "http://viacep.com.br/ws/" . $cep . "/json/";
			
			$page = file_get_contents($link);
			
			return (json_decode($page));		

		}
	}

}