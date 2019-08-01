<?php 

abstract class PushNotificationHelper
{


	public static function sendGCM($regs, $message, $title = "", $subtitle = "", $ticker_text = "", $bundle = "")
	{
		// API access key from Google API's Console
		
		//$registrationIds = array( $_GET['id'] );
		$registrationIds = array($regs);
		// prep the bundle
		$msg = array
		(
			'message' 	=> $message,
			'title'		=> $title,
			'subtitle'	=> $subtitle,
			'tickerText'	=> $ticker_text,
			'bundle_object'	=> $bundle,
			'vibrate'	=> 1,
			'sound'		=> 1,
			'largeIcon'	=> 'large_icon',
			'smallIcon'	=> 'small_icon'
		);

		//var_dump($msg);
		$fields = array
		(
			'registration_ids' 	=> $registrationIds,
			'data'			=> $msg
		);
		 
		$headers = array
		(
			'Authorization: key=-I4',
			'Content-Type: application/json'
		);
		 
		$ch = curl_init();
		curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
		curl_setopt( $ch,CURLOPT_POST, true );
		curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, true );
		curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
		$result = curl_exec($ch );
		curl_close( $ch );
		
	}

}
