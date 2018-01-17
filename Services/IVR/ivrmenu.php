<?php
require_once("../../connect/connect.php");
require_once('../Twilio.php');	
require_once('function.php');	


if ( isset ( $_GET['flow_id']) && trim ($_GET['flow_id'] ) != '')
{	
	$flow_id 	= $_GET['flow_id'];
	$query_ivr 	= "SELECT * FROM  `ivr_flow`  WHERE flow_id  ='".$flow_id."'";
	$exec_query	= $dbo->do_query( $query_ivr );
    $rowIvr 	= $dbo->get_row_array( $exec_query );

	$voice 	  	= $rowIvr['voice_speak'];
	$language 	= $rowIvr['voice_language'];
	$selection 	= $rowIvr['selection'];
	$contact_id	= $rowIvr['contact_id'];
}


header('Content-type: text/xml');
//header("Content-Type: text/php;");
	
$response = new Services_Twilio_Twiml;


if ( $flow_id == FALSE ) {
	$response->reject( array( "reason"=>"busy" ) );
}


if( isset ( $_REQUEST['wId'] ) ) {	
	$getStarted  =	$_REQUEST['wId'];
}
else {
	$getStarted  = getStarted($flow_id); 
}

//print_r($getStarted);exit;

if ( $getStarted == '' )
{
	 $response->reject(array("reason"=>"busy"));
}

if ( $getStarted )
{
	$kp ='-';
	if ( $_REQUEST['kp'] !='' )		$kp  =	$_REQUEST['kp'];
	$widget= getWidget($getStarted,$kp);
}

