<?php //Copyright (c) 2022 Gary Strawn, All rights reserved
// Site Header i.e.:
//  <html>
//  <head> ... </head>
//  <body>
//    <header> ... </header>
//    <noscript></noscript>
//      ... remainder of website here ...
//  see also footer.php

if(count(get_included_files())==1)  exit;  //direct access not permitted
require_once "{$_SERVER['DOCUMENT_ROOT']}/inc/common.php";

AutoLogin(); //attempt to set session variables

//log out
if(isset($_GET['logout'])) {
	setcookie(REMEMBER_ME_COOKIE, '', time()-3600); //delete "Remember Me" cookie
	setcookie(session_name(), '', time()-3600); //delete session cookie
	session_destroy();
	header('Location: /'); //redirect to home page without ?logout
	exit;
}

ob_start();  //cache HTML output, allows ob_end_clean for HTTP redirect
?><!-- Copyright © 2021-<?=date('Y')?> Gary Strawn, All rights reserved -->
<!DOCTYPE html>
<html lang="en-US">
<head>
  <title><?=$g_siteName . ($g_pageTitle ? " - $g_pageTitle" : '')?></title>
  <meta charset="UTF-8" />
  <meta name="copyright" content="Gary Strawn" />
  <meta name="description" content="<?=$g_pageDesc?>" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta property="og:type" content="website" />
  <meta property="og:url" content="<?=$g_pageURL?>" />
  <meta property="og:image" content="<?=$g_pageURL.$g_pageImage?>" />
  <meta property="og:title" content="<?=$g_pageTitle?>" />
  <meta property="og:site_name" content="<?=$g_siteName?>" />
  <meta property="og:description" content="<?=$g_pageDesc?>" />
<?php
if(isset($g_redirectURL)) {
	if(!isset($g_redirectDelay))  $g_redirectDelay = 0;
	echo "  <meta http-equiv=\"refresh\" content=\"$g_redirectDelay; url=$g_redirectURL\" />\n";
}
?>
  <link rel="icon" href="/favicon.ico" />
  <link rel="stylesheet" href="/inc/normalize.css" />
  <?= !isset($g_noHdrFtr) ? '<link rel="stylesheet" href="/inc/common.css" />'."\n" : ''; ?>
  <?= file_exists('index.css') ? '<link rel="stylesheet" href="index.css" />'."\n" : ''; ?>
  <?= !isset($g_noHdrFtr) ? '<script src="/inc/common.js" defer></script>'."\n" : ''; ?>
  <?= file_exists('index.js') ? '<script src="index.js" defer></script>'."\n" : ''; ?>
</head>

<body><?php if(!isset($g_noHdrFtr)) { ?>
<header id="siteHdr">
  <a href="/">
    <img src="/img/hdrlogo.png" id="hdrLogo">
    <h1 id="hdrName" class="wide_only"><?=$g_siteName?></h1>
  </a>
<?php
if(!IsSignedIn()) { //if not signed in
	//Sign in
	$loginClick = "return OpenLogin()";
	$loginTxt = 'Sign&nbsp;In';
} else {
	if($_SERVER['PHP_SELF'] === '/account.php') {
		//Sign out
		$loginClick = "location='/?logout'";
		$loginTxt = 'Sign&nbspOut';
	} else {
		//Howdy (go to account page)
		$loginClick = "location='/account.php'";
		$loginTxt = 'Howdy '. $_SESSION['name'];
	}
}
?>
  <button id="hdrLogin" class="blueBtn" onclick="<?=$loginClick?>"><?=$loginTxt?></button>
<?php //hidden Boss menu (invisible and requires double click)
	if(IsBoss())
		echo '    <img src="/img/hdrlogo.png" id="hdrDbl" ondblclick="location=/boss/">'."\n";
?>
</header><?php } ?>

<noscript>
<p>NOTE: Your browser has Javascript disabled. This website contains many interactive elements that will not function properly unless you <a href="http://www.enable-javascript.com/" rel="external" target="_blank">enable Javascript</a>. Feel free to continue without Javascript, just don't expect everything to function properly or make sense.</p>
</noscript>
