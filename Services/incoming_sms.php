<?php

$conn = mysqli_connect('localhost','smsreply','smsreply','smsreply')
or die('Error connecting to MySQL server.');

// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";

require_once('Twilio.php');

$postData = '';
foreach($_REQUEST as $key => $val)
{
	$postData .= $key." => ".$val."\n \r";
}
//mail("officezam@gmail.com", "SMS Responce", $postData);


if(isset($_REQUEST['To']) && $_REQUEST['To'] != '' ){
$response 		= new Services_Twilio_Twiml;
$twilio_Number  = $_REQUEST['To'];
$user_phone 	= $_REQUEST['From'];
$Body 		    = $_REQUEST['Body'];
$sms_status	    = $_REQUEST['Status'];
$status			= 'New';
$curreent_date  = date('m-d-Y H:i:s');
	
$query = "INSERT INTO receive_sms SET 
									`from` 		= '$user_phone' ,
									`to` 		= '$twilio_Number' , 
									`keyword` 		= '$Body' ,
									`reply_status`	= '$status',
									 ";
	//$conn->do_query( $query );

	if (mysqli_query($conn, $query)) {
		$query =  "New record created successfully";
		//mail("officezam@gmail.com", "SMS Query Responce Success", $query);
	} else {
		$query =  "Error: " . $sql . "<br>" . mysqli_error($conn);
		//mail("officezam@gmail.com", "SMS Query Responce Error", $query);
	}


}
    ?>