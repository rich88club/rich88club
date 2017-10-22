<?php
 error_log('rich88 in site');
$access_token = "EAAaQfgBMh4oBAFVAJYszAJBI9X2xxUQ7oik2gEskrZA9iMpmlA3I4QvmxqZAjZCm3Q0TPV4y3EPzto3cIFR2OgLV5hr2xEEw5wsXZAeTeKxCxgEgwXsifjk09RNYfLyDbgheDAIdiQtcuADh3jhImDf21wDLXbpM96nevkBhCbEgDZBZC9cVZCxy8Kxl8RLthUZD";
$verify_token = "rich88";

$hub_verify_token = null;
if (isset($_REQUEST['hub_challenge'])) {
    error_log('match request');
    $challenge = $_REQUEST['hub_challenge'];
    $hub_verify_token = $_REQUEST['hub_verify_token'];
}
echo $challenge;


?>
