<?php # Manage emails (interface to phpMailer)
if(count(get_included_files())==1)  exit;  //direct access not permitted
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once "{$_SERVER['DOCUMENT_ROOT']}/inc/phpmailer/PHPMailer.php";
require_once "{$_SERVER['DOCUMENT_ROOT']}/inc/phpmailer/Exception.php";
require_once "{$_SERVER['DOCUMENT_ROOT']}/inc/phpmailer/SMTP.php";

require_once "{$_SERVER['DOCUMENT_ROOT']}/inc/ckrit/ckrit_email.php";  //email secrets

const EMAIL_NAME = 'Thunkonaut';          //From name for all emails sent by server
const EMAIL_FROM = 'info@thunkonaut.com'; //Reply-To: for all emails sent by server
const EMAIL_LOGO = '/img/hdrlogo.png'; //logo image for EmailUser

//---------------------------------------------------------------------------
//StartEmail - wrapper for PHPMailer;  don't forget to call $mail->send()
function StartEmail($toEmail, $toName, $subj, $msg) {
try {
	$mail = new PHPMailer(true); //true = enable exceptions
	$mail->addAddress($toEmail, $toName);   //To: user
	$mail->setFrom(EMAIL_FROM, EMAIL_NAME); //From: info@;  also used as Reply-To
	$mail->isHTML(true);
	$mail->Subject = $subj;
	$mail->msgHTML($msg); //html version AND auto-generated (tags stripped) text version
	//NOTE: For text version, all html tags are stripped.  For example:
	//  <a href="$g_siteURL">$g_siteURL</a>        =>  $g_siteURL
	//  <img src="$g_siteURL/img/KonaEarth.jpg">   => ''

	$mail->isSMTP(); //smtp=send(port 25), pop3=recv/download (port 110), imap=recv/leave on server (port 143)
	$mail->SMTPOptions = array('ssl' => array(
		'verify_peer' => false,
		'verify_peer_name' => false,
		'allow_self_signed' => true
	));
	$mail->CharSet = 'UTF-8';
	$mail->Host = EMAIL_HOST;
	$mail->SMTPAuth = true;
	$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
	$mail->Port = 587;
	$mail->Username = EMAIL_USER;
	$mail->Password = EMAIL_PSWD;
//	if(IsLocalHost()) { //show PHPMailer debug info
//		$mail->SMTPDebug = 2; //0=off, 1=client msgs, 2=client & server msgs
//		$mail->Debugoutput = 'html'; //'echo'=text only, 'html'=browser
//	}

	return $mail; //don't forget to call $mail->send()
} catch(Exception $e) {
	return ErrMsg('Email error: '. $mail->ErrorInfo);
}} //StartEmail


//---------------------------------------------------------------------------
//EmailUser - Send an email to a user (order confirmation, refill reminder, password reset)
//NOTE: User emails should always include logo. Add the following:
//<a href="$g_siteURL">
//  <img src="cid:logo" alt="$g_siteName" align="right" width=100 height=100 hspace=10 vspace=5>
//</a>
function EmailUser($toEmail, $toName, $subj, $msg) {
try {
	$mail = StartEmail($toEmail, $toName, $subj, $msg);
	if($mail === false)  return false;
	if(strpos($msg, 'cid:logo') !== false) //attach inline logo
		$mail->addEmbeddedImage($_SERVER['DOCUMENT_ROOT'] . EMAIL_LOGO, 'logo');

	//add BCC to Dev
	$aBoss = DB::Run('SELECT email, CONCAT(first_name,\' \',last_name) AS name FROM users '.
				'WHERE flags >= '. USER_FLAGS['Dev'])->fetchAll(PDO::FETCH_KEY_PAIR);
	foreach($aBoss as $bossEmail=>$bossName)
		$mail->addBCC($bossEmail, $bossName);

	$mail->send();
	return true;
} catch(Exception $e) {
	return ErrMsg('EmailUser error: '. $mail->ErrorInfo);
}} //EmailUser


//---------------------------------------------------------------------------
//EmailContactUs - Send Contact Us email to all bosses
function EmailContactUs($replyEmail, $replyName, $subj, $msg) {
try {
	//email to all employees Cashier or above
	$aBoss = DB::Run('SELECT email, CONCAT(first_name,\' \',last_name) AS name FROM users '.
				'WHERE flags >= '. USER_FLAGS['Cashier'])->fetchAll(PDO::FETCH_KEY_PAIR);
	foreach($aBoss as $bossEmail=>$bossName) {
		$mail = StartEmail($bossEmail, $bossName, $subj, $msg);
		if($mail !== false) {
			$mail->addReplyTo($replyEmail, $replyName);
			$mail->send();
		}
	}
} catch(Exception $e) {
	ErrMsg('Contact Us error: '. $mail->ErrorInfo);
}} //EmailContactUs


