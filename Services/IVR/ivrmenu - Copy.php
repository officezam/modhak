<?php

require_once("../../connect/connect.php");
//require_once("../connect/functii.php");
require_once('../Services/Twilio.php');	
require_once('function.php');	


function custom_variable($data){
	
	if($_REQUEST['Direction'] == 'outbound-api'){
		$sql_Contacts = "SELECT * FROM select_contacts WHERE phone = '".$_REQUEST['To']."'";
	}else{
		$sql_Contacts = "SELECT * FROM select_contacts WHERE phone = '".$_REQUEST['From']."'";
	}
	
	$res_Contacts = mysql_query($sql_Contacts);
	$row_Contacts = mysql_fetch_array($res_Contacts);
	 
	$data = str_replace('%FIRSTNAME%' , $row_Contacts['fname'] , $data);
	$data = str_replace('%LASTNAME%' , $row_Contacts['lname'] , $data);
	$data = str_replace('%EMAIL%' , $row_Contacts['email'] , $data);
	$data = str_replace('%PHONE%' , $row_Contacts['phone'] , $data);
	$data = str_replace('%CITY%' , $row_Contacts['city'] , $data);
	$data = str_replace('%STATE%' , $row_Contacts['state'] , $data);
	$data = str_replace('%ZIP%' , $row_Contacts['zip'] , $data);
	$data = str_replace('%COUNTRY%' , $row_Contacts['country'] , $data);
	$data = str_replace('%ADDRESS%' , $row_Contacts['address'] , $data);
	
	return $data;
	
}

header('Content-type: text/xml');


$response = new Services_Twilio_Twiml;

if ( isset ( $_REQUEST['campaignId']) && trim ($_REQUEST['campaignId'] )!= ''){	
	
	$campaignId = $_REQUEST['campaignId'];
	
	$query_ivr 	= "SELECT * FROM  `ivr_flow`  WHERE flow_id  ='".$campaignId."'";
	$exec_query	= mysql_query( $query_ivr );
	$rowIvr = mysql_fetch_array( $exec_query );
	$voice = $rowIvr['voice_speak'];
	$language = $rowIvr['voice_language'];
}


if ( $campaignId == FALSE ) {
	$response->reject( array( "reason"=>"busy" ) );
}


if( isset ( $_REQUEST['wId'] ) ) {	
	$getStarted  =	$_REQUEST['wId'];
}
else {
	$getStarted  = getStarted($campaignId); 
}

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


