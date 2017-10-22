<?php
$access_token = 'IHf9TGAiHOH3XZdKNdrz+NBHzcPr2y+f2rpdiDj7b2okT11aW2a7eknIfMCVkkIekN82nmiUonCyubOwPxCD0WN6ObtI8miTVkemgWQN8M27m8kCdxcbE6Q/rGRExajPhaWfpzyrO8xTyGyIrE/TGgdB04t89/1O/w1cDnyilFU=';


// Get POST body content
$content = file_get_contents('php://input');
// Parse JSON
$events = json_decode($content, true);
// Validate parsed JSON data
if (!is_null($events['events'])) {
	// Loop through each event
	foreach ($events['events'] as $event) {
		// Reply only when message sent is in 'text' format
		$userID = $event['source']['userId'];
		if ($event['type'] == 'message' && $event['message']['type'] == 'text') {
			// Get text sent			
			error_log('message '.$event['message']);
			$text = $event['message']['text'];
			//$text = 'hello world';
			// Get replyToken
			$replyToken = $event['replyToken'];
			error_log('replyToken'.$replyToken);
			// Build message to reply back
			// make GET
			$sessionID = session_id();
			error_log('session '.$sessionID);
			$postCXP = json_encode($para);
			$cxpUrl = 'http://58.82.133.74:8070/VoxeoCXP/DialogMapping?VSN=testService@System&message='.$text.'&vsDriver=164&channel=line&sessionID='.$userID;
			
			
			error_log($cxpUrl);
			$chcxp = curl_init($cxpUrl);
			
			curl_setopt($chcxp, CURLOPT_CUSTOMREQUEST, "GET");
			curl_setopt($chcxp, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($chcxp, CURLOPT_FOLLOWLOCATION, 1);
			$xcpResult = curl_exec($chcxp);
			curl_close($chcxp);
			error_log($xcpResult);	
			error_log('XXXX:'.substr($xcpResult,0,27).'');
			
			$messages = [
						'type' => 'text',
						'text' => $xcpResult
					];
			
			error_log('message : '.$messages);	
			// Make a POST Request to Messaging API to reply to sender
			 $url = 'https://api.line.me/v2/bot/message/reply';
			
			$data = [
				'replyToken' => $replyToken,
				'messages' => [$messages],
			];
			$post = json_encode($data);
			$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			$resultMes = curl_exec($ch);
			curl_close($ch);
			echo $resultMes . "\r\n";
		}
	}
}
echo "OK";
?>
