<?php
 error_log('rich88 in site');
$access_token = "EAAaQfgBMh4oBAFVAJYszAJBI9X2xxUQ7oik2gEskrZA9iMpmlA3I4QvmxqZAjZCm3Q0TPV4y3EPzto3cIFR2OgLV5hr2xEEw5wsXZAeTeKxCxgEgwXsifjk09RNYfLyDbgheDAIdiQtcuADh3jhImDf21wDLXbpM96nevkBhCbEgDZBZC9cVZCxy8Kxl8RLthUZD";
$verify_token = "rich88";

$hub_verify_token = null;
error_log(isset($_REQUEST['hub_challenge']));
if (isset($_REQUEST['hub_challenge'])) {
    $challenge = $_REQUEST['hub_challenge'];
    $hub_verify_token = $_REQUEST['hub_verify_token'];
}
echo $challenge;
echo $hub_verify_token;
 error_log('challenge  : '. $challenge);
 error_log('hub_verify_token : '. $hub_verify_token);

if ($hub_verify_token === $verify_token) {
    echo $challenge;
}

?>
