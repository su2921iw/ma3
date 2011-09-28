<?php

// define include path for zend lib
$path = $_SERVER['DOCUMENT_ROOT'] ."/../";
set_include_path($path);

// require the zend mail lib for sending email
require_once('../../Zend/Mail.php');

// the allowed referer
define("ORDER_FORM_URL", "https://www.maskitalia.com/secure/orderform.php");



// address of business to send orders to
define("EMAIL_TO_ADDR", "maskitalia@gmail.com");

// name of business to send orders to
define("EMAIL_TO_NAME", "Mask Italia");

// subject of new order message sent to business
define("EMAIL_ORDER_SUBJECT", "New Order");



// address of business sender of confirmation emails
define("EMAIL_FROM_ADDR", "maskitalia@gmail.com");

// name of business sender of confirmation emails
define("EMAIL_FROM_NAME", "Mask Italia");

// subject of confirmation message sent to user
define("EMAIL_CONFIRMATION_SUBJECT", "Mask Italia Order Acknowledgement");

// body of confirmation message sent to user
define("EMAIL_CONFIRMATION_BODY", "Thank you for ordering from Mask Italia.  Orders are normally processed within 24 hours.  Should you need confirmation more urgently, please reply to this message with your request.");



// address to redirect to on success
define("ORDER_CONFIRMATION_URL","https://www.maskitalia.com/secure/success.php");

// bucket for the form data
$data = array();

// bucket for error msgs
$errors = array();

// define post or send 500 code
if(isset($_POST)){
	$post = $_POST;
}else{
	sendResponseCode(500);
}

// send any response code
function sendResponseCode($code){
	header('HTTP', true, $code);			
}

// format data as html get params for use when redirecting back to the form, the form can use the params to prefill the form with data the user has already entered
function getDataAsParams()
{
	global $post;
	$params = '';
	$count = count($post);
	if($count){
		$params .= '?';
		$i = 1;
		foreach($post as $k => $d){
			if(!preg_match('/(Number)/', $k)){
				$params .= urlencode($k) . '=' . urlencode($d);
				if($i != $count){
					$params .= '&';
				}
			}
			$i++;
		}
		
	}
	return $params;
}

// redirect back to the referring page or send a 500 code if no referrer
function redirectBack() 
{
	if(isset($_SERVER['HTTP_REFERER'])){
		$referer = parse_url($_SERVER['HTTP_REFERER']);
		$referer = $referer['scheme'] . "://" . $referer['host'] . $referer['path'];
		$params = getDataAsParams();
		$request = $referer . $params;
		header("Location: $request");

	}else{
		sendResponseCode(500);			
	}
}
// redirect to the success page if the page is called from the order form page, otherwise send a 500 code
function redirectSuccess(){
	$success = ORDER_CONFIRMATION_URL;
	if(isset($_SERVER['HTTP_REFERER'])){
		$referer = parse_url($_SERVER['HTTP_REFERER']);
		$referer = $referer['scheme'] . "://" . $referer['host'] . $referer['path'];

		if($referer==ORDER_FORM_URL){
			header("Location: $success");			
		}else{
			sendResponseCode(500);		
		}
	}
	
}

// format the form post as one form variable per line
function getDataAsMsgBody(){
	global $data;
	$name = getName();
	$date = date("D, M j, Y G:i:s T");
	$body = "New Order from $name: \n";
	$body .= $date . "\n";
	foreach($data as $k => $v){
		if($v != '' && $k != ''){
			$body .= $k . ': ';
			$body .= $v . "\n";			
		}
	}
	return $body;
}

// return the name from the form post
function getName(){
	global $data;
	return $data['fName'];
}

// return the email address from the form post
function getEmail(){
	global $data;
	return $data['Email'];
}

// send the order to the business email address, after that call the sendConfirmation function
function sendOrder(){
	$mail = new Zend_Mail();
	$body = getDataAsMsgBody();
	$name = getName();
	$toAddr = getEmail();
	$mail->setFrom($toAddr, $name);
	$mail->addTo(EMAIL_TO_ADDR, EMAIL_TO_NAME);
	$mail->setBodyText($body);
	$mail->setSubject(EMAIL_ORDER_SUBJECT);
	$mail->send();
	unset($mail);
	sendConfirmation();
}

