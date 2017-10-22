<?php
$access_token = 'V2YxcaVlPFwhyPDYtLD5fGRnCsolCEEFtIvCWDS1XUK5iYtIbaq5jfrqFB9GSZ95OnsfD1F9uXWru0rw9dOH4x0j4yCiVimguAaHKXCwdkUjDH82CJ5RLjlG1Nu+3+UnnK0yBTyNBHI/0UmHNZKL6gdB04t89/1O/w1cDnyilFU=';


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
			//error_log(':'.substr($xcpResult,0,6).':');
			error_log('XXXX:'.substr($xcpResult,0,27).'');
			$messages = '';
			
			$result = explode("\n",$xcpResult);
			$symResult = "";
			foreach ($result as $value) {
			    $symResult .= substr($value, 0, 1);
			}
			$imageURL = "";
			$title = "";
			$subTitle = "";
			$titleButton = "";
			$webURL = "";
			$messages = "";
			for($i = 0; $i < count($result) ; $i++){
				if(substr($result[$i],0,1) == "!"){
					$imageURL  = trim($result[$i],"!");
					 error_log($imageURL);
				}elseif (substr($result[$i],0,1) == "["){
					$title  = trim($result[$i],"[");
					error_log($title);
				}elseif (substr($result[$i],0,1) == "{"){
					$subTitle   = trim($result[$i],"{");
					error_log($subTitle);
				}elseif (substr($result[$i],0,1) == "*"){
					$titleButton   = trim($result[$i],"*");
					error_log($titleButton);
				}elseif (substr($result[$i],0,1) == "#"){
					$webURL    = trim($result[$i],"#");
					error_log($webURL);
				}else{
					error_log("Not have condition fix2.");
					$messageDir = implode("\n", $result);
				}
			}
			$symImageURL = "!";
			$symTitle = "[";
			$symSubtitle = "{";
			$symTitleBN = "*";
			$symWebURL = "#";
			$symMessOnly = "(";
			$checkImageURL = strpos($symResult, $symImageURL);
			$checkTitle = strpos($symResult, $symTitle);
			$checkSubtitle = strpos($symResult, $symSubtitle);
			$checkTitleBN = strpos($symResult, $symTitleBN);
			$checkWebURL = strpos($symResult, $symWebURL);
			$checkMessOnly = strpos($symResult, $symMessOnly);
			
			if (($checkImageURL !== false) && ($checkTitle !== false) && ($checkSubtitle !== false) && ($checkTitleBN !== false) && ($checkWebURL !== false)) {
			    error_log("Template have all");
				$messages=['type'=> 'template',
								'altText' => 'this is a buttons template',
								'template' => [
									'type'=> 'buttons',
									'thumbnailImageUrl'=> $imageURL,
									'title' => $title,
									'text' => $subTitle,
									'actions' => [
											 ['type' => 'uri',
												'label' => $titleButton,
												'uri' => $webURL
											  ]
										     ]
										]
								];
				
			}elseif (($checkImageURL !== false) && ($checkTitle !== false) && ($checkTitleBN !== false) && ($checkWebURL !== false)) {
			    error_log("Template not have title");
				$messages=['type'=> 'template',
								'altText' => 'this is a buttons template',
								'template' => [
									'type'=> 'buttons',
									'thumbnailImageUrl'=> $imageURL,
									'title' => 'RVP',
									'text' => $title,
									'actions' => [
											 ['type' => 'uri',
												'label' => $titleButton,
												'uri' => $webURL
											  ]
										     ]
										]
								];
				
			}elseif (($checkTitle !== false) && ($checkTitleBN !== false) && ($checkWebURL !== false)) {
			    error_log("Template not have image ");
				$messages=['type'=> 'template',
								'altText' => 'this is a buttons template',
								'template' => [
									'type'=> 'buttons',
									'text' => $title,
									'actions' => [
											 ['type' => 'uri',
												'label' => $titleButton,
												'uri' => $webURL
											  ]
										     ]
										]
								];

			
			}elseif (($checkImageURL !== false)) {
			    error_log("Send image only");
				$messages=['type'=> 'template',
								'altText' => 'this is a buttons template',
								'template' => [
									'type'=> 'buttons',
									'thumbnailImageUrl'=> $imageURL
										]
								];
			}elseif (($checkMessOnly !== false)) {
			    error_log("Send message only");
					$messages = [
						'type' => 'text',
						'text' => $messageDir
					];
			}else{
				error_log("Not have condition fix.");
				$messages = [
						'type' => 'text',
						'text' => $messageDir
					];
			}
			
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
