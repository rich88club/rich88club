<?php
error_log('facebook hook ');
 
   $access_token = 'EAASvNkXVo7wBAAZCAZBU4dJBXMCWnoCFCx9sgpLMVZAnUMb3JbqlYehI2bu2rblrSPVurMZCYgqb4mIdHCfVZAz6jcE3aAhQPUjU8CqFiwSJbRZC6SlkZB5WPprBhbwrc5Q2bAE7Az9P1ukzAAYnvBZCWlZCENEd1N7ZAXhC43aZCtiKAZDZD';
   $verify_token = 'cxp_poc';
   $hub_verify_token = null;
 
   if(isset($_REQUEST['hub_challenge'])) {
     	$challenge = $_REQUEST['hub_challenge'];
    	$hub_verify_token = $_REQUEST['hub_verify_token'];
   }
 
  error_log('hub_verify_token  '. $hub_verify_token );
   if ($hub_verify_token === $verify_token) {
     	error_log('challenge '.$challenge);
   }
 
   $input = json_decode(file_get_contents('php://input'), true);
  error_log('input  '. $input );
   $sender = $input['entry'][0]['messaging'][0]['sender']['id'];
  error_log('sender  '. $sender );
   $message = $input['entry'][0]['messaging'][0]['message']['text'];
  error_log('XXXXXmessage :  '. $message );
 	if($message==''){
 		return;
 	}
 $cxpUrl = 'http://58.82.133.74:8070/VoxeoCXP/DialogMapping?VSN=testService@System&message='.$message.'&vsDriver=164&channel=facebook&sessionID=EAASvNkXVo7wBAAZCAZBU4dJBXMCWnoCF';
 					
 	error_log($cxpUrl);
 	$chcxp = curl_init($cxpUrl);
 
 	curl_setopt($chcxp, CURLOPT_CUSTOMREQUEST, "GET");
 	curl_setopt($chcxp, CURLOPT_RETURNTRANSFER, true);
 	curl_setopt($chcxp, CURLOPT_FOLLOWLOCATION, 1);
 	$xcpResult = curl_exec($chcxp);
 	curl_close($chcxp);
 	error_log('cxp '.$chcxp);
 
 	//API Url
 	$url = 'https://graph.facebook.com/v2.6/me/messages';
 	$message_to_reply = $xcpResult;
 	error_log('url reply'.$url);
 	//Initiate cURL.
 	$ch = curl_init($url);
	
			$messages = '';
			

			$result = explode("\n",$message_to_reply);

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

			$messages=[
  				'attachment' =>['type' => 'template',
 						'payload' => ['template_type' => 'generic',
 							      	'elements' => [
 										 [
 											'title' => $title,
											'image_url'=> $imageURL,
 											'subtitle' => $subTitle,
 											'buttons' => [
 												['type' => 'web_url',
 												'title' => $titleButton,
 												'url' => $webURL
 												]
 											]
 										]
  									]
 							      
 							      ]
  						]
  				];

			}elseif (($checkTitle !== false) && ($checkSubtitle !== false) && ($checkTitleBN !== false) && ($checkWebURL !== false)) {

			    error_log("Template not have image");
				$messages=[
  				'attachment' =>['type' => 'template',
 						'payload' => ['template_type' => 'generic',
 							      	'elements' => [
 										 [
 											'title' => $title,
 											'subtitle' => $subTitle,
 											'buttons' => [
 												['type' => 'web_url',
 												'title' => $titleButton,
 												'url' => $webURL
 												]
 											]
 										]
  									]
 							      
 							      ]
  						]
  				];

			}elseif (($checkImageURL !== false) && ($checkTitle !== false) && ($checkSubtitle !== false)) {
			    error_log("Template not have button");
			$messages=[
  				'attachment' =>['type' => 'template',
 						'payload' => ['template_type' => 'generic',
 							      	'elements' => [
 										 [
 											'title' => $title,
											'image_url'=> $imageURL,
 											'subtitle' => $subTitle
 											
 										]
  									]
 							      
 							      ]
  						]
  				];


			}elseif (($checkImageURL !== false) && ($checkSubtitle !== false)) {
			    error_log("Template not have title");
				$messages=[
  				'attachment' =>['type' => 'template',
 						'payload' => ['template_type' => 'generic',
 							      	'elements' => [
 										 [
											'image_url'=> $imageURL,
 											'subtitle' => $subTitle,
 											
 										]
  									]
 							      
 							      ]
  						]
  				];


			}elseif (($checkImageURL !== false) && ($checkTitle !== false)) {
			    error_log("Template not have subtitle and button");
				$messages=[
  				'attachment' =>['type' => 'template',
 						'payload' => ['template_type' => 'generic',
 							      	'elements' => [
 										 [
 											'title' => $title,
											'image_url'=> $imageURL
 											
 										]
  									]
 							      
 							      ]
  						]
  				];


			}elseif (($checkImageURL !== false)) {
			    error_log("Send image only");
				$messages=[
  				'attachment' =>['type' => 'template',
 						'payload' => ['template_type' => 'generic',
 							      	'elements' => [
 										 [
 										
											'image_url'=> $imageURL
 											
 										
 										]
  									]
 							      
 							      ]
  						]
  				];


			}elseif (($checkMessOnly !== false)) {
			    error_log("Send message only");
					$messages = [
						'text' => $messageDir
					];

			}else{

				error_log("Not have condition fix.");
				$messages = [
						'text' => $messageDir
					];
			}

 
 //The JSON data.
 	$jsonData = [
 	    'access_token'=>$access_token,
 	    'recipient'=>[
 		'id'=> $sender
 	      ],
 	    'message'=> $messages
 		    
 	];
 
 	//Encode the array into JSON.
 	$jsonDataEncoded = json_encode($jsonData);
 	//Tell cURL that we want to send a POST request.
 	curl_setopt($ch, CURLOPT_POST, 1);
 	//Attach our encoded JSON string to the POST fields.
 	curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
 	//Set the content type to application/json
 	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
 
 	//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
 	//Execute the request
 	if(!empty($input['entry'][0]['messaging'][0]['message'])){
 	    $resultMes = curl_exec($ch);
	 }
 ?>
