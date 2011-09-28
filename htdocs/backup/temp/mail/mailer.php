<?php
// STANDALONE OPTION
$standalone = FALSE;

// Path to your CSS file
$path_to_css = 'mailer.css';

// LANGUAGE SETTING 
$language = 'lang/English.php';

// FULL URL TO SCRIPT
$script_path = 'http://maskitalia.nicolasblock.com/mail/mailer.php';

// FULL URL TO CONTACT PAGE
// If you are running this script in standalone mode, leave this blank. Otherwise,
// enter the full URL to the page that is displaying the form

$path_contact_page = 'http://maskitalia.nicolasblock.com/newmail.php';

// RECIPIENT DATA
// If you are just sending email to a single address, enter it here. For more advanced
// usage such as multiple recipients, CC, BCC, etc.. please see the web page for instructions

$recipients = 'nicolasblock@gmail.com';

// FORM STRUCTURE
// This is used to generate the form. Each form element must be on its own line.
// Detailed usage instructions can be found on the web page

$form_struct = '
	type=text|class=fmtext|label=Name|fieldname=fm_name|max=100|req=true
	type=text|class=fmtext|label=Email|fieldname=fm_email|max=100|req=true|ver=email
	type=text|class=fmtext|label=Subject|fieldname=fm_subject|max=100|req=true
	type=verify|class=fmverify|label=Verify
	type=textarea|class=fmtextarea|label=Message|fieldname=fm_message|max=1000|rows=6|req=true
';

// MANUAL FORM CODE
// Advanced users only! please read documentation first

$manual_form_code = '';

// WRAP MESSAGES
// If enabled, this wraps messages to 70 chars per line (for RFC compliance)

$wrap_messages = TRUE;

// SHOW REQUIRED
// If enabled, required fields are marked with an asterisk

$show_required = TRUE;

// SHOW IP AND HOSTNAME
// If enabled, the visitor's IP and hostname are added to the message

$show_ip_hostname = TRUE;

// SPECIAL FIELDS
// These options help generate the email headers. Simply enter a field name,
// and the user input from that field will be used. You can also combine fields. 
// For example, if you have a fm_firstname and fm_lastname field, you could 
// set $sender_name to 'fm_lastname, fm_firstname'

$sender_name = 'fm_name';
$sender_email = 'fm_email';
$email_subject = 'Contact: fm_subject';

// MAX UPLOAD SIZE
// If you are using file uploads in your form, this specifies the max file size.
// (This does not override any server settings you might have in PHP.ini)

$max_file_size = 1000000; // in bytes

// MESSAGE STRUCTURE
// This is an optional setting that allows you to define your own custom message
// template. More information can be found on the web page. If left blank, the script
// will generate the message itself, which is generally suitable for most purposes.
// You use field names in this - they will be replaced with the user input from those fields.

$message_structure = '';

// SUCCESS MESSAGE
// This is the text shown after the visitor has successfully submitted the form.
// You use field names in this - they will be replaced with the user input from those fields.

$sent_message = '<p>Thank you - your message has been sent.</p>';

// AUTO REPLY OPTION
// This optional feature allows you to automatically send a pre-defined auto reply email.
// To use it, simply specify the name and email address you want the message to be 'from', 
// as well as a subject and message. To disable, just leave $auto_reply_message blank.
// You use field names in the message - they will be replaced with the user input from those fields.


$auto_reply_name = '';
$auto_reply_email = '';
$auto_reply_subject = '';
$auto_reply_message = '';

// IMAGE VERIFICATION
// You can disable image verification, use the simple built-in method, or use ReCaptcha
// If you use ReCaptcha, sign up for a free account at http://recaptcha.net and enter the codes below

$verify_method = 'basic'; // 'off', 'basic', or 'recaptcha'

// BASIC IMAGE VERIFICATION OPTIONS

$verify_background = 'F0F0F0';	// hex code for background color
$verify_text = '005ABE';		// hex code for text color
$force_type = '';				// problems showing the code? try forcing to 'gif', 'jpeg' or 'png'

// RECAPTCHA IMAGE VERIFICATION OPTIONS
// Public and private keys - you get these when you sign up an account at http://recaptcha.net

$re_public_key = '';
$re_private_key = '';




// SAVE DATA TO FILE
// If set to TRUE, the form input will be saved in a delimited file

$save_to_file = FALSE;

// STILL SEND EMAIL
// If saving the data to a file, still have the script send the email?

$save_email = TRUE;

// DATA PATH
// The file that will be written to - make sure it has write access

$save_path = 'data.txt';

// DELIMITER
// Fields will be separated by this character. If this character is found in
// the actual data, it will be removed.

$save_delimiter = '|';

// NEWLINES
// Newlines in the data will be replaced by this

$save_newlines = "<br>";



/*
** END OF OPTIONS 
*/


if (!defined('PHP_EOL')) define ('PHP_EOL', strtoupper(substr(PHP_OS,0,3) == 'WIN') ? "\r\n" : "\n");


if (trim($path_contact_page) == '') {
	$path_contact_page = $script_path;
}

$verify_method = strtolower($verify_method);


/* Convert hex color code to R, G, B */
function ddfm_hex_to_rgb($h) {
	$h = trim($h, "#");
	$color = array();	
	if (strlen($h) == 6) {
		$color[] = (int)hexdec(substr($h, 0, 2));
		$color[] = (int)hexdec(substr($h, 2, 2));
		$color[] = (int)hexdec(substr($h, 4, 2));
	} else if (strlen($h) == 3) {
		$color[] = (int)hexdec(substr($h, 0, 1) . substr($h, 0, 1));
		$color[] = (int)hexdec(substr($h, 1, 1) . substr($h, 1, 1));
		$color[] = (int)hexdec(substr($h, 2, 1) . substr($h, 2, 1));
	}
	return $color;
}



