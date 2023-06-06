<?php //Copyright (c) 2022 Gary Strawn, All rights reserved
if(count(get_included_files())==1)  exit;  //direct access not permitted
require_once "{$_SERVER['DOCUMENT_ROOT']}/inc/sql.php";
require_once "{$_SERVER['DOCUMENT_ROOT']}/inc/email.php";

//make sure session is started
if(session_id() === '') {
	session_name('ThunkonautSession');
	session_set_cookie_params([
		'path' => '/',      //all directories
		'secure' => false,  //false = http or https
		'httponly' => true, //true = not accessible from Javascript
		'samesite' => 'Lax' //Lax = cookies used here but not other sites
	]);
	session_start();
}


//---------------------------------------------------------------------------
//IsLocalHost() - true if running on localhost
function IsLocalHost() { return in_array($_SERVER['SERVER_ADDR'], array('127.0.0.1', '::1')); }


//---------------------------------------------------------------------------
//Global variables - variables (not const) so can be included in strings: ex: "foo=$foo"
//Use $g_siteURL, $g_pagePath, and $g_pageURL instead of $_SERVER[...] to prevent XSS exploits
//  Note: <form action=""> will use current URL
//$_SERVER['PHP_SELF']    = path without GET variables, no domain;  Ex: /path/file.php  No GET
//$_SERVER['REQUEST_URI'] = path with    GET variables, no domain;  Ex: /path/file.php?a=1&b=2
//PHP_SELF & REQUEST_URI might have unwanted XSS scripting so sanitize before displaying:
//$safePath = filter_var($_SERVER['PHP_SELF'], FILTER_SANITIZE_SPECIAL_CHARS);
$g_siteName  = $g_siteName ?? 'Thunkonaut';
$g_siteHost  = 'Thunkonaut' . (IsLocalHost() ? '.test' : '.com');
$g_siteURL   = (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $g_siteHost;
$g_pagePath  = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);  //ex: /dir/subdir/
$g_pageURL   = $g_siteURL . $g_pagePath;  //full URL = https://thunkonaut.com/path/

//these should be pre-set for every page (before header.php)
$g_pageTitle  = $g_pageTitle  ?? ''; //short version - appears in title bar
$g_pageDesc   = $g_pageDesc   ?? $g_pageTitle; //appears in search engine results
$g_pageAuthor = $g_pageAuthor ?? 'Thunk';
$g_pageImage  = $g_pageImage  ?? '/img/thunkonaut.png'; //preview image
if(!isset($g_pubDate)) { //DateTime object
	if(ctype_digit(substr($g_pagePath, 1, 6))) //if this is a blog post ex: 221231
		$g_pubDate = DateTime::createFromFormat('ymd', substr($g_pagePath, 1, 6));
	else //use file modified time
		$g_pubDate = DateTime::createFromFormat('U', filemtime(__FILE__));
}

const REMEMBER_ME_COOKIE = 'Thunkonaut'; //"Remember Me" cookie
//Note: strlen(bin2hex(random_bytes(#))) = # * 2;  i.e. 2 hex digits per byte
const REMEMBER_NUMBYTES = 20; //"Remember Me" unique ID;  bin2hex(random_bytes(REMEMBER_NUMBYTES))
const FORGOT_NUMBYTES = 10; //"forgot password" unique ID;  bin2hex(random_bytes(FORGOT_NUMBYTES))


//---------------------------------------------------------------------------
//User Flags - Control access to discounts and protected areas
//  see also HasFlag() and IsBoss()
const USER_FLAGS = [
	//User bitfields  i.e. checkboxes
	'Default'   => 0x0000, //no special status
	'Banned'    => 0x0001, //reduced privileges (no purchases allowed)
	'Watched'   => 0x0002, //user on probation (admin emailed every user login)
	'Inactive'  => 0x0004, //user is inactive  (admin emailed at next login)
	'Premium'   => 0x0008, //premium pricing (discount / extra fee)
	'Friend'    => 0x0010, //friend discount
	'Family'    => 0x0020, //family discount
	'Member'    => 0x0040, //loyalty discount
	'Wholesale' => 0x0080, //wholesale discount
	'Thunker'   => 0x0100, //has dungeon access
	'Book'      => 0x0200, //invited to read my book
	'Coder'     => 0x0400, //computer programmer
	'Monkey'    => 0x0800, //likes bananas

	//Boss levels (NOT FLAGS):  Intern < Cashier < Admin
	'Intern'    => 0x1000, //view new orders only;  no notifications, cannot mark as shipped
	'Shipper'   => 0x2000, //View/ship orders, notified of new orders
	'Cashier'   => 0x3000, //Edit orders, view users
	'Manager'   => 0x4000, //Edit orders, users, prices, etc.
	'Admin'     => 0x5000, //Receive website errors, throttles, etc.
	'Dev'       => 0xF000  //Developer, debug info   0xF000=61440
]; //USER_FLAGS