switch ( $widget['flowtype'] ) :

	case "Hangup":{
		$response->hangup();
		break;
		} // end of case Hangup
		
	case "Group":{			
		
		$call_type = $widget['content_type'];
		$groupId = $widget['content'];
		$LastId =$widget['data'];
		
		//// 
		
		$groupMember = array();
		$groupSQL = mysql_query("select memebers, nonmemeber from call_groups  where Id=$groupId ");
		if(@mysql_num_rows($groupSQL)>0)
		{
			$groupRow= @mysql_fetch_array($groupSQL );		
			
			$callGroupMemberId = $groupRow['memebers'];
			
			$callGroupNonMemberId = $groupRow['nonmemeber'];
			$gidx = 1;
			
			
			
			
			$SQL_Member= mysql_query("SELECT Id,firstname,lastname, phone,call_option,login_status FROM  members where Id IN ($callGroupMemberId) and  call_option!=0 and login_status=1 ");
			
			if(@mysql_num_rows($SQL_Member)>0)
			{
				while($rowMember = @mysql_fetch_array($SQL_Member))
				{
					$groupMember[$gidx]['name'] = $rowMember['firstname'].''.$rowMember['Id'] ;
					$groupMember[$gidx]['phone'] = $rowMember['phone'];
					$groupMember[$gidx]['call_option'] = $rowMember['call_options'];
					$groupMember[$gidx]['type'] = 'member';
					$gidx++;
				}
			}
				
			$SQL_NONMember= mysql_query("SELECT * FROM call_group_members where id IN ($callGroupNonMemberId) ");
			if(@mysql_num_rows($SQL_NONMember)>0)
			{
				while($rowNONMember = @mysql_fetch_array($SQL_NONMember))
				{
					$groupMember[$gidx]['name'] = $rowNONMember['Name'];
					$groupMember[$gidx]['phone'] = $rowNONMember['Number'];
					$groupMember[$gidx]['call_option'] = '';
					$groupMember[$gidx]['type'] = 'contact';
					$gidx++;
				}
			}
			
		}
						  
		if ( count($groupMember)> 0)
		{
			
			$nxtURL ='&campaignId='.$campaignId;
			if ( $widget['nId'] != '0' && $widget['nId'] != '' ){
				$nxtURL .='&wId='. $widget['nId'];
			}
				$dial_actions = array(
				'action' => 'record_call.php?DialCallTox='.urlencode($number).$nxtURL,
				'method' => 'POST',
				'record' => 'false'
			);
			
			if($RECORDINGS==false || $isCompanyRecordingDisabled)
				$dial_actions['record'] = 'false';

			if($recording_warning_url!="")
			{
				$response->play($recording_warning_url);
			}

			
			
			if ( $call_type =='all')
			{
				$dial = $response->dial(NULL,$dial_actions);
				
				$rsGroupMembers = mysql_query("SELECT * FROM `call_group_members` where call_group_id=$groupId order  by id ");
				foreach ($groupMember as $key => $member )					
				{	
					if ( $member['type'] == 'contact')
					{
						$numbertoDial =$member['phone'];								
						if (substr($numbertoDial, 0, 1) != "+") $numbertoDial='+'.trim($numbertoDial);
						$dial->number($numbertoDial,array());
					}else{
						
						if( $member['call_option'] =='3' ){
							$numbertoDial =$member['phone'];								
							if (substr($numbertoDial, 0, 1) != "+") $numbertoDial='+'.trim($numbertoDial);
							$dial->number($numbertoDial,array());
						
							$dial->client($member['name'],array());
							
							
						}elseif( $member['call_option'] =='2' ){
							$dial->client($member['name'],array());
						}else{
							$dial->client($member['name'],array());
						}
					}
					
				}
			}elseif ( $call_type =='ibr'){
				
				$dial = $response->dial(NULL,$dial_actions);
				
				$indiviual_but_random = array_rand ($groupMember ,1);
				$member	=$indiviual_but_random[0];
					
					if ( $member['type'] == 'contact')
					{
						$numbertoDial =$member['phone'];								
						if (substr($numbertoDial, 0, 1) != "+") $numbertoDial='+'.trim($numbertoDial);
						$dial->number($numbertoDial,array());
					}else{
						
						if( $member['call_option'] =='3' ){
							$numbertoDial =$member['phone'];								
							if (substr($numbertoDial, 0, 1) != "+") $numbertoDial='+'.trim($numbertoDial);
							$dial->number($numbertoDial,array());
						
							$dial->client($member['name'],array());
							
							
						}elseif( $member['call_option'] =='2' ){
							$dial->client($member['name'],array());
						}else{
							$dial->client($member['name'],array());
						}
					}
			}else{
				
					$LastId=$LastId+1;
					if ( !array_key_exists($LastId,$groupMember))  $LastId=1;
					
					$nxtURL ='&campaignId='.$campaignId;
					if ( $widget['nId'] != '0' && $widget['nId'] != '' ){
						$nxtURL .='&wId='. $widget['nId'];
					}
					
					$noofAttampts =0;
					if (isset ($_REQUEST['atmpt'] ) ) $atmpt = $_REQUEST['atmpt']; 
					
					$atmpt++;
					$lastAtmpt =$widget['wId'];
					if ( $atmpt == count($groupMember))
					{
						$lastAtmpt =0;
					}
					$nxtURL .='&lastAtmpt='. $lastAtmpt;
					$nxtURL .='&atmpt='. $atmpt;
					
						$dial_actions = array(
						'action' => 'record_call.php?DialCallTox='.urlencode($number).$nxtURL,
						'method' => 'POST',
						'record' => 'false'
					);
			
			
					$dial = $response->dial(NULL,$dial_actions);
					
					
					
					$wId = $widget['wId'];
					$res = mysql_query("update call_ivr_widget set  data='$LastId' WHERE wId = '$wId' ");
					
					$member	=$groupMember[$LastId];
					
					if ( $member['type'] == 'contact')
					{
						$numbertoDial =$member['phone'];								
						if (substr($numbertoDial, 0, 1) != "+") $numbertoDial='+'.trim($numbertoDial);
						$dial->number($numbertoDial,array());
					}else{
						
						if( $member['call_option'] =='3' ){
							$numbertoDial =$member['phone'];								
							if (substr($numbertoDial, 0, 1) != "+") $numbertoDial='+'.trim($numbertoDial);
							$dial->number($numbertoDial,array());
						
							$dial->client($member['name'],array());
							
							
						}elseif( $member['call_option'] =='2' ){
							$dial->client($member['name'],array());
						}else{
							$dial->client($member['name'],array());
						}
					}
				}
		
			}else{
				
					
					if ( $widget['nId'] != '0' && $widget['nId'] != '' ){
						
						$response->redirect('ivrmenu.php?campaignId='.$campaignId.'&wId='. $widget['nId'].'&tcx='.strtotime('now'));
					}else{
						$response->reject(array("reason"=>"busy"));
					}
			}
		
		break;
	} // end of case Group
	
	case "RoundRobin":{
		
		
				
			   $last_idx = $db->customExecute("SELECT `meta_data` FROM call_ivr_data WHERE  meta_key = 'last_idx'  and wId = ? GROUP BY id ASC");
				$last_idx->execute(array( $widget['wId']));
				$last_idx = $last_idx->fetch(PDO::FETCH_ASSOC);
				
				if ( !empty($last_idx) ) $last_idx =$last_idx['meta_data']; else $last_idx=NULL;
			
				
				
				
				
				$timeout= '30';
				  

				$numbers = $db->customExecute("SELECT `number` FROM call_ivr_round_robin WHERE wId = ? GROUP BY idx ASC");
				$numbers->execute(array( $widget['wId']));
				$numbers = $numbers->fetchAll(PDO::FETCH_OBJ);
				
			
				if($RECORDINGS==false || $isCompanyRecordingDisabled)
						  $URL_data =  '&rc=false';
					else
					  $URL_data =  '&rc=true';
				
				$URL_data .='&rr=1&campaignId='.$campaignId.'&wId='. $widget['wId'].'&to='.$timeout;
				if ( $widget['nId'] != '0' && $widget['nId'] != '' )
				{
					$URL_data .='&nextId='. $widget['nId'];
					
				}
				
				
				
					
				if(count($numbers)==0)
				{
					$response->reject(array("reason"=>"busy"));
				}else{
					
					if($last_idx==NULL)
					{
						$this_idx = 0;
						$number = $numbers[0];
						$number = $number->number;
					}else{
						$this_idx = $last_idx+1;
						$number = $numbers[$this_idx];
						if($number->number!=NULL){
							$number = $number->number;
						}else{
							$this_idx = 0;
							$number = $numbers[0];
							$number = $number->number;
						}
					}
					
					if($widget['content']=='0')
						$number = "+1".$number;
					else
						$number = "+".$number;
/*
					$prev_num = $db->getPreviousOutgoingNumbertoCall($_REQUEST['From']);

					if($prev_num != false && $_REQUEST['From']!="+266696687" && $_REQUEST['From']!="266696687")
						$number = $prev_num;*/

					if ($db->getVar("intl_dialtone")!="yes") {
						$dial_actions = array(
							'action' => 'record_call.php?DialCallTo='.urlencode($number). $URL_data,
							'method' => 'POST',
							'timeout' => $timeout,
							'record' => 'true'
							
						);
						

						if($RECORDINGS==false || $isCompanyRecordingDisabled)
							$dial_actions['record'] = 'false';

						if($recording_warning_url!="")
						{
							$response->play($recording_warning_url);
						}

						$dial = $response->dial($number,$dial_actions);
						
					}else{
						if($recording_warning_url!="")
						{
							$response->play($recording_warning_url);
						}
						$rand_id = md5(rand(0,2504920));
						$dial_actions = array(
							'action' => 'record_call.php?test=c2&DialCallTo='.urlencode($number). $URL_data,
							'timeout' => $timeout,
							'record' => 'true'
						);
						
						$dial = $response->dial(NULL,$dial_actions);
						$dial->conference($rand_id,array(
							"record"=>"true",
							"beep"=>"false",
							"endConferenceOnExit"=>"true",
							"maxParticipants"=>"1",
							"waitUrl"=>"intl_tone.php?op=1&T=".urlencode($_REQUEST['To'])."&F=".urlencode($_REQUEST['From'])."&company=".$campaignId."&DialCallTo=".urlencode($number)."&conf_id=".$rand_id
						));
					}
					
					$stmt = $db->customExecute("delete from call_ivr_data  WHERE wId = ?");
					$stmt->execute(array( $widget['wId']));
					
					$stmt = $db->customExecute("insert call_ivr_data SET meta_key = 'last_idx', meta_data = '$this_idx', wId = ?");
					$stmt->execute(array( $widget['wId']));
				
				}

				break;
			
	} // end of case RoundRobin
	
	case "MultiNumber":{
				$numbers = $db->customExecute("SELECT `number` FROM call_ivr_multiple_numbers WHERE wId = ? GROUP BY idx ASC");
				$numbers->execute(array($widget['wId']));
				$numbers = $numbers->fetchAll(PDO::FETCH_OBJ);
				if(count($numbers)==0)
				{
					$response->reject(array("reason"=>"busy"));
				}else{
					
					$nextURL ='';
					if ( $widget['nId'] != '0' && $widget['nId'] != '' ){
						$nxtURL ='&campaignId='.$campaignId.'&wId='. $widget['nId'];
					}
			
					$dial_actions = array(
						'action' => 'record_call.php?DialCallTo='.urlencode($number).$nxtURL,
						'method' => 'POST',
						'record' => 'true'
					);
				   

					if($RECORDINGS==false || $isCompanyRecordingDisabled)
						$dial_actions['record'] = 'false';

					if($recording_warning_url!="")
					{
						$response->play($recording_warning_url);
					}

					$dial = $response->dial(NULL,$dial_actions);

					

				   foreach($numbers as $number)
						{
							if($widget['content']=='0')
								$numbertoDial = "+1".$number->number;
							else
								$numbertoDial = "+".$number->number;
								
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
				$response->redirect('ivrmenu.php?campaignId='.$campaignId.'&wId='. $widget['nId'].'&tcx='.strtotime('now'));
			}
			
			break;
		} // end of case SMS
	
	case "Poll": {
		
		//getting the poll name
		$query_poll = "SELECT * FROM ivr_poll_management WHERE poll_id = ". $widget['content']."";
		$exe_qp		= mysql_query ( $query_poll );
		$poll_res	= mysql_fetch_array( $exe_qp );	
		
		//first system will speak the poll name 
		$response->say("Welcome to the ".$poll_res['poll_name']." Poll", array("voice" => $voice_speak, "language" => $voice_language)); 
		
		//next we will get the options of that specific poll and put them in say tag
		$query_poll_options = "SELECT * FROM poll_options WHERE poll_id = ". $widget['content']."";
		$exe_qp_opt			= mysql_query ( $query_poll_options );
		//$option_feedback 	= $_REQUEST['Digits'];
		
		if ( mysql_num_rows($exe_qp_opt) > 0 ) {
						
			$g =$response->gather( array ( 
				"action" 	=> 'handle_poll.php?poll_id='.$widget['content'].'&camp_id='.$campaignId.'&widget_id='.$widget['nId'], 
				"numDigits" =>  "1" )
			);
			
			
			$pollAudioPath = ru_dir.'media/poll/'.$poll_res['poll_audio'];
				
			if ($poll_res['poll_audio'] != '' && file_exists($pollAudioPath))
			{
				$g->play(ru.'media/poll/'.$poll_res['poll_audio'], array("loop" => "1"));
			}else{
				$g->say($poll_res['poll_text'], array("voice" => $voice_speak, "language" => $voice_language));
			
			}
			
			/*while(	$poll_opt_res = mysql_fetch_array( $exe_qp_opt ) ) :
				$g->say("Press ". $poll_opt_res['option_keyword']." for poll option ".$poll_opt_res['option_name']."");
			endwhile;	*/
							
		}
				
		if ( $widget['nId'] == '0') {
			$response->hangup();
		}
		else {
			$response->redirect('ivrmenu.php?campaignId='.$campaignId.'&wId='. $widget['nId'].'&tcx='.strtotime('now'));
		}
		
		break;
	} // end of case Poll

    case "Conference":{		
	
		$participants = array();
		//getting numbers from lists table		
		$query_gn  = "SELECT * FROM select_contacts";
		$query_gn .= " WHERE ";
		$query_gn .= " list_id = ". $widget['content'];		
		
		$exec_q_gn = mysql_query ( $query_gn );
		
		if ( mysql_num_rows($exec_q_gn) > 0 ) :
			while( $group_number 	= mysql_fetch_array( $exec_q_gn ) ){
				$participants[] = $group_number['phone'];
				}
		endif;
				
		foreach ($participants as $particpant) :
		
			//building parms array for call
			$dial_actions = array (
			
				'action' 	=> 'handle_conference.php?DialCallTo='.urlencode($number).$nextWidgetURL,
				'method' 	=> 'POST',
				'record' 	=> 'false'
			);			
			
			$dial = $response->dial($particpant,$dial_actions);

		endforeach;
		
		break;
	} // end of case Conference
	
	case "Transfer": {		
			
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
			$nextWidgetURL ='&campaignId='.$campaignId.'&wId='.$widget['nId'];
		else
			$nextWidgetURL ='&campaignId='.$campaignId;
		//initiating message 
		$response->say('please wait your call will be connecting ');
				
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
	} // end of case Transfer

	case "Greetings":{		
	
		if ( $widget['content_type']  == 'Text' ){
			
			$widget_content = custom_variable($widget['content']);
			$response->say($widget_content, array("voice" => $voice_speak, "language" => $voice_language));
		
		}elseif ($widget['content_type']  == 'Audio' )	
			$response->play(ru.'media/audio/'.$campaignId.'/'.$widget['wId'].'/'.$widget['content'], array("loop" => "1"));
		
		if ( $widget['nId'] == '0'){
			$response->hangup();
		}else{
			$response->redirect('ivrmenu.php?campaignId='.$campaignId.'&wId='. $widget['nId'].'&tcx='.strtotime('now'));
		}		
	
		break;
	} // end of case Greetings
		
	case "Voicemail":{
	
		if ( $widget['content_type']  == 'Text' )
			
			$response->say($widget['content']);
							
		elseif ($widget['content_type']  == 'Audio' )	
		
			$response->play(ru.'media/audio/'.$campaignId.'/'.$widget['wId'].'/'.$widget['content'], array("loop" => "1"));
		else
			$response->say('Leave a message at the beep');
				
			$vmURL = "handle_message.php?exten=$campaignId";
			
			if ( $widget['nId'] != '0') $vmURL =$vmURL .'&wId='.$widget['nId'];
			
			$response->record(
				array(
					'action' => $vmURL,
					'maxLength' => '120',
					'playBeep' => 'true')
			);
		
		
		
		
		break;
	} // end of case Voicemail
	
	case "Menu":{
				
		$rdir=0;
		if (isset ($_REQUEST['rdir'] ) ) {			
			$rdir = $_REQUEST['rdir'];
		}
		
		if ( strlen( $_REQUEST['Digits'] ) ) {
			
			$digits 	= $_REQUEST['Digits'];
			$ivr_detail = getMenuItems( $widget['wId'] );
			
			if ( array_key_exists( $digits,	$ivr_detail ) ) {
				
				$response->redirect('ivrmenu.php?campaignId='.$campaignId.'&wId='. $ivr_detail[$_REQUEST['Digits']]['wId'].'&kp='.$_REQUEST['Digits'] .'&tcx='.strtotime('now'));					
			}
			else{
												
				$response->say('please enter valid digit', array("voice" => $voice_speak, "language" => $voice_language));
				$response->pause(NULL, array ('length'=>'2'));
				$response->redirect('ivrmenu.php?campaignId='.$campaignId.'&wId='. $widget['wId'].'&rdir='. $rdir .'&tcx='.strtotime('now'));
			}
		}
		else{			
		
			$menu_repeat =  intval($widget['meta_data']);
			if ( $menu_repeat == 0) $menu_repeat =1;		
				$gather = $response->gather(array("numDigits" => "1"));
			
			if ( $widget['content_type']  == 'Text' )
				$gather->say($widget['content'], array("voice" => $voice_speak, "language" => $voice_language));
			
			elseif ($widget['content_type']  == 'Audio' )	
			
				$gather->play(ru.'media/audio/'.$campaignId.'/'.$widget['wId'].'/'.$widget['content'], array("loop" => "1"));
			
			
			if ( $rdir <  $menu_repeat ) {				
				$rdir++;
				$response->redirect('ivrmenu.php?campaignId='.$campaignId.'&wId='. $widget['wId'].'&rdir='. $rdir.'&tcx='.strtotime('now'));
			
			}
			elseif ( $widget['nId'] == '0'){			
				$response->hangup();			
			}
			else {
				$response->redirect('ivrmenu.php?campaignId='.$campaignId.'&wId='. $widget['nId'].'&tcx='.strtotime('now'));
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
			$nextWidgetURL ='&campaignId='.$campaignId.'&wId='.$widget['nId'];
		else
			$nextWidgetURL ='&campaignId='.$campaignId;
		//initiating message 
		$response->say('please wait your call will be connecting ');
				
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