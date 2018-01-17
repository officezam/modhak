<?php
require_once("../../connect/connect.php");
require_once('../Twilio.php');	
require_once('function.php');	


$wId = $_REQUEST['wId'];
$flow_id = $_REQUEST['flow_id'];
$query		= "SELECT user_id FROM `ivr_flow` WHERE flow_id = '$flow_id' ";
$rsUser		= mysql_query( $query );
//echo $query;

if ( mysql_num_rows( $rsUser ) > 0 ) {
	
	$rowUser	= mysql_fetch_array( $rsUser );
	$user_id	= $rowUser['user_id'];

} 
	
$_REQUEST['atmpt'] =1;
$_REQUEST['lastAtmpt'] =1;

header('Content-type: text/xml');
echo '<?xml version="1.0" encoding="UTF-8"?><Response>';

if ( ( isset ($_REQUEST['atmpt'] ) )  				and 
     ( $_REQUEST['lastAtmpt'] != 0)					and 
	 ( $_REQUEST['DialCallStatus'] 	== 'busy' 		or 
	   $_REQUEST['DialCallStatus'] 	== 'no-answer'  or 
	   $_REQUEST['DialCallStatus']	=='failed') ) 	{
		   		   
			$flow_id = $_REQUEST['flow_id'];
			
			
			echo '<Redirect>ivrmenu.php?flow_id='.$flow_id.'&amp;wId='.$wId.'</Redirect>';
			
}	
else {
	
	if ( isset($_REQUEST['wId'] ) ) {
			
		$wId 		= $_REQUEST['wId'];
		$flow_id = $_REQUEST['flow_id'];
		echo '<Redirect>ivrmenu.php?flow_id='.$flow_id.'&amp;wId='.$wId.'</Redirect>';
		
		
	}
}
?></Response>