const USER_FLAG_DESC = [
	//USER_FLAG    Description seen by user                     Checkbox tooltip seen by manager
	'Default'   => ['Valued customer',                          'No special status'],
	'Banned'    => ['Your account is restricted.',              'Reduced privileges (no purchases allowed)'],
	'Watched'   => ['Your business is important to us.',        'User on probation (notify upon login)'],
	'Inactive'  => ['Account inactive.',                        'User is inactive (notify upon login)'], 
	'Premium'   => ['You are a premium member.',                'Premium pricing (extra fee)'],
	'Friend'    => ['You are a friend.',                        'Friend discount'],
	'Family'    => ['You are family.',                          'Family discount'],
	'Member'    => ['You are a member.',                        'Loyalty discount'],
	'Wholesale' => ['You are qualified for wholesale pricing.', 'Wholesale discount'],
	'Thunker'   => ['You may visit the Dungeon of Thunk.',      'Access dungeon'],
	'Book'      => ['You are a book reviewer.',                 'Book reviewer'],
	'Coder'     => ['You are a coder.',                         'Computer programmer'],
	'Monkey'    => ['You are a monkey.',                        'Likes bananas'],

	'Intern'    => ['Intern: Help package new orders',          'Cannot mark new orders as shipped'],
	'Shipper'   => ['Shipper: View and ship new orders',        'Receive new orders notifications'],
	'Cashier'   => ['Cashier: Create & modify orders',          'Edit orders, view users'],
	'Manager'   => ['Manager: Edit orders, users, prices',      'Edit orders, users, prices, etc.'],
	'Admin'     => ['Admin: Monitor/fix the website',           'Manage website errors, DB, etc.'],
	'Dev'       => ['You are a developer.',                     'Developer, debug info']
];


//---------------------------------------------------------------------------
//ErrMsg - ***Dev only*** error messages; not shown on live server
function ErrMsg($msg) {
	global $g_emailError;
	if(IsBoss('Dev') || IsLocalHost()) //debug mode: display on screen
		echo "<div class=\"errBox\">$msg</div>\n";
	else //release mode: send an email
		EmailAdmin("*** SERVER ERROR ***", $msg);
	return false; //always returns false
} //ErrMsg

//ErrorHandler - overrides default old-style error handler
set_error_handler('ErrorHandler');
function ErrorHandler($errno, $errstr, $errfile='', $errline='') {
	$eol = (IsBoss('Dev') || IsLocalHost()) ? "<br>\n" : "\r\n";
	$msg = $errstr . $eol;
	$msg .= 'File: '. $_SERVER['PHP_SELF'] . $eol;
	if($errfile !== ''  ||  $errline !== '')
		$msg .= "Error $errno at $errline:$errfile". $eol;
	return ErrMsg('PHPError: '. $msg);
} //ErrorHandler

//ExceptionHandler - catch all uncaught exceptions
set_exception_handler('ExceptionHandler');
function ExceptionHandler($e) { ErrMsg('Exception: '. $e->getMessage()); }

ini_set('log_errors', 1); //always log errors
ini_set('display_errors', (IsBoss('Dev') || IsLocalHost()) ? 1 : 0); //hide errors from users