/* Handle requests for verification code */
if (isset($_GET['v'])) {
	if ($_GET['v'] == '1') {

		$this_domain = preg_replace("/^www\./", "", $_SERVER['HTTP_HOST']);

		// Choose image type
		$type = '';
		if (function_exists("imagegif")) {
			$type = 'gif';
		} else if (function_exists("imagejpeg")) {
			$type = 'jpeg';
		} else if (function_exists("imagepng")) {
			$type = 'png';
		}

		if (trim($force_type) != '') {
			$type = $force_type;
		}

		// Generate verification code
		srand((double)microtime()*1000000); 
		$ddfmcode = substr(strtoupper(md5(rand(0, 999999999))), 2, 5); 
		$ddfmcode = str_replace("O", "A", $ddfmcode); // for clarity
		$ddfmcode = str_replace("0", "B", $ddfmcode);
		setcookie("ddfmcode", md5($ddfmcode), time()+3600, '/', '.' . $this_domain); 

		// Generate image
		header("Content-type: image/" . $type);
		header("Cache-Control: no-store, no-cache, must-revalidate"); 
		header("Cache-Control: post-check=0, pre-check=0", false); 
		header("Pragma: no-cache"); 
		header("Expires: Mon, 1 Jan 2000 01:00:00 GMT"); // Date in the past
		$image = imagecreate(60, 24);

		list($br, $bg, $bb) = ddfm_hex_to_rgb($verify_background);
		list($rr, $rg, $rb) = ddfm_hex_to_rgb($verify_text);

		$background_color = imagecolorallocate($image, $br, $bg, $bb);
		$text_color = imagecolorallocate($image, $rr, $rg, $rb);

		imagestring($image, 5, 8, 4, $ddfmcode, $text_color);

		switch ($type) {
			case 'gif': imagegif($image); break;
			case 'png': imagepng($image); break;
			case 'jpeg': imagejpeg($image, NULL, 100); break;
		}		
		imagedestroy($image);

		exit();
	}
}


// Load language settings
@include_once($language);


/* Check for GD support */
function ddfm_check_gd_support() {
	if (extension_loaded("gd") && (function_exists("imagegif") || function_exists("imagepng") || function_exists("imagejpeg"))) {
		return TRUE;
	} else {
		return FALSE;
	}
}

/* Safe str_replace */
function ddfm_str_replace($search, $replace, $subject) {
	if (isset($search)) {
		return str_replace($search, $replace, $subject);
	} else {
		return $subject;
	}
}

/* Check for valid URL */
function ddfm_is_valid_url($link) { 
	if (strpos($link, "http://") === FALSE) {
		$link = "http://" . $link;
	}
	$url_parts = @parse_url($link);
	if (empty($url_parts["host"])) 
		return( false );
	if (!empty($url_parts["path"])) {
		$documentpath = $url_parts["path"];
	} else {
		$documentpath = "/";
	}
	if (!empty($url_parts["query"])) {
		$documentpath .= "?" . $url_parts["query"];
	}
	$host = $url_parts["host"];
	$port = $url_parts["port"];
	if (empty($port)) 
		$port = "80";
	$socket = @fsockopen( $host, $port, $errno, $errstr, 30 );
	if (!$socket) {
		return(false);
	} else  {
		fwrite ($socket, "HEAD ".$documentpath." HTTP/1.0\r\nHost: $host\r\nUser-Agent: DDFMVerify\r\n\r\n");
		$http_response = fgets( $socket, 22 );
		if (ereg("200 OK", $http_response, $regs)) {
			return(true);
			fclose($socket);
		} else {
			return(false);
		}
	}
}


/* Check for valid email address */
function dd_is_valid_email($email) {
	/* Credits: http://www.ilovejackdaniels.com/php/email-address-validation/ */

	if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) {    
		return false;  
	}  
	$email_array = explode("@", $email);  
	$local_array = explode(".", $email_array[0]);  
	for ($i = 0; $i < sizeof($local_array); $i++) {     
		if (!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i])) {      
			return false;    
		}  
	}    
	if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) { 
		$domain_array = explode(".", $email_array[1]);    
		if (sizeof($domain_array) < 2) {        
			return false; // Not enough parts to domain    
		}    
		for ($i = 0; $i < sizeof($domain_array); $i++) {      
			if (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$", $domain_array[$i])) {       
				return false;      
			}    
		}  
	} 
	return true;
} 


/* Check for injection characters */
function ddfm_injection_chars($s) {
	return (eregi("\r", $s) || eregi("\n", $s) || eregi("%0a", $s) || eregi("%0d", $s)) ? TRUE : FALSE;
}


/* Make output safe for the browser */
function ddfm_bsafe($input) {
	return htmlspecialchars(stripslashes($input));
}




function ddfm_stripslashes($s) {
	if (get_magic_quotes_gpc()) {
		return stripslashes($s);
	} else {
		return $s;
	}
}


function ddfm_injection_test($str) { 
	$tests = array("/bcc\:/i", "/Content\-Type\:/i", "/Mime\-Version\:/i", "/cc\:/i", "/from\:/i", "/to\:/i", "/Content\-Transfer\-Encoding\:/i"); 
	return preg_replace($tests, "", $str); 
} 