// send a confirmation email using the zend mail lib, after that redirect to the success page
function sendConfirmation(){
	$mail = new Zend_Mail();
	$body = EMAIL_CONFIRMATION_BODY;
	$name = getName();
	$toAddr = getEmail();
	$mail->setFrom(EMAIL_FROM_ADDR, EMAIL_FROM_NAME);
	$mail->addTo($toAddr, $name);
	$mail->setBodyText($body);
	$mail->setSubject(EMAIL_CONFIRMATION_SUBJECT);
	$mail->send();
	redirectSuccess();
}
// validate name is not empty and has first and last
if(isset($post['fName']) && $post['fName'] != ''){
	if(!preg_match('/ /', $post['fName'])){
		$errors['fName'] = 'Please enter a first and last name';
	}else{
		$data['fName'] = $post['fName'];
	}
}else{
	$errors['fName'] = 'Name cannot be empty';
}

// validate address is not empty and has more than one line
if(isset($post['Address']) && $post['Address'] != ''){
	$data['Address'] = $post['Address'];
	
}else{
	$errors['Address'] = 'Shipping address cannot be empty';
}

// add billing address
if(isset($post['Billing']) && $post['Billing'] != ''){
	$data['Billing'] = $post['Billing'];
}

// validate phone number is 10 digits if set
if(isset($post['Phone']) && $post['Phone'] != ''){
	$phone = preg_replace('/\D/', '', $post['Phone']);
	$length = strlen($phone);
	if($length < 10 || $length > 11){
		$errors['Phone'] = 'Please enter a valid 10 digit phone number';
	}else{
		$data['Phone'] = $phone;
	}
}

// validate fax number is 10 digits if set
if(isset($post['Fax']) && $post['Fax'] != ''){
	$fax = preg_replace('/\D/', '', $post['Fax']);
	$length = strlen($fax);
	if($length < 10 || $length > 11){
		$errors['Fax'] = 'Please enter a valid 10 digit fax number';
	}else{
		$data['Fax'] = $fax;
	}
}

// validate email address not empty and valid
if(isset($post['Email']) && $post['Email'] != ''){
	$qtext = '[^\\x0d\\x22\\x5c\\x80-\\xff]';
	$dtext = '[^\\x0d\\x5b-\\x5d\\x80-\\xff]';
	$atom = '[^\\x00-\\x20\\x22\\x28\\x29\\x2c\\x2e\\x3a-\\x3c'.
		'\\x3e\\x40\\x5b-\\x5d\\x7f-\\xff]+';
	$quoted_pair = '\\x5c[\\x00-\\x7f]';
	$domain_literal = "\\x5b($dtext|$quoted_pair)*\\x5d";
	$quoted_string = "\\x22($qtext|$quoted_pair)*\\x22";
	$domain_ref = $atom;
	$sub_domain = "($domain_ref|$domain_literal)";
	$word = "($atom|$quoted_string)";
	$domain = "$sub_domain(\\x2e$sub_domain)*";
	$local_part = "$word(\\x2e$word)*";
	$addr_spec = "$local_part\\x40$domain";
	
	if(!preg_match("!^$addr_spec$!",$post['Email'])){
		$errors['Email'] = 'Please enter a valid email address';
	}else{
		$data['Email'] = $post['Email'];
	}
}else{
	$errors['Email'] = 'Email address cannot be empty';
}

// validate order is not empty
if(isset($post['Order']) && $post['Order'] != ''){
	$data['Order'] = $post['Order'];
}else{
	$errors['Order'] = 'Order cannot be empty';
}

// validate shipping is not empty
$require_comments = false;
if(isset($post['Shipping']) && $post['Shipping'] != ''){
	$data['Shipping'] = $post['Shipping'];
	if($post['Shipping'] == 'Other'){
		$require_comments = true;
	}
}else{
	$errors['Shipping'] = 'Shipping cannot be empty';
}

// validate comments are set if shipping is 'other'
if($require_comments){
	if(isset($post['Comments']) && $post['Comments'] != ''){
		$data['Comments'] = $post['Comments'];
	}else{
		$errors['Comments'] = 'Please select a shipping option or indicate shipping information in comments';
	}	
}