//echo $widget['flowtype'];exit;
//$widget_name = 'Greetings';
switch ( $widget['flowtype'] ) :


	case "Greetings":{		
		//echo 'test';exit;
		if ( $widget['content_type']  == 'Text' ){
			
			$widget_content = $widget['content'];
			//echo $widget_content;exit;
			$response->say($widget_content, array("voice" => $voice, "language" => $language));
		
		}elseif ($widget['content_type']  == 'Audio' )	
			$response->play(ru.'media/audio/'.$flow_id.'/'.$widget['wId'].'/'.$widget['content'], array("loop" => "1"));
		
		if ( $widget['nId'] == '0'){
			$response->hangup();
		}else{
			$response->redirect('ivrmenu.php?flow_id='.$flow_id.'&wId='. $widget['nId'].'&tcx='.strtotime('now'));
		}		
	
		break;
	} // end of case Greetings
	//echo 'Test';exit;	
	case "Menu":{
			//echo 'menu';exit;	
		$rdir=0;
		if (isset ($_REQUEST['rdir'] ) ) {			
			$rdir = $_REQUEST['rdir'];
		}
		
		if ( strlen( $_REQUEST['Digits'] ) ) {
			
			$digits 	= $_REQUEST['Digits'];
			$ivr_detail = getMenuItems( $widget['wId'] );
			
			if ( array_key_exists( $digits,	$ivr_detail ) ) {
				
				$response->redirect('ivrmenu.php?flow_id='.$flow_id.'&wId='. $ivr_detail[$_REQUEST['Digits']]['wId'].'&kp='.$_REQUEST['Digits'] .'&tcx='.strtotime('now'));					
			}
			else{
												
				$response->say('please enter valid digit', array("voice" => $voice_speak, "language" => $voice_language));
				$response->pause(NULL, array ('length'=>'2'));
				$response->redirect('ivrmenu.php?flow_id='.$flow_id.'&wId='. $widget['wId'].'&rdir='. $rdir .'&tcx='.strtotime('now'));
			}
		}
		else{			
		
			$menu_repeat =  intval($widget['meta_data']);
			if ( $menu_repeat == 0) $menu_repeat =1;		
				$gather = $response->gather(array("numDigits" => "1"));
			
			if ( $widget['content_type']  == 'Text' )
				$gather->say($widget['content'], array("voice" => $voice_speak, "language" => $voice_language));
			
			elseif ($widget['content_type']  == 'Audio' )	
			
				$gather->play(ru.'media/audio/'.$flow_id.'/'.$widget['wId'].'/'.$widget['content'], array("loop" => "1"));
			
			
			if ( $rdir <  $menu_repeat ) {				
				$rdir++;
				$response->redirect('ivrmenu.php?flow_id='.$flow_id.'&wId='. $widget['wId'].'&rdir='. $rdir.'&tcx='.strtotime('now'));
			
			}
			elseif ( $widget['nId'] == '0'){			
				$response->hangup();			
			}
			else {
				$response->redirect('ivrmenu.php?flow_id='.$flow_id.'&wId='. $widget['nId'].'&tcx='.strtotime('now'));
			}
		
		}
		
		break;
	} // end of case Menu
	
	case "Dial": {		
			
		$number = $widget['content'];
		
		//checking the number is interenation or not
		if ( $widget['data'] == 'Y' )
			$number = "+".$number;
		else
			$number = "+1".$number;
		
		//setting timeout variable
		$timeOut = 30;
		
		if ( $widget['meta_data'] != '' )
			$timeOut = $widget['meta_data'];
		
		if ( $widget['nId'] != '0')
			$nextWidgetURL ='&flow_id='.$flow_id.'&wId='.$widget['nId'];
		else
			$nextWidgetURL ='&flow_id='.$flow_id;
		//initiating message 
		/// $response->say('please wait your call will be connecting ');
				
		//setting calls parms
		$dial_actions = array (
						'action'	=> 'record_call.php?DialCallTo='.urlencode( $number ). $nextWidgetURL,
						'method' 	=> 'POST',
						'timeout'	=>	$timeOut,
						'record' 	=> 'true'
						);
		
		//checing the recording status		
		if ( $RECORDINGS == false or $isCompanyRecordingDisabled )
			$dial_actions['record'] = 'false';
		
		//initiating any warining sound about recording
		if ( $recording_warning_url != "" )	{
			$response->play( $recording_warning_url );
		}
		
		//initiating call		
		$dial = $response->dial( $number, $dial_actions );
					
		break;
	} // end of case Dial
	
	case "RoundRobin":{
		

			  $last_idx =NULL;
			  $roundRobinIndex =  mysql_query( "SELECT `meta_data` FROM call_ivr_data WHERE  meta_key = 'last_idx'  and wId = '". $widget['wId'] ."' GROUP BY id ASC");
				if (mysql_num_rows($roundRobinIndex ) >0 ) {
					$roundRobinIndexRow= mysql_fetch_array($roundRobinIndex);
						$last_idx =$roundRobinIndexRow['meta_data'];
				}
				
				
				
				
				
				$timeout= '30';
				  $numbers = array();
$roundRobinNumber=  mysql_query( "SELECT `number` FROM call_ivr_round_robin WHERE wId =  '". $widget['wId'] ."' GROUP BY idx ASC");
				if (mysql_num_rows($roundRobinNumber ) >0 ) {
					while ( $roundRobinNumberRow= mysql_fetch_array($roundRobinNumber)){
						 $numbers[] =$roundRobinNumberRow['number'];
					}
						
				}
				
				
				
				$URL_data .='&rr=1&flow_id='.$flow_id.'&wId='. $widget['wId'].'&to='.$timeout;
				if ( $widget['nId'] != '0' && $widget['nId'] != '' )
				{
					$URL_data .='&nextId='. $widget['nId'];
					
				}
				
				
				
					
				if(count($numbers )==0)
				{
					$response->reject(array("reason"=>"busy"));
				}else{
					
					if($last_idx==NULL)
					{
						$this_idx = 0;
						$number =  $numbers[0];
						
					}else{
						$this_idx =intval($last_idx)+1;
						
					}
					
					if ( array_key_exists($this_idx,$numbers)){
						
							$number = $numbers[$this_idx];
						}else{
							$this_idx = 0;
							$number = $numbers[0];
							
						}
					mysql_query("delete from call_ivr_data  WHERE wId=".$widget['wId']);
					mysql_query("insert call_ivr_data SET meta_key = 'last_idx', meta_data = '$this_idx', wId = ".$widget['wId']);
					
					if($widget['content']=='0')
						$number = "+1".$number;
					else
						$number = "+".$number;

					
					$dial_actions = array(
							'action' => 'record_call.php?DialCallTo='.urlencode($number). $URL_data,
							'method' => 'POST',
							'timeout' => $timeout,
							'record' => 'true'
							
						);
					$dial = $response->dial($number,$dial_actions);
					
					
				
				}

				break;
			
	} // end of case RoundRobin
	
	case "MultiNumber":{
				$numberQuery = mysql_query("SELECT `number` FROM call_ivr_multiple_numbers WHERE wId ='".$widget['wId']."' GROUP BY idx ASC");
				
				$numbers = array();
				if (mysql_num_rows($numberQuery ) >0 ) {
					while ( $numberRow= mysql_fetch_array($numberQuery)){
						 $numbers[] =$numberRow['number'];
					}
						
				}
				if(count($numbers)==0)
				{
					$response->reject(array("reason"=>"busy"));
				}else{
					
					$nextURL ='';
					if ( $widget['nId'] != '0' && $widget['nId'] != '' ){
						$nxtURL ='&flow_id='.$flow_id.'&wId='. $widget['nId'];
					}
			
					$dial_actions = array(
						'action' => 'record_call.php?DialCallTo='.urlencode($number).$nxtURL,
						'method' => 'POST',
						'record' => 'true'
					);
				   

					

					$dial = $response->dial(NULL,$dial_actions);

					

				   foreach($numbers as $number)
						{
							if($widget['content']=='0')
								$numbertoDial = "+1".$number;
							else
								$numbertoDial = "+".$number;
								
							$dial->number($numbertoDial,array());
						}
						
				}
		
		break;
	} // end of case MultiNumber
	
	case "SMS":{
		
			$response->sms($widget['content']);
			
			if ( $widget['nId'] == '0'){
				$response->hangup();
			}else{
				$response->redirect('ivrmenu.php?flow_id='.$flow_id.'&wId='. $widget['nId'].'&tcx='.strtotime('now'));
			}
			
			break;
		} // end of case SMS
		
	case "Voicemail":{
	
		if ( $widget['content_type']  == 'Text' )
			
			$response->say($widget['content']);
							
		elseif ($widget['content_type']  == 'Audio' )	
		
			$response->play(ru.'media/audio/'.$flow_id.'/'.$widget['wId'].'/'.$widget['content'], array("loop" => "1"));
		else
			$response->say('Leave a message at the beep');
				
			$vmURL = "handle_message.php?exten=$flow_id";
			
			if ( $widget['nId'] != '0') $vmURL =$vmURL .'&wId='.$widget['nId'];
			
			$response->record(
				array(
					'action' => $vmURL,
					'maxLength' => '120',
					'playBeep' => 'true')
			);
		
		
		
		
		break;
	} // end of case Voicemail
	
	case "Hangup":{
		$response->hangup();
		break;
		} // end of case Hangup
		
		
	/**
	============================================================================================================
	=========================================== Survey Modules Start ===========================================
	============================================================================================================
	**/ 
		
endswitch;
	/* 
		$widget['wId'] = $rs_nxt['wId'];
		$widget['pId'] = $rs_nxt['pId'];
		$widget['flowtype'] = $rs_nxt['flowtype'];
		$widget['content_type'] = $rs_nxt['content_type'];
		$widget['content'] = $rs_nxt['content'];
		$widget['nId'] = $rs_nxt['nId'];
		$widget['data'] = $rs_nxt['data']
		$widget['keypress'] = $rs_nxt['keypress'];
	*/
print $response;


?>