function ddfm_send_mail($recipients, $sender_name, $sender_email, $email_subject, $email_msg, $attachments = false) {

	$extra_recips = '';

	// generate recipient data from list
	if (strpos($recipients, '|')) {

		$rdata = array();
		$ri = 0;
		$rtmp = explode('|', $recipients);
		foreach ($rtmp as $rd) {
			if (trim($rd) != "") {
				list($m, $e) = (array)explode("=", trim($rd), 2);
				$rdata[$ri]['m'] = trim(strtolower($m));
				$rdata[$ri]['e'] = trim($e);
				$ri++;
			}
		}	

		rsort($rdata);

		$r_to = array();
		$extra_recips = "";
		foreach ($rdata as $r) { 
			if ($r['m'] == 'to') $r_to[] = $r['e'];	
			if ($r['m'] == 'cc') $extra_recips .= 'Cc: ' . $r['e'] . PHP_EOL;		
			if ($r['m'] == 'bcc') $extra_recips .= 'Bcc: ' . $r['e'] . PHP_EOL;	
		}
		$send_to = implode(', ', $r_to);
	
	} else {
		$send_to = trim($recipients);
	}


	$sender_name = ddfm_injection_test($sender_name);
	$sender_email = ddfm_injection_test($sender_email);
	$email_subject = ddfm_injection_test($email_subject);


	if (trim($sender_name) == "") {
		$sender_name = 'Anonymous';
	}
	if (trim($sender_email) == "") {
		$sender_email = 'user@domain.com';
	}
	if (trim($email_subject) == "") {
		$email_subject = 'Contact Form';
	}


	$mime_boundary = md5(time()); 

	$headers = '';
	$msg = '';


	$headers .= 'From: ' . $sender_name . ' <' . $sender_email . '>' . PHP_EOL;
	$headers .= $extra_recips;
	$headers .= 'Reply-To: ' . $sender_name . ' <' . $sender_email . '>' . PHP_EOL;
	$headers .= 'Return-Path: ' . $sender_name . ' <' . $sender_email . '>' . PHP_EOL;
	$headers .= "Message-ID: <" . time() . "ddfm@" . $_SERVER['SERVER_NAME'] . ">" . PHP_EOL;
	$headers .= 'X-Sender-IP: ' . $_SERVER["REMOTE_ADDR"] . PHP_EOL;
	$headers .= "X-Mailer: PHP v" . phpversion() . PHP_EOL;

	$headers .= 'MIME-Version: 1.0' . PHP_EOL;
//	$headers .= 'Content-Type: multipart/related; boundary="' . $mime_boundary . '"';
	$headers .= 'Content-Type: multipart/mixed; boundary="' . $mime_boundary . '"';

	$msg .= '--' . $mime_boundary . PHP_EOL;
	$msg .= 'Content-Type: text/plain; charset="iso-8859-1"' . PHP_EOL;
	$msg .= 'Content-Transfer-Encoding: 8bit' . PHP_EOL . PHP_EOL;

	$msg .= $email_msg . PHP_EOL . PHP_EOL;

	if (count($attachments) > 0) {

		for ($i = 0; $i < count($attachments); $i++) { 

			if (is_file($attachments[$i]['tmpfile'])) {

			$handle = fopen($attachments[$i]['tmpfile'], 'rb');
			$f_contents = fread($handle, filesize($attachments[$i]['tmpfile'])); 
			$f_contents = chunk_split(base64_encode($f_contents));
			fclose($handle);		

			$msg .= '--' . $mime_boundary . PHP_EOL;
			$msg .= 'Content-Type: application/octet-stream; name="' . $attachments[$i]['file'] . '"' . PHP_EOL;
			$msg .= 'Content-Transfer-Encoding: base64' . PHP_EOL;
			$msg .= 'Content-Disposition: attachment; filename="' . $attachments[$i]['file'] . '"' . PHP_EOL . PHP_EOL; 
			$msg .= $f_contents . PHP_EOL . PHP_EOL;

			}
       
		}
	}

	$msg .= '--' . $mime_boundary . '--' . PHP_EOL . PHP_EOL;

	@ini_set('sendmail_from', $sender_email);
	$send_status = mail($send_to, $email_subject, $msg, $headers);
	@ini_restore('sendmail_from');

	return $send_status;
}





$form_input = array();




// START of functions to show form output

function ddfm_gen_text($item) {

	// type=text|class=|label=|fieldname=|max=|req=(TRUEFALSE)|[ver=]|[default=]

	global $form_submitted, $form_input, $show_required;

	$req_text = (($show_required) && ($item['req'] == 'true')) ? '<span class="required">' . DDFM_REQUIREDTAG . '</span> ' : '';

	$gen = "";
	$gen .= '<p class="fieldwrap"><label for="' . $item['fieldname'] . '">' . $req_text . $item['label'] . '</label>';
	$gen .= '<input class="' . $item['class'] . '" type="text" name="' . $item['fieldname'] . '" id="' . $item['fieldname'] . '" value="';

	if ($form_submitted) {
		$gen .= ddfm_bsafe($form_input[$item['fieldname']]);
	} else if (isset($item['default'])) {
		$gen .= ddfm_bsafe($item['default']);
	}

	$gen .= '" /></p>' . "\n\n";

	return $gen;
}



function ddfm_gen_password($item) {

	// type=password|class=|label=|fieldname=|max=|req=(TRUEFALSE)|confirm=(TRUEFALSE)

	global $form_submitted, $form_input, $show_required;

	$req_text = (($show_required) && $item['req'] == 'true') ? '<span class="required">' . DDFM_REQUIREDTAG . '</span> ' : '';

	$gen = "";
	$gen .= '<p class="fieldwrap"><label for="' . $item['fieldname'] . '">' . $req_text . $item['label'] . '</label>' . "\n";
	$gen .= '<input class="' . $item['class'] . '" type="password" name="' . $item['fieldname'] . '" id="' . $item['fieldname'] . '" value="';
	$gen .= '" /></p>' . "\n\n";

	if ($item['confirm'] == 'true') {

		// Duplicate field (add 'c' to end)
		$gen .= '<p class="fieldwrap"><label for="' . $item['fieldname'] . 'c">' . $req_text . DDFM_CONFIRMPASS . ' ' . $item['label'] . '</label>' . "\n";
		$gen .= '<input class="' . $item['class'] . '" type="password" name="' . $item['fieldname'] . 'c" id="' . $item['fieldname'] . 'c" value="';
		$gen .= '" /></p>' . "\n\n";

	}

	return $gen;
}



function ddfm_gen_textarea($item) {

	// type=textarea|class=|label=|fieldname=|max=|rows=|req=(TRUEFALSE)|[default=]

	global $form_submitted, $form_input, $show_required;

	$req_text = (($show_required) && $item['req'] == 'true') ? '<span class="required">' . DDFM_REQUIREDTAG . '</span> ' : '';

	$gen = "";
	$gen .= '<p class="fieldwrap"><label for="' . $item['fieldname'] . '">' . $req_text . $item['label'] . '</label>' . "\n";
	$gen .= '<textarea class="' . $item['class'] . '" name="' . $item['fieldname'] . '" cols="20" rows="' . $item['rows'] . '" id="' . $item['fieldname'] . '">';

	if ($form_submitted) {
		$gen .= ddfm_bsafe($form_input[$item['fieldname']]);
	} else if (isset($item['default'])) {
		$gen .= ddfm_bsafe($item['default']);
	}

	$gen .= '</textarea></p>' . "\n\n";

	return $gen;
}



function ddfm_gen_widetextarea($item) {

	// type=widetextarea|class=|label=|fieldname=|max=|rows=|req=(TRUEFALSE)|[default=]

	global $form_submitted, $form_input, $show_required;

	$req_text = (($show_required) && $item['req'] == 'true') ? '<span class="required">' . DDFM_REQUIREDTAG . '</span> ' : '';

	$gen = "";
	$gen .= '<p class="fieldwrap"><label for="' . $item['fieldname'] . '" class="fmtextlblwide">' . $req_text . $item['label'] . '</label>' . "\n";
	$gen .= '<textarea class="' . $item['class'] . '" name="' . $item['fieldname'] . '" cols="20" rows="' . $item['rows'] . '" id="' . $item['fieldname'] . '">';

	if ($form_submitted) {
		$gen .= ddfm_bsafe($form_input[$item['fieldname']]);
	} else if (isset($item['default'])) {
		$gen .= ddfm_bsafe($item['default']);
	}

	$gen .= '</textarea></p>' . "\n\n";

	return $gen;
}



