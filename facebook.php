<?php
 error_log('rich88 in site');
$access_token = "EAAaQfgBMh4oBAJVqlzweTgah4ZCxEq8szdrEcZAtqveL0sZAZCEjngaGHcy2rhdnHrtgNdgjZAZAGvaqebvqy2PG53GmH5nChLWn6KBuZC94TqVMJeRZArdN5uRw2ULZA8uZAZAQqmGZBe8zNjbgnqxktnQTXgK3cwJ91HdkNqSZCutQFJcUJewZACdtyD6FHi8z3saAKHf4PTun45EwZDZD";
$verify_token = "rich88";

$hub_verify_token = null;
if (isset($_REQUEST['hub_challenge'])) {
    error_log('match request');
    $challenge = $_REQUEST['hub_challenge'];
    $hub_verify_token = $_REQUEST['hub_verify_token'];
}
echo $challenge;


?>