// validate card not empty
$require_card = false;
if(isset($post['Card']) && $post['Card'] != ''){
	if($post['Card'] != 'Check'){
		$require_card = true;
	}
//	if($post['Card'] != 'PayPal'){
//		$require_card = true;
//	}
	
	$data['Card'] = $post['Card'];
}else{
	$errors['Card'] = 'Payment type cannot be empty';
}

// validate card info is set is card is not 'check'
if($require_card){
	
	//validate card number not empty and is credit card number
	if(isset($post['Number']) && $post['Number'] != ''){
		$value = $post['Number'];
		$flag = false;			
		$value = preg_replace('/\D/', '', $value);
	       $length = strlen($value);
	       if ($length < 13 || $length > 19) {
			$flag = true;
	       }
		if(!$flag){
	        $sum    = 0;
	        $weight = 2;

	        for ($i = $length - 2; $i >= 0; $i--) {
	            $digit = $weight * $value[$i];
	            $sum += floor($digit / 10) + $digit % 10;
	            $weight = $weight % 2 + 1;
	        }

			$rlength = $length -1;
	        if (((10 - $sum % 10) % 10 != $value[$rlength])) {
				$flag = true;
	        }				
		}
		if($flag){
			$errors['Number'] = 'Please enter a valid credit card number';
		}else{
			$data['Number'] = $post['Number'];
		}
	}else{
		$errors['Number'] = 'Credit card number cannot be empty';
	}

	// validate month is not empty and 2 digits
	if(isset($post['Month']) && $post['Month'] != ''){
		$value = $post['Month'];
		$value = preg_replace('/\D/','', $value);
		$length = strlen($value);
		$flag = false;
		if(!preg_match('/\d{2}/', $value)){
			$flag = true;
		}
		if(!($value >= 0) || !($value <= 12)){
			$flag = true;
		}
		if($flag){
			$exp_error = true;
			$errors['Month'] = 'Expiration month must be a two digit number between 01 and 12';
		}else{
			$data['ExpirationMonth'] = $post['Month'];
		}	
	}else{
		$exp_error = true;
		$errors['Month'] = 'Expiration month cannot be empty';
	}

	// validate year is not empty and 4 digits
	if(isset($post['Year']) && $post['Year'] != ''){
		$value = $post['Year'];
		$value = preg_replace('/\D/','', $value);
		$last_year = (int) date('Y') - 1;
		if( (!preg_match('/\d{4}/',$value)) || (!($value >= $last_year))){
			$exp_error = true;
			$errors['Year'] = "Expiration year must be a four digit number between greater than $last_year";
		}else{
			$data['ExpirationYear'] = $post['Year'];
		}
	}else{
		$exp_error = true;
		$errors['Year'] = 'Expiration year cannot be empty';
	}
	
	// validate expiration has not passed
	if(!isset($exp_error)){
		$month = $post['Month'];
		$year = $post['Year'];
		if($year == (int) date('Y')){
			if ($month < (int) date('m')){
				$errors['Month'] = "The expiration date of $month/$year has past";
				unset($errors['Year']);
			}
		}
	}
	
	//validate cvn is not empty and is 3 or 4 digit number
	if(isset($post['CVN']) && $post['CVN'] != ''){
		$value = $post['CVN'];
		$value = preg_replace('/\D/','', $value);
		$length = strlen($value);
		if(!preg_match('/\d{3,4}/', $value)){
			$errors['CVN'] = "The cvn number must be 3 or 4 digits";
		}else{
			$data['CVN'] = $post['CVN'];
		}
	}else{
		$errors['CVN'] = "CVN cannot be empty";
	}
	
}

// if there are errors from validation then store them in the session and redirect back to order form
if(count($errors)){
	if(!isset($_SESSION)){
		session_name('maskitalia');
		ini_set("session.gc_maxlifetime", "18000"); 
		session_start();
	}
	
	// store errors in session
	$_SESSION['errors'] = $errors;

	// redirect to order form
	redirectBack();

}else{
	// process the order
	if(isset($_SESSION['errors'])){
		unset($_SESSION['errors']);		
	}
	sendOrder();
	
}

?>