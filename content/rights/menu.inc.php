<?php
//MakeMenu($g_aMenuFreedom);
$g_aMenuFreedom = //url, bold, title, submenu
	array("/content/rights", "", "Privacy, Security, Freedom", array(
		array("cryptography", "Privacy", "Cryptography? But I have nothing to hide."),
		array("bitcoin", "Security", "Bitcoin? Nah, our economy is fine."),
		array("cicada", "Freedom", "Who is Cicada 3301?", array(
			array("01-hello",  "Hello",  "A mystery on 4chan"),
			array("02-caesar", "Caesar", "Original image from 2012"),
			array("03-decoy",  "Decoy",  "The art of steganography"),
			array("04-reddit", "Reddit", "Was King Arthur a Mayan?"),
			array("05-global", "Global", "Entering the real world"),
			array("06-again",  "Again",  "Another chance in 2013"),
			array("07-truth",  "Truth",  "Some philosophies emerge")))));
?>
