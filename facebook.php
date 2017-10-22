<?php

$access_token = "1847720705558410|6R91KDCTOHwRuo382JBcC0HMIHI";
$verify_token = "rich88";

$hub_verify_token = null;
error_log(isset($_REQUEST['hub_challenge']));
if (isset($_REQUEST['hub_challenge'])) {
    $challenge = $_REQUEST['hub_challenge'];
    $hub_verify_token = $_REQUEST['hub_verify_token'];
}

 error_log('challenge  : '. $challenge);
 error_log('hub_verify_token : '. $hub_verify_token);

if ($hub_verify_token === $verify_token) {
    echo $challenge;
}

?>
