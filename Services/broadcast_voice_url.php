<?php
include_once('../connect/connect.php');
	// this line loads the library 
require_once(ru_dir.'Services/Twilio.php');

 
  $sv_id = $_GET['p'];



	///// Get Selected template id For Audio /////
	$templateQry  = "select * from sms_template where msg_id = (SELECT template_id FROM selected_template WHERE compaign_id = '$sv_id' AND type = 'voice')";
	
  $exetemplateQry = $dbo->do_query($templateQry);
  $num_rows 	  = $dbo->num_rows($exetemplateQry);
  
  if($num_rows)
  {
	  $templateArray = $dbo->get_row_array($exetemplateQry);
	  
	  $Text_speech 	 = $templateArray['msg'];
	  $audioPath	 = $templateArray['audio'];
	  
	  $path_explode  = explode("/" ,$audioPath);
	  $audio 	 	 = end($path_explode);
	  
	  $audioFile 	 = ru_dir . 'media/audio/' . $audio;
		
		
		
	  
  }else{ 
  
		  ////// Get Audio file Or Text For Speech //////
		  $textQry	   = "select * from sms_voice_broadcast where sv_id = '$sv_id' ";
		  $exeqry      = $dbo->do_query($textQry);
		  $textArray   = $dbo->get_row_array($exeqry);
		 
		  $Text_speech = $textArray['greeting_txt'];
		  $audio 	   = $textArray['greeting_mp3'];
		  
		  $audioPath   = ru_audio.$audio;
		  $audioFile = ru_dir . 'media/audio/' . $audio;
		}


header('Content-type: text/xml');
echo '<?xml version="1.0" encoding="UTF-8"?>';

?><Response>
		<?php 
        	if ($audio != '' && file_exists($audioFile))
       		 { 	
			 ?>
        		<Play loop="1"><?php echo $audioPath; ?></Play>
            	<Hangup/>
			<?php 
            }
            else
            { 
            ?>
              <Say voice="woman" language="en"><?php echo $Text_speech;?></Say>
              <Hangup/>
		<?php 
        } 
        ?>				
</Response>