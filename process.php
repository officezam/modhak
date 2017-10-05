<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$output  = '';
$err     = '';
$campaID = '';

include( realpath( '.' ) . "/config.php" );
include( realpath( '.' ) . "/LIB_http.php" );
require( 'Twilio.php' );
require 'vendor/autoload.php';
use Plivo\RestAPI;


if ( isset( $_POST['campaignID'] ) ) {
	$campaID = $_POST['campaignID'];
}

if ( isset( $_POST['userName'] ) ) {
	$userName = $_POST['userName'];
}
if ( isset( $_POST['mobileNumber'] ) ) {
	$mobileNumber = $_POST['mobileNumber'];
}
if ( isset( $_POST['email'] ) ) {
	$email = $_POST['email'];
}

$mobileNumber = str_replace( " ", "", $mobileNumber );


$result = mysql_query( "select name,smsText,invalidURL,duplicateURL,sucessURL,forwardURL,country from campaign where ID=" . $campaID . " limit 1 " );
if ( mysql_num_rows( $result ) ) {

	$campaData = mysql_fetch_object( $result );

	$name    = $campaData->name;
	$smsText = $campaData->smsText;
	$smsText = str_replace( '%name%', $userName, $smsText );

	$invalidURL       = $campaData->invalidURL;
	$duplicateURL     = $campaData->duplicateURL;
	$sucessURL        = $campaData->sucessURL;
	$forwardURL       = $campaData->forwardURL;
	$countryCode      = $campaData->country;
	$countryName      = $COUNTRY[ $countryCode ];
	$answerForwardURL = '';

	$serverResponse = '';
	$smsStatus      = '';
	$resultURL      = '';


	$sql_qry_str = "select ID from subscriber where campID=" . $campaID . " and mobileNumber like '" . $mobileNumber . "' ";
	$result = mysql_query( $sql_qry_str);
	if ( mysql_num_rows( $result ) > 0 ) {
		$resultURL = $duplicateURL;
		$smsStatus = 'DUPLICATE';
		// user has been added already means duplicate user for this campaign
	} else {

		if ( preg_match( "/^[0-9]{10}$/", $mobileNumber ) ) {

			$message   = sendSMS( $mobileNumber, $countryCode, $smsText );
			$resultURL = $sucessURL;
			$smsStatus = 'VALID';

			if ( trim( $forwardURL ) <> "" ) {
				$answerForwardURL = $forwardURL . "?name=" . $userName . "&mobileNumber=" . $mobileNumber . "&email=" . $email;
				$response         = http_get( $answerForwardURL, "" );
			}

		} else {

			$resultURL = $invalidURL;
			$smsStatus = 'INVALID';
		}
	}
}


mysql_query( "insert into subscriber values('*'," . $campaID . ",'" . addslashes( $userName ) . "','" . addslashes( $mobileNumber ) . "','" . addslashes( $email ) . "',
		         '" . addslashes( $smsText ) . "','" . addslashes( $serverResponse ) . "','" . date( "Y-m-d h:i:s" ) . "','" . $smsStatus . "','" . $resultURL . "','" . addslashes( $answerForwardURL ) . "')" );


function sendSMS( $mobileNumber, $countryCode, $smsText ) {
	$result     = mysql_query( "select * from setting limit 1" );
	$data       = mysql_fetch_object( $result );
	$active_api = $data->active_api;
	$alphaSender = $data->alphaSender;
	$alphaSender = trim( $alphaSender );

	if ( $active_api == 'twilio' ) {

		$SID         = $data->SID;
		$key         = $data->key;
		$phoneNumber = $data->phoneNumber;
	} else {
		$SID         = $data->plivo_sid;
		$key         = $data->plivo_key;
		$phoneNumber = $data->plivo_number;
	}

	if ( $alphaSender <> "" ) {
		$fromNumber = $alphaSender;
	} else {
		$fromNumber = $phoneNumber;
	}
	$mobileNumber = $countryCode . $mobileNumber;

	if ( $active_api == 'twilio' ) {

		// SEND SMS USING TWILIO
		$account_sid  = trim( $SID ); // Your Twilio account sid
		$auth_token   = trim( $key ); // Your Twilio auth token
		$client       = new Services_Twilio( $account_sid, $auth_token );
		$fromNumber   = str_replace( "+", "", $fromNumber );
		$mobileNumber = str_replace( "+", "", $mobileNumber );
		$message      = $client->account->messages->sendMessage(
			'+' . $fromNumber, // From a Twilio number in your account
			'+' . $mobileNumber, // Text any number
			"" . $smsText . ""
		);

		return $message;
	} else {

		// SEND SMS USING PLIVO
		$auth_id      = trim( $SID );
		$auth_token   = trim( $key );
		$fromNumber   = str_replace( "+", "", $fromNumber );
		$p = new RestAPI($auth_id, $auth_token);
		$params = array(
			'src'  => $alphaSender,
			'dst'  => '+' . $mobileNumber,
			'text' => $smsText
		);

		$response = $p->send_message( $params );

		return  $response['response'];
	}

}
header( "Location:" . $resultURL );
?>