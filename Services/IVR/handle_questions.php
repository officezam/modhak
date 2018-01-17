<?php
require_once("../../connect/connect.php");
require_once('../Twilio.php');	
require_once('function.php');	



//$language		 	= $_GET['lang'];
$campaign_id	 	= $_GET['camp_id'];
$widget_id	 		= $_GET['widget_id'];
$current_wId 		= $_GET['current_wId'];


$CallSid			= $_REQUEST['CallSid'];


/*$qry_survey = "SELECT * FROM survey WHERE s_id = ". $campaign_id;
$exe_survey = mysql_query ( $qry_survey );
$row_survey = mysql_fetch_assoc( $exe_survey );
$repeat_key = $row_survey['repeat_key'];

$errormessage1 = $row_survey['errormessage1'];
$errormessage2 = $row_survey['errormessage2'];*/
//mail("rizwan@zamsol.com", "file name", $errormessage1);

$repeat_key = "*";

$qry_widget = "SELECT * FROM call_ivr_widget WHERE companyId = ". $campaign_id. " AND wId = ".$current_wId;
$exe_widget = mysql_query ( $qry_widget );
$row_widget = mysql_fetch_assoc( $exe_widget );

$flow_type  = $row_widget['flowtype'];
$question  	= $row_widget['question'];
$label  	= $row_widget['label'];
$range  	= $row_widget['range'];
if($range != ''){
	$range_arr = explode("-", $range);
	$s_range   = $range_arr[0];
	$e_range   = $range_arr[1];
}
if($s_range == '' || $e_range == ''){
	$s_range = 1;
	$e_range = 5;
}


header('Content-type: text/xml');
echo '<?xml version="1.0" encoding="UTF-8"?>';
echo '<Response>';

	$option_feedback = $_REQUEST['Digits'];
	
	/*$myvars = "option_feedback = ".$option_feedback."\n";
	$myvars .= "repeat_key = ".$repeat_key."\n";
	$myvars .= "s_range = ".$s_range."\n";
	$myvars .= "e_range = ".$e_range."\n";
	
	mail("rizwan@zamsol.com", "Variables", $myvars);*/
	
	if($option_feedback != $repeat_key and $option_feedback != '' and $option_feedback >= $s_range and $option_feedback <= $e_range)
	{
		
		  // inserting in the log table 		
		  $query_insert_plog  = " INSERT INTO survey_widget_log ";
		  $query_insert_plog .= " SET ";
		  $query_insert_plog .= " survey_id   	= '" . $campaign_id 		. "',";
		  $query_insert_plog .= " widget_id 	= '" . $current_wId 		. "',";
		  $query_insert_plog .= " widget_type	= '" . $flow_type 			. "',";
		  $query_insert_plog .= " call_sid		= '" . $_REQUEST['CallSid']	. "',";
		  $query_insert_plog .= " question   	= '" . addslashes($question). "',";
		  $query_insert_plog .= " label			= '" . addslashes($label)	. "',";
		  $query_insert_plog .= " answer 		= '" . $option_feedback 	. "' ";
		  
		  /*if($current_wId == 14){
			  mail("rizwan@zamsol.com", "Query Insert", $query_insert_plog);
		  }*/
		  
		 $exec_q_ipl		 = @mysql_query ( $query_insert_plog );
		  
		  
		  //echo '<Say>Thanks for participating</Say>';
	
		  if ( $widget_id == '0') {
			  echo '<Hangup/>';
		  }
		  else {
			  echo '<Redirect>ivrmenu.php?campaignId='.$campaign_id.'&amp;wId='.$widget_id.'&amp;lang='.$language.'&amp;tcx='.strtotime('now').'</Redirect>';
		  }
	
	
	
	}
	else
	{
		
		if ( $row_widget['repeat'] == 'Yes'){
			
			$rep_count	= $_GET['rep_count'];
			
			if($rep_count < 3){
				
				$rep_count	= $rep_count + 1;
			
				if($option_feedback != $repeat_key){
					
					$audioFilePath = ru_dir.'media/audio/'.$campaign_id.'/errormessage/'.$errormessage1;
					$audioFileUrl  = ru	   .'media/audio/'.$campaign_id.'/errormessage/'.$errormessage1;
					
					if (file_exists($audioFilePath))
					{
						echo '<Play loop="1">'.$audioFileUrl.'</Play>';
					}else{
						echo '<Say>You have pressed an invalid key. Please try again.</Say>';
					}
					//cost_delete
					echo '<Redirect>ivrmenu.php?campaignId='.$campaign_id.'&amp;wId='.$current_wId.'&amp;rep_count='.$rep_count.'&amp;tcx='.strtotime('now').'</Redirect>';
					
				}if($option_feedback != $repeat_key and ($option_feedback < $s_range || $option_feedback > $e_range)){
				
					$audioFilePath = ru_dir.'media/audio/'.$campaign_id.'/errormessage/'.$errormessage1;
					$audioFileUrl  = ru	   .'media/audio/'.$campaign_id.'/errormessage/'.$errormessage1;
	
					if (file_exists($audioFilePath))
					{
						echo '<Play loop="1">'.$audioFileUrl.'</Play>';
					}else{
						echo '<Say>You have pressed an invalid key. Please try again.</Say>';
					}
					
					echo '<Redirect>ivrmenu.php?campaignId='.$campaign_id.'&amp;wId='.$current_wId.'&amp;rep_count='.$rep_count.'&amp;tcx='.strtotime('now').'</Redirect>';
				
				
				}else{ //.'&amp;lang='.$language
					echo '<Redirect>ivrmenu.php?campaignId='.$campaign_id.'&amp;wId='.$current_wId.'&amp;rep_count='.$rep_count.'&amp;tcx='.strtotime('now').'</Redirect>';
				}
			
			}// end of repeat count condition
			else{
				
				$audioFilePath = ru_dir.'media/audio/'.$campaign_id.'/errormessage/'.$errormessage2;
				$audioFileUrl  = ru	   .'media/audio/'.$campaign_id.'/errormessage/'.$errormessage2;
				
				if (file_exists($audioFilePath))
				{
					echo '<Play loop="1">'.$audioFileUrl.'</Play>';
				}else{
					echo '<Say>You have pressed an invalid key. Please try again.</Say>';
				}
				
				 echo '<Hangup/>';
				
			}
			
		}else{
			
			// inserting in the log table 		
			$query_insert_plog  = " INSERT INTO survey_widget_log ";
			$query_insert_plog .= " SET ";
			$query_insert_plog .= " survey_id   = '" . $campaign_id 		. "',";
			$query_insert_plog .= " widget_id 	= '" . $current_wId 		. "',";
			$query_insert_plog .= " widget_type	= '" . $flow_type 			. "',";
			$query_insert_plog .= " call_sid	= '" . $_REQUEST['CallSid']	. "',";
			$query_insert_plog .= " question   	= '" . addslashes($question). "',";
			$query_insert_plog .= " label		= '" . addslashes($label)	. "',";
			$query_insert_plog .= " answer 		= '" . $option_feedback 	. "' ";
						
			$exec_q_ipl			= @mysql_query ( $query_insert_plog );
			
			if ( $widget_id == '0') {
				echo '<Hangup/>';
			}
			else { //.'&amp;lang='.$language
				echo '<Redirect>ivrmenu.php?campaignId='.$campaign_id.'&amp;wId='.$widget_id.'&amp;tcx='.strtotime('now').'</Redirect>';
			}
		  
		}
		
	}
	
	
	

echo '</Response>';?>