function ddfm_gen_verify($item) {

	// type=verify|class=|label=

	global $verify_method, $show_required, $script_path;

	if ($verify_method != 'basic') return '';

	$req_text = ($show_required) ? '<span class="required">' . DDFM_REQUIREDTAG . '</span> ' : '';

	$gen = "";

	if (ddfm_check_gd_support()) {
		$gen .= '<p class="fieldwrap"><label for="fm_verify">' . $req_text . $item['label'] . '</label>' . "\n";
		$gen .= '<input class="'. $item['class'] . '" type="text" name="fm_verify" id="fm_verify" />' . "\n";
		$gen .= '<img src="' . $script_path . '?v=1" alt="' . $item['label'] . '" title="' . $item['label'] . '" />';
		$gen .= '</p>' . "\n\n";
	}

	return $gen;
}


function ddfm_gen_fullblock($item) {

	// type=fullblock|class=|text=

	$gen = "";

	$gen .= '<div class="' . $item['class'] . '"><p class="fieldwrap">' . "\n";
	$gen .= $item['text'] . "\n";
	$gen .= '</p></div>' . "\n\n";

	return $gen;
}


function ddfm_gen_halfblock($item) {

	// type=halfblock|class=|text=

	$gen = "";

	$gen .= '<div class="' . $item['class'] . '"><p class="fieldwrap">' . "\n";
	$gen .= $item['text'] . "\n";
	$gen .= '</p></div>' . "\n\n";

	return $gen;
}

  
function ddfm_gen_openfieldset($item) {

	// type=openfieldset|legend=

	$gen = "";

	$gen .= '<fieldset><legend>' . ddfm_bsafe($item['legend']) . '</legend>' . "\n\n";

	return $gen;
}


function ddfm_gen_closefieldset($item) {

	// type=closefieldset

	$gen = "";

	$gen .= '</fieldset>' . "\n\n";

	return $gen;
}


function ddfm_gen_checkbox($item) {

	// type=checkbox|class=|label=|data=
	//	 (fieldname),(text),(CHECKED),(REQUIRED),
	//	 (fieldname),(text),(CHECKED),(REQUIRED),
	//	 (fieldname),(text),(CHECKED),(REQUIRED)

	global $form_submitted, $form_input, $show_required;

	$gen = "";

	$gen .= '<p class="fieldwrap"><label>' . $item['label'] . '</label><div class="' . $item['class'] . '">' . "\n";

	$data = explode(",", trim($item['data']));

	for ($i = 0; $i < sizeof($data); $i+=4) {

		$req_text = (($show_required) && ($data[$i+3] == 'true')) ? ' <span class="required">' . DDFM_REQUIREDTAG . '</span>' : '';

		$gen .= '<p><input type="checkbox" name="' . 
			$data[$i] . '" value="' . $data[$i + 1] . '"';

		if ($form_submitted) {
			if (trim($form_input[$data[$i]]) != '') {
				$gen .= ' checked="checked"';
			}
		} else {
			if ($data[$i + 2] == 'true') {
				$gen .= ' checked="checked"';
			}
		}

		$gen .= ' />' . $data[$i + 1] . $req_text . '</p>' . "\n";
	}

	$gen .= '</div></p>' . "\n\n";

	return $gen;
}


function ddfm_gen_radio($item) {

	//  type=radio|class=|label=|fieldname=|req=|[default=]|data=
	//	  (text),(text),(text)

	global $form_submitted, $form_input, $show_required;

	$req_text = (($show_required) && ($item['req'] == 'true')) ? '<span class="required">' . DDFM_REQUIREDTAG . '</span> ' : '';

	$gen = "";

	$gen .= '<p class="fieldwrap"><label>' . $req_text . $item['label'] . '</label><div class="' . $item['class'] . '">' . "\n";
	
	$c = 1;

	$data = explode(",", trim($item['data']));
	
	for ($i = 0; $i < sizeof($data); $i++) {

		$gen .= '<p><input type="radio" name="' . 
			$item['fieldname'] . '" value="' . $data[$i] . '"';

		if ($form_submitted) {
			if (trim($form_input[$item['fieldname']]) == $data[$i]) {
				$gen .= ' checked="checked"';
			}
		} else {
			if ($c == $item['default']) {
				$gen .= ' checked="checked"';
			}
		}

		$gen .= ' />' . $data[$i] . '</p>' . "\n";

		$c++;
	}

	$gen .= '</div></p>' . "\n\n";

	return $gen;
}



function ddfm_gen_select($item) {

	//	type=select|class=|label=|fieldname=|multi=(TRUEFALSE)|data=
	//    (#group),(text),(text),(#group),(text),(text)

	global $form_submitted, $form_input, $show_required;

	$req_text = (($show_required) && ($item['req'] == 'true')) ? '<span class="required">' . DDFM_REQUIREDTAG . '</span> ' : '';

	$gen = "";

	$gen .= '<p class="fieldwrap"><label>' . $req_text . $item['label'] . '</label><select class="' . $item['class'] . '" name="' . $item['fieldname'];

	if ($item['multi'] == 'true') {
		$gen .= '[]';
	}

	$gen .= '"';

	if ($item['multi'] == 'true') {
		$gen .= ' multiple="multiple"';
	}

	$gen .= '>' . "\n";
	
	$c = 1;

	$og = FALSE;

	$data = explode(",", trim($item['data']));

	for ($i = 0; $i < sizeof($data); $i++) {

		if (substr($data[$i], 0, 1) == '#' ) {

			if ($og) {
				$gen .= '</optgroup>' . "\n";	
			}
			$gen .= '<optgroup label="' . ltrim($data[$i], '#') . '">' . "\n";
			$og = TRUE;

		} else {

			$gen .= '<option';

			if ($form_submitted) {
		
				if ($item['multi'] == 'true') {

					foreach ((array)$form_input[$item['fieldname']] as $ii) {

						if ($data[$i] == $ii) {
							$gen .= ' selected="selected"';
						}
					}
			
				} else {

					if (trim($form_input[$item['fieldname']]) == $data[$i]) {
						$gen .= ' selected="selected"';
					}

				}
			} 

			$gen .= ' >' . $data[$i] . '</option>' . "\n";
		}

		$c++;
	}

	if ($og) {
		$gen .= '</optgroup>' . "\n";	
		$og = FALSE;
	} 

	$gen .= '</select></p>' . "\n\n";

	return $gen;
}