//---------------------------------------------------------------------------
//EmailBoss - Send an email to all bosses
// Does NOT automatically include Userinfo
function EmailBoss($minLvl, $subj, $msg) {
try {
	global $g_siteName;
	$subj = $g_siteName .' - '. $subj;
	$aBoss = DB::Run('SELECT email, CONCAT(first_name,\' \',last_name) AS name FROM users '.
				'WHERE flags >= '. USER_FLAGS[$minLvl])->fetchAll(PDO::FETCH_KEY_PAIR);
	foreach($aBoss as $bossEmail=>$bossName) {
		$mail = StartEmail($bossEmail, $bossName, $subj, $msg);
		if($mail !== false)  $mail->send();
	}
} catch(Exception $e) {
	ErrMsg('EmailBoss error: '. $mail->ErrorInfo);
}} //EmailBoss


//---------------------------------------------------------------------------
//EmailAdmin - ErrMsg, website throttle, suspicious activity, new user, etc.
//Appends UserInfo of current user
function EmailAdmin($subj, $msg) {
	EmailBoss('Admin', $subj, $msg ."<br>\n". (IsBoss('Dev') ? '' : UserInfo()));
} //EmailAdmin


//---------------------------------------------------------------------------
//UserInfo - fetch user info into a string for inclusion in an Admin email
//RECEIVES: $id = user identifier:
// 0       = default = current user = $_SESSION['uid']
// <uid>   = user ID;  i.e. DB::Run('SELECT * FROM users WHERE user_id=?')
// <email> = user's email = DB::Run('SELECT * FROM users WHERE user_id=?')
// user[]  = pre-fetched user account
function UserInfo($id=0) {
	global $g_siteURL, $g_siteName;
	//fetch user information
	$info = '';
	if($id === 0  &&  IsSignedIn())  $id = $_SESSION['uid'];
	if((is_int($id)  &&  $id > 0)  ||  (is_string($id)  &&  ctype_digit($id))) //UserInfo(<uid>)
		$user = DB::Run('SELECT * FROM users WHERE user_id=?', [$id])->fetch();
	else if(is_string($id)  &&  filter_var($id, FILTER_VALIDATE_EMAIL)) //UserInfo(<email>)
		$user = DB::Run('SELECT * FROM users WHERE email=?', [$id])->fetch();
	else
		$user = $id; //UserInfo($user)
	if(!$user  ||  !isset($user['email']))
		$info = 'User not signed in.';
	else {
		$flags = sprintf("0x%04X", $user['flags']) .' = '. FlagList($user['flags']);
		$flags .= $user['notify'] ? ', Notify' : '';
		$flags .= $user['autologin'] ? ', Remember' : '';

		//user info
		$name = $user['first_name'] .' '. $user['last_name'];
		$info = <<<INFO
<pre>User #<a href="$g_siteURL/account.php?{$user['user_id']}">{$user['user_id']}</a>: $name
<a href="mailto:$name%3c{$user['email']}%3e?subject=$g_siteName">{$user['email']}</a>
Registered: {$user['created']}
Last visit: {$user['visited']}
Access: $flags\n
INFO;
		if(!empty($user['company']))   $info .= "{$user['company']}\n";
		$info .= $user['street'] ."\n";
		if(!empty($user['street2']))   $info .= "{$user['street2']}\n";
		$info .= "{$user['city']}, {$user['state']} {$user['zip']} {$user['country']}\n";
		if(!empty($user['phone']))     $info .= "{$user['phone']}\n";
		if(!empty($user['phone2']))    $info .= "{$user['phone2']}\n";
		if(!empty($user['remarks']))   $info .= "remarks: {$user['remarks']}\n";
		if(!empty($user['notes']))     $info .= "Internal notes: {$user['notes']}\n";
		$info .= "</pre>\n";
	}

	//add server info
	if(!empty($_SERVER['REQUST_URI'])) { //these are empty during cron job
		$info .= "<pre>\n"
		.'REQUEST_URI: '. ($_SERVER['REQUEST_URI'] ?? '') ."\n"
		.'REMOTE_ADDR: '. ($_SERVER['REMOTE_ADDR'] ?? '') .' : '. ($_SERVER['REMOTE_PORT'] ?? '') ."\n"
		.'HTTP_USER_AGENT: '. ($_SERVER['HTTP_USER_AGENT'] ?? '') ."\n"
		.'HTTP_COOKIE: '. ($_SERVER['HTTP_COOKIE'] ?? '') ."\n"
		.'HTTP_REFERER: '. ($_SERVER['HTTP_REFERER'] ?? '') ."\n"
		."</pre>\n";
	}
	return $info;
} //UserInfo