//---------------------------------------------------------------------------
//PostAccessOnly - if not a POST request, redirect to home page
//Should be first line of all files in /server/*.php
function PostAccessOnly() {
	if($_SERVER['REQUEST_METHOD'] === 'POST')  return; //all is good

	$subj = 'Suspicious attempt to access POST-only file: '.$_SERVER['PHP_SELF'];
	$email = <<<EMAIL
<p>Someone attempted to directly access AJAX file {$_SERVER['PHP_SELF']}. These files are for public use but only via Javascript XMLHttpRequest (i.e. POST request). This file has been accessed directly.</p>

<p>If this happens once or twice, no big deal, it's probably an honest mistake or web crawler. However, if it continues, especially for different files, it may be a hacker probing the website. This is a throttled event so multiple access attemps will trigger the website throttle, slowing down each subsequent access attempt. The throttle should not affect normal website usage.</p>
EMAIL;
	EmailAdmin($subj, "$subj<br>\n$email");
	Throttle($subj);

	while(ob_get_level())  ob_end_clean(); //make sure no HTTP has been sent yet
	header('Location: /'); //redirect to home page
	exit;
} //PostAccessOnly


//---------------------------------------------------------------------------
//LogHit - log website access
function LogHit($msg=NULL) {
	$uri = filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_SPECIAL_CHARS);
	if(IsBoss('Dev')) {
		//Only one entry for Dev
		if(DB::Run('SELECT EXISTS(SELECT 1 FROM hits WHERE user_id=?)', 
				[$_SESSION['uid']])->fetchColumn()) {
			DB::Run('UPDATE hits SET hit_time=NOW(), page=?, msg=? WHERE user_id=?', 
				[$uri, $msg, $_SESSION['uid']]);
			return;
		}
	}
	DB::Run('INSERT INTO hits (hit_time, ip, user_id, page, msg) VALUES 
			(NOW(), INET6_ATON(?), ?, ?, ?)', 
			[$_SERVER['REMOTE_ADDR'], $_SESSION['uid']??0, $uri, $msg]);
} //LogHit


//---------------------------------------------------------------------------
//CalcThrottleDelay - calculate current throttle delay
function CalcThrottleDelay() {
	$delay = 1;
	$prevTime = time();
	$aHits = DB::Run('SELECT hit_time FROM throttle ORDER BY hit_time DESC LIMIT 6')->fetchAll(PDO::FETCH_COLUMN);
	foreach($aHits as $hit) {
		$currTime = strtotime($hit);
		if($prevTime - $currTime >= 5*60)  return $delay; //any gap of 5 minutes resets throttle
		$prevTime = $currTime;
		$delay *= 2;
	}
	return ($delay > 60*2)  ?  60*2  :  $delay; //2 minutes max delay
} //CalcThrottleDelay