function ddfm_gen_file($item) {

	// type=file|class=|label=|fieldname=|req=(TRUEFALSE)|[allowed=1,2,3]

	global $form_submitted, $form_input, $show_required, $max_file_size;

	$req_text = (($show_required) && ($item['req'] == 'true')) ? '<span class="required">' . DDFM_REQUIREDTAG . '</span> ' : '';

	$gen = "";

	$gen .= '<p class="fieldwrap"><label for="' . $item['fieldname'] . '">' . $req_text . $item['label'] . '</label>' . "\n";
	$gen .= '<input class="' . $item['class'] . '" type="file" name="' . $item['fieldname'] . '" id="' . $item['fieldname'] . '" ';
	$gen .= ' /></p>' . "\n\n";

	return $gen;
}


function ddfm_gen_selrecip($item) {

	// type=selrecip|class=|label=|data=User1,user1@domain.com,User2 etc..

	global $form_submitted, $form_input, $show_required;

	$req_text = ($show_required) ? '<span class="required">' . DDFM_REQUIREDTAG . '</span> ' : '';

	$gen = "";

	$gen .= '<p class="fieldwrap"><label>' . $req_text . $item['label'] . '</label><select class="' . $item['class'] . '" name="fm_selrecip">' . "\n";
	

	$data = explode(",", trim($item['data']));


	$gen .= '<option';
	if ($form_submitted) {
		if (trim($form_input['fm_selrecip']) == $data[0]) {
			$gen .= ' selected="selected"';
		}
	}
	$gen .= ' >' . $data[0] . '</option>' . "\n";



	for ($i = 1; $i < sizeof($data); $i+=2) {

		$gen .= '<option';

		if ($form_submitted) {
			if (trim($form_input['fm_selrecip']) == $data[$i]) {
				$gen .= ' selected="selected"';
			}
		}

		$gen .= ' >' . $data[$i] . '</option>' . "\n";
	}

	$gen .= '</select></p>' . "\n\n";

	return $gen;

}


