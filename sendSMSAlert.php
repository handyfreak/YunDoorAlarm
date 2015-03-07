<?php 

define ("SKEBBY_GATEWAY", "http://gateway.skebby.it/api/send/smsskebby/advanced/http.php");

define ("SMS_TYPE_0CENT", "0cent");
define ("SMS_TYPE_CLASSIC", "classic");
define ("SMS_TYPE_CLASSIC_PLUS", "classic_plus");
define ("SMS_TYPE_BASIC", "basic");

// insert your real account (username & password), the recipients, the message body and choose one of the SMS types above
// NB: phone numbers must start with the international prefix (39 for Italy), without + or 00
echo sendSMS("skebby_user", "skebby_password", "391234567890", "391234567890", "Warning: door opened!", "0cent") . "\n";

	
function sendSMS($username, $password, $sender, $recipients, $text, $type) {

	// check if $recipients is an array, if not change it into an array
	if (!is_array($recipients)) $recipients = array($recipients);
	
	// set the correct $metod based on the SMS type
	switch($type) {

		case SMS_TYPE_0CENT:
			$method='send_sms'; break;
		case SMS_TYPE_CLASSIC: 
			$method='send_sms_classic'; break;
		case SMS_TYPE_CLASSIC_PLUS:
			$method='send_sms_classic_report'; break;
		case SMS_TYPE_BASIC:
			$method='send_sms_basic'; break;
	}
	
	// set the POST parameters
	$parameters = 'method=' . urlencode($method) . '&'
		.'username=' . urlencode($username) . '&'
		.'password=' . urlencode($password) . '&'
		.'text=' . urlencode($text) . '&'
		.'recipients[]=' . implode('&recipients[]=', $recipients);
	
	// add the sender number if any
	$parameters .= $sender != '' ? '&sender_number='.urlencode($sender) : '';

	// make the call
	return doPostRequest($parameters);
}

function doPostRequest($parameters) {
	
	// inizialize the cURL library
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT, 10);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch,CURLOPT_TIMEOUT, 60);
	curl_setopt($ch,CURLOPT_USERAGENT, 'ArduinoPHP');
	
	// set gateway URL and parameters
	curl_setopt($ch,CURLOPT_URL, SKEBBY_GATEWAY);
	curl_setopt($ch,CURLOPT_POSTFIELDS, $parameters);
	
	// make the request and return the response
	$response = curl_exec($ch);
	curl_close($ch);
	return $response;
}

?>