//---------------------------------------------------------------------------
//Throttle - Slow down the website for certain events, Ex: failed logins
//  This function is the only thing that slows down, no matter why it is called.
function Throttle($msg) {
	global $g_siteURL;
	$delay = CalcThrottleDelay();
	if($delay === 4) { //4 seconds = third time login;  notify Admin
		$email = <<<EMAIL
<p>The website throttle has been triggered. If this happens once or twice, no big deal, it's probably a forgetful user trying different passwords. However, if the throttle continues to be triggered, it may be a hacker. The website responds by slowing down its reponse time, doubling the response delay after every trigger:</p>
<pre>
	1st trigger:  1 second delay
	2nd trigger:  2 second delay
	3rd trigger:  4 second delay
	4th trigger:  8 second delay
	...
	8th trigger:  2 minutes max
</pre>
<p>This email was sent on the third consecutive throttle trigger. The fourth trigger will incur an eight second delay before the website replies to that user. This continues up to a maximum delay of two minutes.</p>

<p>Throttling is site-wide, it affects all users, all IP addresses, all throttle triggers. For example, accessing server-only files will also slow down failed login attempts. Only triggers points are affected, the throttle should not affect normal website usage. Throttles reset after five minutes of no triggers.</p>

<p>No further action is necessary unless throttling continues, then it may be necessary to block the IP(s) causing the triggers.</p>

<p>Check current throttle status: <a href="$g_siteURL/boss/?hits">$g_siteURL/boss/?hits</a></p>
EMAIL;
		EmailAdmin('WARNING! Website throttle triggered', "$msg<br>\n$email");
	}

	//store failed login attempt
	DB::Run('INSERT INTO throttle (hit_time, ip, delay, msg) VALUES 
			(NOW(), INET6_ATON(?), ?, ?)', [$_SERVER['REMOTE_ADDR'], $delay, $msg]);

	//delay
	session_write_close(); //release session lock so other users aren't stalled
	sleep($delay); //make this user wait
	ob_end_clean(); //no HTML should have been sent, but make sure
	session_start(); //restart session
} //Throttle


//---------------------------------------------------------------------------
//DateHTML - format date as HTML <time>
//  dateTime - date/time string or timestamp to be displayed
//  isTimeToo - true = show date and time, false (default) = only date
function DateHTML($dateTime, $isTimeToo=false) {
	$t = ctype_digit($dateTime) ? $dateTime : strtotime($dateTime);
	$machine = date('Y-m-d H:i:s', $t); //2021-03-10 17:16:18
	$human = date('D j M Y'. ($isTimeToo ? ', g:ia' : ''), $t); //Sat 10 Mar 2021, 5:16pm
	return "<time datetime=\"$machine\">$human</time>";
} //DateHTML


//---------------------------------------------------------------------------
//AddQuery - Appends GET parameter to URL/URI
// Note: does NOT check for duplicate parameters  Ex: AddQuery('/?a=1', 'a=2') => '/?a=1&a=2'
function AddQuery($keyVal, $url=NULL) {
	if(!$url)  $url = $_SERVER['REQUEST_URI'];
	return $url . (parse_url($url, PHP_URL_QUERY) ? '&' : '?') . $keyVal;
} //AddQuery


//---------------------------------------------------------------------------
//RemoveQuery - Returns URL/URI without specified GET parameter
//  RemoveQuery('http://example.com?a', 'a') => http://example.com
//  RemoveQuery('http://example.com?a=1', 'a') => http:/example.com
//  RemoveQuery('http://example.com?a=1&b=2', 'a') => http://example.com?b=2
function RemoveQuery($key, $url=NULL) {
	if(!$url)  $url = $_SERVER['REQUEST_URI'];
	$aURL = parse_url($url);
	if(!$aURL['query'])  return $url; //no parameters found

	$aQuery = [];
	parse_str($aURL['query'], $aQuery);
	if(!isset($aQuery[$key]))  return $url; //key not found

	unset($aQuery[$key]);
	$aURL['query'] = http_build_query($aQuery); //new query string (without URL)

	$out = preg_replace('/(.*)\?(.*)/', '$1?'.$aURL['query'], $url); //add original URL
	return rtrim($out, '?='); //if empty(query), return URL
} //RemoveQuery


//---------------------------------------------------------------------------
//-- Login

//---------------------------------------------------------------------------
//DBLogin - database login, sets session variables and "Remember Me" cookie
//NOTE: only called from AutoLogin (header.php), login.php, and new user registration
function DBLogin($user) {
	$_SESSION['uid']   = (int)$user['user_id'];
	$_SESSION['flags'] = (int)$user['flags'];
	$_SESSION['name']  = $user['first_name'];

	if($user['autologin']) { //remember me?
		//generate unique autologin id; user's cookie gets unhashed id, database gets hashed version
		//make sure autologin is unique to entire database
		$isFinite = 9999; //prevent infinite loop (if DB fails)
		do { $user['autologin'] = bin2hex(random_bytes(REMEMBER_NUMBYTES));  $isFinite--;
		} while(DB::Run('SELECT EXISTS(SELECT 1 FROM users WHERE autologin=? LIMIT 1)', 
					[hash('sha256', $user['autologin'])])->fetchColumn()  &&  $isFinite);
		if(!$isFinite) {
			//"Remember Me" failed
			ErrMsg('Unable to create a unique autologin ID. This should be impossible.');
			DB::Run('UPDATE users SET autologin=NULL WHERE user_id=?', [$_SESSION['uid']]);
			setcookie(REMEMBER_ME_COOKIE, '', time()-3600); //delete "Remember Me" cookie
			$user['autologin'] = 0;
		} else {
			//set "Remember Me" cookie
			$opt = [
				'expires'=>strtotime('+90 days'),
				'path' => '/',      //all directories
				'secure' => false,  //false = http or https
				'httponly' => true, //true = not accessible from Javascript
				'samesite' => 'Lax' //Lax = cookies used here but not other sites
			];
			if(!setcookie(REMEMBER_ME_COOKIE, $user['autologin'], $opt))
				ErrMsg('Unable to create "Remember Me" cookie');
		}
	} else {
		//user unchecked "Remember Me", make sure cookie is deleted
		setcookie(REMEMBER_ME_COOKIE, '', time()-3600); //delete "Remember Me" cookie
	}

	//update user's visitied time and autologin
	if(!DB::Run('UPDATE users SET visited=NOW(), autologin=? WHERE user_id=?', 
					[$user['autologin'] ? hash('sha256', $user['autologin']) : NULL, $_SESSION['uid']]))
		ErrMsg('Unable to update autologin ID');

	//notify admin that user has logged in
	if(HasFlag('Banned'))   EmailAdmin('Banned user login', 'This user is banned from making purchases.');
	if(HasFlag('Watched'))  EmailAdmin('Watched user login', 'This user is on the watch list.');
	if(HasFlag('Inactive')) {
		EmailAdmin('Inactive user login', 'This user was previously marked as inactive. No big deal, the account is now active again.');
		$_SESSION['flags'] = ($_SESSION['flags'] & ~(USER_FLAGS['Inactive'])); //user is no longer inactive
		DB::Run('UPDATE users SET flags=? WHERE user_id=?', [$_SESSION['flags'], $_SESSION['uid']]);
	}
	if(IsBoss('Dev')  &&  $_SESSION['uid'] !== 10)
		EmailAdmin('Unknown Dev login', 'An unauthorized account logged in with Dev access. This is either a bug or a hacker. Immediate attention is paramount.');
	return true;
} //DBLogin


//---------------------------------------------------------------------------
//AutoLogin - login using REMEMBER_ME_COOKIE
// Called only from header.php, before any HTML output
function AutoLogin() {
	if(IsSignedIn()) return true; //already logged in
	if( !isset($_COOKIE[REMEMBER_ME_COOKIE]) //no cookie found
	 || !ctype_xdigit($_COOKIE[REMEMBER_ME_COOKIE]) //see bin2hex(random_bytes()) in DBLogin()
	 || strlen($_COOKIE[REMEMBER_ME_COOKIE]) !== REMEMBER_NUMBYTES*2) //2 hex digits per byte
		return false; //invalid cookie

	//get user info from database
	$user = DB::Run('SELECT user_id, autologin, flags, first_name FROM users WHERE autologin=?', 
					[hash('sha256', $_COOKIE[REMEMBER_ME_COOKIE])])->fetch();
	if(!$user) {
		Throttle("Failed autologin, bad/fake \"Remember me\" cookie: {$_COOKIE[REMEMBER_ME_COOKIE]}");
		setcookie(REMEMBER_ME_COOKIE, '', time()-3600); //delete "Remember me" cookie
		return false;
	}
	DBLogin($user);
	return true;
} //AutoLogin


//---------------------------------------------------------------------------
//IsSignedIn - True if user is currently signed in
function IsSignedIn()  { return isset($_SESSION['uid']); }


//---------------------------------------------------------------------------
//HasFlag - true if user has specified flag
//$flag - flag to test for (ex: 'Wholesale')
//$curr - user's current flag (default = $_SESSION['flags'])
//Ex: HasFlag('Friend') || HasFlag('Family')
function HasFlag($flag, $curr=NULL) {
	if($curr === NULL)  $curr = $_SESSION['flags'] ?? 0;
	if(USER_FLAGS[$flag] < USER_FLAGS['Intern'])
		return ($flag === 'Default') ? ($curr === USER_FLAGS['Default']) : ($curr & USER_FLAGS[$flag]);
	else
		return ($curr & 0xF000) === USER_FLAGS[$flag];
} //HasFlag


//---------------------------------------------------------------------------
//IsBoss - true if user has specified boss level or higher
//Boss levels:  Intern < Cashier < Admin
function IsBoss($reqd='Intern', $curr=NULL) {
	if($curr === NULL)  $curr = $_SESSION['flags'] ?? 0;
	return $curr >= USER_FLAGS[$reqd];
} //IsBoss


//---------------------------------------------------------------------------
//FlagList - convert bitfield to text string
function FlagList($curr=NULL) {
	if($curr === NULL)  $_SESSION['flags'] ?? 0;
	$flags = '';

	//show boss flag first (can only have one)
	if(($curr & 0xF000))
		$flags = '***'. array_search(($curr & 0xF000), USER_FLAGS) .'*** ';

	//show regular flags
	foreach(USER_FLAGS as $key=>$bit) {
		if($bit >= USER_FLAGS['Intern'])  break; //done
		if(($curr & $bit))  $flags .= $key .', ';
	}
	$flags = rtrim($flags, ', '); //remove trailing ', '
	return (empty($flags) ? 'Default' : $flags);
} //FlagList


//-------------------------------------------------------------------
//MakeMenu - build a "Table of Contents" menu
// aMenu - array(url, bold, description, array(submenu))
//   Note: top-level array is menu header <h1> and must contain all 4 elements
// iNext - optional "Next >>" button
/* Example menu: 
$aMenu = //url, bold, title, submenu
	array("rootUrl", "", "Menu title", array(
		array("url1", "One", "Description of item 1.0"),
		array("url2", "Two", "Description of item 2.0"),
			array("url21", "TwoA", "Description of item 2.1"),
			array("url22", "TwoB", "Description of item 2.2"),
		array("url3", "Three", "Description of item 3.0")));
Creates:
  Menu Title
  1) One: Description of item 1.0
  2) Two: Description of item 2.0
    1) TwoA: Description of item 2.1
    2) TwoB: Description of item 2.2
  3) Three: Description of item 3.0
*/
$g_isMenuCurrPage = false;  //true if current page, so next page shows "<< Next" button
function MakeMenu($aMenu) {
	global $g_isMenuCurrPage;
	if(!isset($aMenu)  ||  count($aMenu) < 4)  return;
	$url = $aMenu[0] .'/';
	$g_isMenuCurrPage = ($url == $_SERVER["REQUEST_URI"]);
	$txt =  "<nav class=\"contents\"><div>\n";
	$txt .= "  <h2>";
	if(!$g_isMenuCurrPage)  $txt .= "<a href=\"$url\">";
	if(!empty($aMenu[1]))   $txt .= "<b>{$aMenu[1]}</b>";
	if(!empty($aMenu[1]) && !empty($aMenu[2]))  $txt .= ': ';
	if(!empty($aMenu[2]))   $txt .= $aMenu[2];
	if(!$g_isMenuCurrPage)  $txt .= "</a>";
	$txt .= "</h2>\n";
	$txt .= MakeSubMenu($aMenu[3], $url, '  ');
	$txt .= "</div></nav>\n";
	echo $txt;
} //MakeMenu

//-------------------------------------------------------------------
//MakeSubMenu - called recursively from MakeMenu
function MakeSubMenu($aMenu, $urlRoot, $indent) {
	global $g_isMenuCurrPage;
	if(empty($aMenu)  ||  empty($aMenu[0])  ||  empty($aMenu[0][0]))  return;
	$txt = $indent ."<ol>\n";
	for($i = 0;  $i < count($aMenu);  $i++) {
		$url = $urlRoot . $aMenu[$i][0] .(($aMenu[$i][0][0] == '?') ? '' : '/');
		$isNext = $g_isMenuCurrPage;  //only true if PREVIOUS page was current page
		$g_isMenuCurrPage = ($url == $_SERVER["REQUEST_URI"]);  //true if at current page
		$txt .= $indent .'  <li>';
		if(!$g_isMenuCurrPage)     $txt .= "<a href=\"$url\">";
		if(!empty($aMenu[$i][1]))  $txt .= "<b>{$aMenu[$i][1]}</b>";
		if(!empty($aMenu[$i][1]) && !empty($aMenu[$i][2]))  $txt .= ': ';
		if(!empty($aMenu[$i][2]))  $txt .= $aMenu[$i][2];
		if($isNext)                $txt .= '<button class="blueBtn">Next &rarr;</button>';
		if(!$g_isMenuCurrPage)     $txt .= "</a>";
		if(isset($aMenu[$i][3])) {
			$txt .= "\n" . MakeSubmenu($aMenu[$i][3], $url, $indent.'  ');
			$txt .= $indent.'  ';
		}
		$txt .= "</li>\n";
	}
	$txt .= $indent ."</ol>\n";
	return $txt;
} //MakeSubMenu


// BadBot trap
//https://perishablepress.com/blackhole-bad-bots/
