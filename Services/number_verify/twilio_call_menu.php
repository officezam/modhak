<?php
include ("../../connect/connect.php");
require('../../Services/Twilio.php');

	$to_phone   = $_REQUEST['To'];
	$from_phone = $_REQUEST['From'];
	$CallSid    = $_REQUEST['CallSid'];
	$camp_id  	= $_REQUEST['camp_id'];
	
	if(isset($_REQUEST['rdir']) && $_REQUEST['rdir'] == ''){
		$rdir = 1;
	}
	
	$qry_temp     = "SELECT * FROM number_verify_template WHERE template_id = '".$camp_id."'";
	$exe_temp     = $dbo->do_query($qry_temp);
	$data_temp    = $dbo->get_row_array($exe_temp);
	
	$answer_text  = $data_temp['voice_text'];
	$answer_mp3   = $data_temp['audio_mp3'];
	$redirect_number    = $data_temp['redirect_number'];
	
	header('Content-type: text/xml');
	
	$response = new Services_Twilio_Twiml;
	
	if ( strlen( $_REQUEST['Digits'] ) ) {
		
		$digits 	= $_REQUEST['Digits'];
		
		if ( $digits == 1 ) {
			
			//record_call.php?DialCallTo='.urlencode( $number ). $nextWidgetURL
			$number = $redirect_number; //'+12149894300';
			$dial_actions = array (
							//'action'	=> '',
							//'method' 	=> 'POST',
							'timeout'	=>	15,
							'record' 	=> 'false'
							);			
			$dial = $response->dial( $number, $dial_actions );
			
		}elseif( $digits == 2 ){
			$response->say("Thanks! Good Bye", array("voice" => "woman"));
		}else{
			if($rdir < 3){
				$rdir++;
				$response->redirect('twilio_call_menu.php?camp_id='.$camp_id.'&rdir='. $rdir .'&tcx='.strtotime('now'));
			}else{
				$response->say("You have pressed invalid digit", array("voice" => "woman"));
				$response->hangup();
			}
		}
		
	}else{
		
		$audioPath = ru_dir.'media/audio/'.$answer_mp3;
		if(($answer_text != '') || ($answer_mp3 != '' && file_exists($audioPath)))
		{	
			if ($answer_mp3 != '' && file_exists($audioPath)){ 
				
				$gather = $response->gather(array("numDigits" => "1"));
				$gather->play(ru.'media/audio/'.$answer_mp3, array("loop" => "3"));
				
				/*$response->pause(array("length" => "2"));
				$gather->say('Press 1 for forword call, Press 2 for hangup call', array("voice" => "woman"));*/
				
			}else{ 
				$gather = $response->gather(array("numDigits" => "1"));
				$gather->say($answer_text, array("voice" => "woman", "loop" => "3"));
			}
			
		}else { 
			$response->say("Sorry, Contact with admin.", array("voice" => "woman"));
		}
	
	}
	

print $response;