// END of functions to show form output


	
/* Generate the script output */


		// convert $form_struct into array of strings
		$form_struct = (array)explode('<br />', nl2br(trim($form_struct)));

		// Prepare globals
		$form_submitted = FALSE;

		$message_sent = FALSE;


		// Prepare output

		$o = "\n\n\n" . '<!-- START of Dagon Design Formmailer output -->' . "\n\n";



		// Convert form structure to multi-dimensional array

		$fs_tmp1 = array();
		$fs_tmp2 = array();
		$fitem = 0;

		foreach ($form_struct as $fs) {
			if (trim($fs) != "") {
				$fs_tmp1 = (array)explode("|", trim($fs));
				foreach ($fs_tmp1 as $fs1) {
					list($k, $v) = (array)explode("=", trim($fs1), 2);	

					$fs_tmp2[$fitem][$k] = $v;
				}			
			}

			$fitem++;
		}
		$form_struct = $fs_tmp2;



		// Make sure form structure is not missing empty keys

		$valid_keys = array('fieldname', 'type', 'req', 'label', 'max', 'ver', 'confirm', 'data', 'multi', 'allowed', 'default');

		for ($i = 0; $i < count($form_struct); $i++) {
			foreach ($valid_keys as $k) {
				if (!isset($form_struct[$i][$k])) $form_struct[$i][$k] = NULL;
			}
		}
	
	


	// Was form submitted?

	if (isset($_POST["form_submitted"])) {



		$form_submitted = TRUE;

		$mail_message = "";
		$csv = "";

		$attached_files = array();
		$attached_index = 0;

		$sel_recip = NULL;

		$message_structure = trim($message_structure);



		unset($errors);
		$errors = array();


		if ($verify_method == 'recaptcha') {
			@include_once('recaptchalib.php');
			$privatekey = $re_private_key;
			$resp = recaptcha_check_answer ($privatekey,
                                $_SERVER["REMOTE_ADDR"],
                                $_POST["recaptcha_challenge_field"],
                                $_POST["recaptcha_response_field"]);
			if (!$resp->is_valid) {
				$errors[] = DDFM_INVALIDVER;
			}
		}



		// Get form input and put in array

		foreach ($_POST as $key => $i) {

			if ($key != "form_submitted") {
				if (!is_array($i)) {
					$form_input[strtolower($key)] = trim($i);
				} else {
					$form_input[strtolower($key)] = $i;
				}
			}

		}

		
		$msg_field_sep = ': ';
		$msg_field_line_end = "\n\n";


		$fsindex = -1;

		// Validate input
		foreach ($form_struct as $fs) {
		
			if (!isset($form_input[$fs['fieldname']])) {
				$form_input[$fs['fieldname']] = '';
			}

			$fsindex++;

			// check for fields used in vars
			if (isset($form_input[$fs['fieldname']])) {
				$sender_name = ddfm_str_replace($fs['fieldname'], ddfm_stripslashes($form_input[$fs['fieldname']]), $sender_name);
				$sender_email = ddfm_str_replace($fs['fieldname'], ddfm_stripslashes($form_input[$fs['fieldname']]), $sender_email);
				$email_subject = ddfm_str_replace($fs['fieldname'], ddfm_stripslashes($form_input[$fs['fieldname']]), $email_subject);
			}

			switch ($fs['type']) {

				case 'text':

					// type=text|class=|label=|fieldname=|max=|req=(TRUEFALSE)|[ver=]|[default=]

					$t = ddfm_stripslashes($form_input[$fs['fieldname']]);

					if ((strtolower($fs['req']) == 'true') && ($t == "")) { 

						$errors[] = DDFM_MISSINGFIELD . " '" . $fs['label'] . "'";

					} else if (strlen($t) > (int)$fs['max']) {

						$errors[] = $fs['max'] . ' ' . DDFM_MAXCHARLIMIT . " '" . $fs['label'] . "'";

					} else if (ddfm_injection_chars($t)) {

						$errors[] = DDFM_INVALIDINPUT . " '" . $fs['label'] . "'";

					} else	if ((strtolower($fs['ver']) == 'email') && ((strtolower($fs['req']) == "true") || ($t != ""))) {

						if (!dd_is_valid_email($t)) $errors[] = DDFM_INVALIDEMAIL . " '" . $fs['label'] . "'";

					} else if ((strtolower($fs['ver']) == 'url') && ((strtolower($fs['req']) == "true") || ($t != ""))) {

						if (!ddfm_is_valid_url($t)) $errors[] = DDFM_INVALIDURL . " '" . $fs['label'] . "'";

					} 

					$csv .= str_replace($save_delimiter, ' ', $t) . $save_delimiter;
					$mail_message .= $fs['label'] . $msg_field_sep . $t . $msg_field_line_end;
					$message_structure = ddfm_str_replace($fs['fieldname'], $t, $message_structure);
					$auto_reply_message = ddfm_str_replace($fs['fieldname'], $t, $auto_reply_message);
					$sent_message = ddfm_str_replace($fs['fieldname'], ddfm_bsafe($t), $sent_message);


					break;

				case 'password':

					// type=password|class=|label=|fieldname=|max=|req=(TRUEFALSE)|confirm=(TRUEFALSE)

					$t = ddfm_stripslashes($form_input[$fs['fieldname']]);

					if ((strtolower($fs['req']) == 'true') && ($t == "")) {

						$errors[] = DDFM_MISSINGFIELD . " '" . $fs['label'] . "'";
		
					} else if (strlen($t) > (int)$fs['max']) {

						$errors[] = $fs['max'] . ' ' . DDFM_MAXCHARLIMIT . " '" . $fs['label'] . "'";

					} else if (ddfm_injection_chars($t)) {

						$errors[] = DDFM_INVALIDINPUT . " '" . $fs['label'] . "'";

					} else if (strtolower($fs['confirm']) == 'true') {

						$tc = ddfm_stripslashes($form_input[$fs['fieldname']  . 'c']);
			
						if ($t != $tc) $errors[] = DDFM_NOMATCH . " '" . $fs['label'] . "'";

					}

					$csv .= str_replace($save_delimiter, ' ', $t) . $save_delimiter;
					$mail_message .= $fs['label'] . $msg_field_sep . $t . $msg_field_line_end;
					$message_structure = ddfm_str_replace($fs['fieldname'], $t, $message_structure);
					$auto_reply_message = ddfm_str_replace($fs['fieldname'], $t, $auto_reply_message);
					$sent_message = ddfm_str_replace($fs['fieldname'], ddfm_bsafe($t), $sent_message);


					break;

				case 'textarea':
				case 'widetextarea':
			
					// type=textarea|class=|label=|fieldname=|max=|rows=|req=(TRUEFALSE)|[default=]

					$t = ddfm_stripslashes($form_input[$fs['fieldname']]);

					if ((strtolower($fs['req']) == 'true') && ($t == "")) {

						$errors[] = DDFM_MISSINGFIELD . " '" . $fs['label'] . "'";

					} else if (strlen($t) > (int)$fs['max']) {
			
						$errors[] = $fs['max'] . ' ' . DDFM_MAXCHARLIMIT . " '" . $fs['label'] . "'";

					}

					$csv .= str_replace($save_delimiter, ' ', $t) . $save_delimiter;
					$mail_message .= $fs['label'] . $msg_field_sep . $t . $msg_field_line_end;
					$message_structure = ddfm_str_replace($fs['fieldname'], $t, $message_structure);
					$auto_reply_message = ddfm_str_replace($fs['fieldname'], $t, $auto_reply_message);
					$sent_message = ddfm_str_replace($fs['fieldname'], ddfm_bsafe($t), $sent_message);


					break;				

				case 'verify':

					// type=verify|class=|label=

					if ($verify_method == 'basic') {

						$t = ddfm_stripslashes($form_input['fm_verify']);

						if ($t == "") {

							$errors[] = DDFM_MISSINGVER;

						} else if (trim($_COOKIE["ddfmcode"]) == "") {

							$errors[] = DDFM_NOVERGEN;

						} else if ($_COOKIE["ddfmcode"] != md5(strtoupper($t))) { 

							$errors[] = DDFM_INVALIDVER;

						} 

					}

					break;

				case 'checkbox':

					//  type=checkbox|class=|label=|data=
					//	  (fieldname),(text),(CHECKED),(REQUIRED),
					//	  (fieldname),(text),(CHECKED),(REQUIRED),
					//	  (fieldname),(text),(CHECKED),(REQUIRED)

					$data = explode(",", trim($fs['data']));

					$tmp_msg = array();

					for ($i = 0; $i < count($data); $i+=4) {

						$t = ddfm_stripslashes(trim($form_input[$data[$i]]));

						if ((strtolower($data[$i+3]) == 'true') && ($t == "")) {
							$errors[] = DDFM_MISSINGFIELD . " '" . $fs['label'] . "'";
						}

						if ($t != "") $tmp_msg[] = $t;

						$message_structure = ddfm_str_replace($data[$i], $t, $message_structure);
						$auto_reply_message = ddfm_str_replace($data[$i], $t, $auto_reply_message);
						$sent_message = ddfm_str_replace($data[$i], ddfm_bsafe($t), $sent_message);


					}

					$csv .= str_replace($save_delimiter, ' ', implode(', ', $tmp_msg)) . $save_delimiter;
					$mail_message .= $fs['label'] . $msg_field_sep . implode(', ', $tmp_msg) . $msg_field_line_end;

					break;
				
				case 'radio':

					//  type=radio|class=|label=|fieldname=|req=|[default=]|data=
					//	  (text),(text),(text),(text)

					$t = ddfm_stripslashes(trim($form_input[$fs['fieldname']]));

					if ((strtolower($fs['req']) == 'true') && ($t == "")) {

						$errors[] = DDFM_MISSINGFIELD . " '" . $fs['label'] . "'";

					}

					$csv .= str_replace($save_delimiter, ' ', $t) . $save_delimiter;	
					$mail_message .= $fs['label'] . $msg_field_sep . $t . $msg_field_line_end;
					$message_structure = ddfm_str_replace($fs['fieldname'], $t, $message_structure);
					$auto_reply_message = ddfm_str_replace($fs['fieldname'], $t, $auto_reply_message);
					$sent_message = ddfm_str_replace($fs['fieldname'], ddfm_bsafe($t), $sent_message);

					break;

				case 'select':

					//  type=select|class=|label=|fieldname=|multi=(TRUEFALSE)|data=
					//    (#group),(text),(text),(#group),(text),(text)

					$data = explode(",", trim($fs['data']));

					if (strtolower($fs['multi']) != 'true') {				

						$t = ddfm_stripslashes($form_input[$fs['fieldname']]);

						if ((strtolower($fs['req']) == 'true') && (($t == "") || ($t == $first_item))) {

							$errors[] = DDFM_MISSINGFIELD . " '" . $fs['label'] . "'";

						}
					
						$csv .= str_replace($save_delimiter, ' ', $t) . $save_delimiter;
						$mail_message .= $fs['label'] . $msg_field_sep . $t . $msg_field_line_end;
						$message_structure = ddfm_str_replace($fs['fieldname'], $t, $message_structure);
						$auto_reply_message = ddfm_str_replace($fs['fieldname'], $t, $auto_reply_message);
						$sent_message = ddfm_str_replace($fs['fieldname'], ddfm_bsafe($t), $sent_message);


					} else { // multi = true
	
						$t = (array)$form_input[$fs['fieldname']];

						if ((count($t) == 1) && ($t[0] == '')) {
							unset($t[0]);
						}

						if ((strtolower($fs['req']) == 'true') && (count($t) == 0)) {
							$errors[] = DDFM_MISSINGFIELD . " '" . $fs['label'] . "'";
						}
						
						$tmp_msg = array();

						foreach ($t as $tt) {
							if ($tt != "") $tmp_msg[] = $tt;
						}

						$csv .= str_replace($save_delimiter, ' ', implode(', ', $tmp_msg)) . $save_delimiter;
						$mail_message .= $fs['label'] . $msg_field_sep . implode(', ', $tmp_msg) . $msg_field_line_end;
						$message_structure = ddfm_str_replace($fs['fieldname'], implode(', ', $tmp_msg), $message_structure);
						$auto_reply_message = ddfm_str_replace($fs['fieldname'], implode(', ', $tmp_msg), $auto_reply_message);
						$sent_message = ddfm_str_replace($fs['fieldname'], ddfm_bsafe(implode(', ', $tmp_msg)), $sent_message);
				
					}

					break;

				case 'file':

					// type=file|class=|label=|fieldname=|[req=]|[allowed=1,2,3]

					if ((strtolower($fs['req']) == 'true') && (($_FILES[$fs['fieldname']]['name'] == ""))) { 
						$errors[] = DDFM_MISSINGFILE . " '" . $fs['label'] . "'";
					}
					

					$allowed = array();

					if (trim($fs['allowed']) != "") {
						$allowed = (array)explode(",", trim(strtolower($fs['allowed'])));
					}

					if (($_FILES[$fs['fieldname']]['name'] != "") && ((int)$_FILES[$fs['fieldname']]['size'] == 0)) {

							$errors[] = DDFM_FILETOOBIG . ' ' . $_FILES[$fs['fieldname']]['name'];

					} else if ($_FILES[$fs['fieldname']]['tmp_name'] != "") {

						if (($_FILES[$fs['fieldname']]['error'] == UPLOAD_ERR_OK) && ($_FILES[$fs['fieldname']]['size'] > 0)) {

							$origfilename = $_FILES[$fs['fieldname']]['name'];
							$filename = explode(".", $_FILES[$fs['fieldname']]['name']);
							$filenameext = $filename[count($filename) - 1];
							unset($filename[count($filename) - 1]);
							$filename = implode(".", $filename);
							$filename = substr($filename, 0, 15) . "." . $filenameext;
							$file_ext_allow = TRUE;

							if (count($allowed) > 0) {

								$file_ext_allow = FALSE;
								
								for ($x = 0; $x < count($allowed); $x++) { 
									if (strtolower($filenameext) == strtolower($allowed[$x])) {
										$file_ext_allow = TRUE;
									}
								} 
							}
							if ($file_ext_allow) {

								if((int)$_FILES[$fs['fieldname']]['size'] < $max_file_size) {

									$attached_files[$attached_index]['file'] = $_FILES[$fs['fieldname']]['name']; 
									$attached_files[$attached_index]['tmpfile'] = $_FILES[$fs['fieldname']]['tmp_name']; 
									$attached_files[$attached_index]['content_type'] = $_FILES[$fs['fieldname']]['type']; 
									$attached_index++;

									$csv .= str_replace($save_delimiter, ' ', $_FILES[$fs['fieldname']]['name']) . $save_delimiter;
									$mail_message .= DDFM_ATTACHED . $msg_field_sep . $_FILES[$fs['fieldname']]['name'] . "\n\n"; 

									$message_structure = ddfm_str_replace($fs['fieldname'], $_FILES[$fs['fieldname']]['name'], $message_structure);
									$auto_reply_message = ddfm_str_replace($fs['fieldname'], $_FILES[$fs['fieldname']]['name'], $auto_reply_message);
									$sent_message = ddfm_str_replace($fs['fieldname'], $_FILES[$fs['fieldname']]['name'], $sent_message);					

								} else { 
									$errors[] = DDFM_FILETOOBIG . ' ' . $_FILES[$fs['fieldname']]['name'];
								}
							} else { 
								$errors[] = DDFM_INVALIDEXT . ' ' . $_FILES[$fs['fieldname']]['name'];
							}
						} else { 
							$errors[] = DDFM_UPLOADERR . ' ' . $_FILES[$fs['fieldname']]['name'];
						}
					} 

					$message_structure = ddfm_str_replace($fs['fieldname'], '', $message_structure);
					$auto_reply_message = ddfm_str_replace($fs['fieldname'], $t, $auto_reply_message);
					$sent_message = ddfm_str_replace($fs['fieldname'], ddfm_bsafe($t), $sent_message);

					break;


				case 'selrecip':

					//  type=selrecip|class=|label=|data=(select),User1,user1@domain.com,User2 etc..

					$data = explode(",", trim($fs['data']));
					
					$t = ddfm_stripslashes($form_input['fm_selrecip']);

					if (($t == "") || ($t == $data[0])) {

						$errors[] = DDFM_MISSINGFIELD . " '" . $fs['label'] . "'";

					} else {

						for ($i = 1; $i < count($data); $i+=2) {

							if ($data[$i] == $t) {
								$sel_recip = $data[$i+1];
							}
						}

					}	

					break;

			}


		}



		// make sure no un-used fieldnames are left in template
		foreach ($form_struct as $fs) {
			$message_structure = ddfm_str_replace($fs['fieldname'], '', $message_structure);
			$auto_reply_message = ddfm_str_replace($fs['fieldname'], '', $auto_reply_message);
			$sent_message = ddfm_str_replace($fs['fieldname'], '', $sent_message);
		}



		if (ddfm_injection_chars($sender_name)) $errors[] = DDFM_INVALIDINPUT;
		if (ddfm_injection_chars($sender_email)) $errors[] = DDFM_INVALIDINPUT;
		if (ddfm_injection_chars($email_subject)) $errors[] = DDFM_INVALIDINPUT;




		
		if ($errors) {

			$o .= '<div class="ddfmwrap"><div class="ddfmerrors">' . DDFM_ERRORMSG . '</div>';
			$o .= '<div class="errorlist">';
			foreach ($errors as $err) {
				$o .= $err . '<br />';
			}
			$o .= '</div><div style="clear:both;"><!-- --></div></div>';
	
		} else {

			if ($wrap_messages) {
				$mail_message = wordwrap($mail_message, 70);
			}

			if ($recipients == 'selrecip') {
				$recipients = $sel_recip;
			}

			// if template exists, use it instead
			if (strlen(trim($message_structure)) > 0) {
				$mail_message = $message_structure . "\n\n";
			}

			if ($show_ip_hostname) {
				$mail_message .= 'IP: ' . $_SERVER['REMOTE_ADDR'] . "\n" . 'HOST: ' . gethostbyaddr($_SERVER['REMOTE_ADDR']) . "\n";
			}


			$sndmsg = TRUE;
			if (($save_to_file == TRUE) && ($save_email == FALSE)) {
				$sndmsg = FALSE;
			}
			
			$csv = str_replace("\n", $save_newlines, $csv);
			$csv = str_replace("\r", '', $csv);
			$csv = substr($csv, 0, strlen($csv) - strlen($save_delimiter));


			if (is_writable($save_path)) {
				$handle = fopen($save_path, 'a+');
				fwrite($handle, $csv . "\n");
				fclose($handle);
			}


			if ($sndmsg == TRUE) {

				if (ddfm_send_mail($recipients, $sender_name, $sender_email, $email_subject, $mail_message, $attached_files)) {
		
					$o .= $sent_message;
	
					$auto_reply_name = trim($auto_reply_name);
					$auto_reply_email = trim($auto_reply_email);
					$auto_reply_subject = trim($auto_reply_subject);
					$auto_reply_message = trim($auto_reply_message);
	
					if (($auto_reply_message != "") && (trim($sender_email != ""))) {
	
						$auto_reply_header = "";
						$auto_reply_header .= 'From: ' . $auto_reply_name . ' <' . $auto_reply_email . '>' . PHP_EOL;
						$auto_reply_header .= 'Reply-To: ' . $auto_reply_name . ' <' . $auto_reply_email . '>' . PHP_EOL;
						$auto_reply_header .= 'Return-Path: ' . $auto_reply_name . ' <' . $auto_reply_email . '>' . PHP_EOL;
	
						mail($sender_email, $auto_reply_subject, $auto_reply_message, $auto_reply_header);
					}

					$message_sent = TRUE;

					$_POST = array();

				} else {
					$o .= DDFM_SERVERERR;
					$message_sent = FALSE;
				}

			} else {
			
				$o .= $sent_message;
			
			}
	
		}


	} // end of form submission processing






	// Generate form if message has not been sent

	if (!$message_sent) {	


		if ($verify_method == 'basic' && !ddfm_check_gd_support()) {
			$o .= DDFM_GDERROR;
		}

		if (trim($manual_form_code) == '') {	// ** Use normal form generation
		
			$o .= '<div class="ddfmwrap">';
			$o .= '<form class="ddfm" method="post" action="' . $path_contact_page . '" enctype="multipart/form-data">' . "\n\n";
	
			// Loop through form items

			foreach ($form_struct as $f_i) {

				switch ($f_i['type']) {

					case 'text':
						$o .= ddfm_gen_text($f_i, $show_required);	
						break;
					case 'password':
						$o .= ddfm_gen_password($f_i, $show_required);	
						break;
					case 'textarea':
						$o .= ddfm_gen_textarea($f_i, $show_required);	
						break;
					case 'widetextarea':
						$o .= ddfm_gen_widetextarea($f_i, $show_required);	
						break;
					case 'verify':
						$o .= ddfm_gen_verify($f_i, $show_required);	
						break;
					case 'fullblock':
						$o .= ddfm_gen_fullblock($f_i, $show_required);	
						break;
					case 'halfblock':
						$o .= ddfm_gen_halfblock($f_i, $show_required);	
						break;
					case 'openfieldset':
						$o .= ddfm_gen_openfieldset($f_i, $show_required);	
						break;
					case 'closefieldset':
						$o .= ddfm_gen_closefieldset($f_i, $show_required);	
						break;
					case 'checkbox':
						$o .= ddfm_gen_checkbox($f_i, $show_required);	
						break;
					case 'radio':
						$o .= ddfm_gen_radio($f_i, $show_required);	
						break;
					case 'select':
						$o .= ddfm_gen_select($f_i, $show_required);	
						break;
					case 'file':
						$o .= ddfm_gen_file($f_i, $show_required);	
						break;				
					case 'selrecip':
						$o .= ddfm_gen_selrecip($f_i, $show_required);	
						break;
				}
			}

			if ($verify_method == 'recaptcha') {

				$o .= "<script>
				var RecaptchaOptions = {
			    theme : 'white'
				};
				</script>";

				@include_once('recaptchalib.php');
				$publickey = $re_public_key;

				$o .= '<div class="recaptcha"><div class="recaptcha-inner">';
				$o .= recaptcha_get_html($publickey);
				$o .= '</div></div>';
			}

			$o .= '<p><input type="hidden" name="MAX_FILE_SIZE" value="' . $max_file_size . '" /></p>' . "\n";
			$o .= '<div class="submit"><input type="submit" name="form_submitted" value="' . DDFM_SUBMITBUTTON . '" /></div>' . "\n\n";


			$o .= '</form>';
			$o .= '</div>' . "\n\n";

		} else { 
			// Use manual form code

			$o .= $manual_form_code;

		}

		// Form generation complete

	} // end of display form code


	$o .= '<!-- END of Dagon Design Formmailer output -->' . "\n\n\n";




/* Page Generation */


if ($standalone) {
// START OF PAGE HEADER
?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Contact</title>
<link rel="stylesheet" href="<?php echo $path_to_css; ?>" type="text/css" media="screen" />
</head>
<body>

<div align="center">
<div style="width: 380px; margin: 0 auto 0 auto; text-align:left;">

<?php
// END OF PAGE HEADER
}

// show script output
echo $o; 

if ($standalone) {
// START OF PAGE FOOTER
?>

</div>
</div>


</body>
</html>


<?php
// END OF PAGE FOOTER
}


?>