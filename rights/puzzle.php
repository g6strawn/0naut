<?php //process Cicada Puzzle;  must NOT be included anywhere
require_once "{$_SERVER['DOCUMENT_ROOT']}/inc/common.php";  //globals, IsLocalHost, ErrMsg, AutoLogin, IsBoss, etc.
require_once "{$_SERVER['DOCUMENT_ROOT']}/inc/sql.php";     //class DB
require_once "{$_SERVER['DOCUMENT_ROOT']}/inc/email.php";   //EmailUser
require_once "{$_SERVER['DOCUMENT_ROOT']}/content/rights/puzzle.inc.php";  //Cicada puzzle
ob_end_clean();  //ignore HTTP from puzzle.inc.php

/*  Note to Cicada 3301:
Vigenere key = (3 19 59 3761)2^2^2  = 12,648,243 = 0xC0FF33
I saw, I studied, agreed, 
disagreed, learned, found my 
own way, wrote my own truth. 
Privacy, security, and 
freedom are sacred. 
Education erodes tyranny. 
Thank you for your efforts.
*/

//Note: levels 4-9 are decoys
$g_aHints = array(
array( //Level 0: Email, page 7
//SETUP: Game not yet started.
//ACTION: enter email address.
//RESPONSE: "An email has been sent."
//[0] = text version, [1] = html version
<<<EOT
Welcome Intrepid Explorer, the adventure continues!
All is not as it once was, the rabbit hole awaits.
To achieve enlightenment, look before you leap.
Patience is a virtue.
A hint: Hints are beguiling.
The first hint is free: $g_siteURL/content/rights/cicada/01-hello/#hint
EOT
,
<<<EOT
Welcome Intrepid Explorer, the adventure continues!<br>
All is not as it once was, the rabbit hole awaits.<br>
To achieve enlightenment, look before you leap.<br>
Patience is a virtue.<br>
A hint: <a href="$g_siteURL/content/rights/cicada/01-hello/#hint" title="The first hint is free.">Hints</a> are beguiling.
EOT
), array( //Level 1: Believe - page 1
//ACTION 1: Hover over hole for 100 seconds.
//RESULT 1: Hidden text slowly reveals "BELIEVE".
//ACTION 2: Click on "I Want To Believe" poster.
//RESULT 2: Poster changed to "Sophomores want to believe"
//RESULT 3: Sophomore definition final sentence changed to: Not all sophomores are foolish but the password is.
'Following is easy. Self-reliance is better.',
'That "rabbit" hole looks scary, maybe you should wait to see what\'s in there.',
'A mouse will lure out what lies within. Be patient.',
'Hover the mouse over <a href="/content/rights/cicada/01-hello/#hole" title="Go here then wait.">the hole</a> then wait a minute.',
'Click on the "<a href="/content/rights/cicada/01-hello/#believe" title="Go here then click">I WANT TO BELIEVE</a>" poster.'
), array( //Level 2: MOROS - page 2
//ACTION: Enter "moros" into password box
//RESULT: Password box text: "Caesar says..."
//  Caesar quote: Nero Caesar says "Oc` _p^f dn ijo `igdbco`i`_)"
'Sophomores know the password isn\'t foolish.',
'Some sophomores are Greek.',
'Greek for <a href="/content/rights/cicada/01-hello/#dfn" title="Read the definition">foolish</a> is <a href="/content/rights/cicada/02-caesar/#hidePswd" title="Enter the password">moros</a>.'
), array( //Level 3: Caesar - page 2
//ACTION: Decipher Caesar quote (copy/paste, shift=5)
//RESULT: Deciphers to: The duck is not enlightened.
'Did Caesar speak in ASCII?',
'Nero was the fifth Caesar, not the negative fifth.',
'<a href="/content/rights/cicada/02-caesar/#caesarsays">Nero Caesar</a> can be <a href="/content/rights/cicada/02-caesar/#caesar" title="Type or paste into the first box">deciphered</a> with a shift of 5.'
), array( //decoy Level 4: Tarth - page 3
//ACTION 1: Highlight duck image.
//RESULT 1: Duck image reveals: <CONTROL> the Tarth
//ACTION 2: <ctrl+click> Tarth.
//RESULT 2: Tarth says: Cletus no like cryptography. Cletus like beer.
'White on white is meretricious.',
'To enlighten <a href="/content/rights/cicada/03-decoy/#duck" title="Image of duck decoy">the duck</a>, select everything with &lt;CTRL+A&gt;.',
'Uncontrolled Tarth don\'t share secrets.',
'For bad advice, hold &lt;CTRL&gt; key then click on <a href="/content/rights/cicada/03-decoy/#tarth" title="Video of Tarth monster">Tarth</a>.'
), array( //decoy Level 5: Beer - Encryption
//ACTION: Click on "Beer!" six times.
//RESULT: Cletus says: Ufcwy'm mywlyn cm W4xsGR0BenrivjDmHFOPI9aP3MJJAbM4
'Cletus don\'t know much about <a href="/content/rights/cryptography/">cryptography</a>.',
'Cletus sure would like some beer.',
'<a href="/content/rights/cryptography/#beer" title="Go here then click on Beer!">Cletus</a> wants six beers.'
), array( //decoy Level 6: Shift - Encryption
//ACTION: Decipher Cletus's words (copy/paste, shift=6)
//RESULT: Deciphers to: Alice's secret is C4dyMX0HktxobpJsNLUVO9gV3SPPGhS4
'Cletus says shifty things when he\'s drunk.',
'Cletus drank six beers.',
'To <a href="/content/rights/cryptography/#caesar" title="Type or paste into the first box">decipher</a> drunk Cletus, shift his letters by six.',
), array( //decoy Level 7: Alice - Encryption
//ACTION: Decipher Alice's secret (copy/paste, level 1)
//RESULT: Bob says: Is the NSA spying on us?
'Bob understands Alice.',
'Help Alice use her private key.',
'<a href="/content/rights/cryptography/#KeyGame" title="Replace Hello World! with Alice\'s secret">Level 1</a>, give Alice her secret and Bob will understand.',
), array( //decoy Level 8: NSA - Encryption
//ACTION: Drag/drop NSA logo onto eye hole
//RESULT: figcaption says: Correct horse doesn't care about 1924 Bitcoin.
'Maybe the NSA will protect your privacy.',
'The NSA is a drag but they have eye drops.',
'Drag the <a href="/content/rights/cryptography/#spyCap" title="Drag and drop the NSA logo">NSA logo</a> over the eye hole.',
), array( //decoy Level 9: Horse - Bitcoin
//ACTION: Toggle 1924. Uncheck all cares (cares = 0). Look at horse.
//RESULT: XKCD horse says: The duck was a decoy.
//  Caption says: Ducks give bad advice.<br>Books give good advice.
'Nobody cares if 2024 horses care.',
'0 cares, 1924.',
'<a href="/content/rights/bitcoin/#switch1924" title="Select 1924">Toggle 1924</a>, have <a href="/content/rights/bitcoin/#Cares" title="Check all the boxes">0 cares</a>, get advice from <a href="/content/rights/bitcoin/#horse" title="Correct horse is correct">correct horse</a>.',
), array( //Level 10: Book - Page 3, Page 4
//ACTION: Decode book code. (unselect Decipher, select Timestamp)
//RESULT: Timestamps decoded to: Vigenere says "Welcome."
'Decode but don\'t decipher.',
'month:day:hour:minute:second:word:letter:length',
'Use the timestamps on the enciphered <a href="/content/rights/cicada/04-reddit/#book" title="Do NOT decipher the Reddit page.">Reddit page</a> to decode the new <a href="/content/rights/cicada/04-reddit/#bookcode" title="The book code has changed.">book code</a>.',
), array( //Level 11: Vig, page 4
//ACTION: Decipher welcome message
//  Welcome message = "Whg qezw pcje ylln veni dgvttxcv ln 30 uhcqqdu. Kutuy!"
//  Problem message = Vigen�re key = "30s"
//RESULT: Decipers to: "The next page will self-destruct in 30 seconds. Hurry!"
'What\'s Vigen�re\'s welcome message?',
'Vigen�re\'s key is the problem.',
'Deciper the <a href="/content/rights/cicada/04-reddit/#welcprob" title="The image is the same but the message has changed">Welcome Message</a> using the <a href="/content/rights/cicada/04-reddit/#welcprob" title="The image is the same but the message has changed">Problem message</a> as the <a href="/content/rights/cicada/04-reddit/#vig" title="Decipher the message requires a key.">Vigen�re key</a>.',
), array( //Level 12: Gadget, page 5
//ACTION: Click on Inspector Gadget
//RESULT: Redirect to "/content/rights/cicada/06-again"
'Stop Inspector Gadget!',
'If at first you don\'t succeed, reload and try again.',
'Reload the page then click on the picture of <a href="/content/rights/cicada/05-global/#gadget" title="Hurry, you only have 30 seconds.">Inspector Gadget</a> before he self-destructs.',
), array( //Level 13: Egypt, page 6
//ACTION: Click on back of Egyptian stele
//RESULT: Redirect to "/content/rights/cicada/07-truth/#runes
'666',
'Touch an Egyptian\'s backside.',
'Click on the back of the <a href="/content/rights/cicada/06-again/#stele" title="A stele, or stela, is a stone gravestone.">Egyption stele</a>.',
), array( //Level 14: Purge, page 7
//ACTION 1: Decipher runes
//RESULT 1: Purge: Almost done. Only pure minds may proceed. To clear your past, close all then start again.
//ACTION 2: Close all browsers then return to page 7
//RESULT 2: Runes changed to "Again?"
'Decipher the runes.',
'Close *all* browsers.',
'Reboot then return to <a href="/content/rights/cicada/07-truth/" title="Decipher the runes">Page 7</a>.',
), array( //Level 15: Remail, page 7
//ACTION: Re-enter email address
//RESULT: Send congratulations email with link "/content/rights/cicada/?"
'Start again.',
'Who are you?',
'Re-enter the same <a href="/content/rights/cicada/07-truth/#start" title="Enter your email address">email</a> you began with.',
	), array( //Level 16: Scores, page 7
//ACTION: Email sent. Click on link with completion id
//RESULT: Show high scores
//[0] = text version, [1] = html version
<<<EOT
Congratulations! You have completed the puzzle. Hopefully 
the journey was educational and maybe even entertaining. 
All that's left to do now is one last step, check your 
final score:
$g_siteURL/content/rights/cicada/07-truth/?cp=*cpRemailID*#high_scores

Hint: To keep it fair, don't give your friends any hints. 
Make them start at the beginning. Ask them about their 
privacy, security and freedom: $g_siteURL/content/rights/
EOT
,
<<<EOT
<p>Congratulations! You have completed the puzzle. Hopefully 
the journey was educational and maybe even entertaining. 
All that's left to do now is one last step, check your 
<a href="$g_siteURL/content/rights/cicada/07-truth/?cp=*cpRemailID*#high_scores" title="High scores">final score</a>.</p>

<p>Hint: To keep it fair, don't give your friends any hints. 
Make them start at the beginning. Ask them about their 
<a href="$g_siteURL/content/rights/">privacy, security and freedom</a>.</p>
EOT
)); //g_aHints

/*
# cicada_puzzle - One entry per solve attempt
#   Also used for Page 6 Test, but only for email address, everything else = defaults
DROP TABLE IF EXISTS cicada_puzzle;
CREATE TABLE cicada_puzzle (
  puzz_id    INT UNSIGNED AUTO_INCREMENT       COMMENT 'one per attempt',
  cookie_id  VARCHAR(256) NOT NULL DEFAULT ''  COMMENT 'sha256(cookie id)',
  prev_id    INT UNSIGNED NOT NULL DEFAULT '0' COMMENT 'previous attempt',
  email      VARCHAR(254) NOT NULL             COMMENT 'anonymous email',
  started    DATETIME     NOT NULL             COMMENT 'start time',
  updated    DATETIME     NOT NULL             COMMENT 'most recent activity',
  level      INT UNSIGNED NOT NULL DEFAULT '0' COMMENT 'most recently completed',
  maxlvl     INT UNSIGNED NOT NULL DEFAULT '0' COMMENT 'highest level completed',
  hints      INT UNSIGNED NOT NULL DEFAULT '0' COMMENT '# hints, total',
  lvlhint    INT UNSIGNED NOT NULL DEFAULT '0' COMMENT '# hints for current level',
  cheats     INT UNSIGNED NOT NULL DEFAULT '0' COMMENT '# cheats; 0=honest',
  remail_id  CHAR(16)     NOT NULL DEFAULT ''  COMMENT 'level 16 Remail id',
  PRIMARY KEY (puzz_id),
  KEY (maxlvl), KEY (hints)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

# cicada_puzzle_times - Timestamp of when a clue was solved
DROP TABLE IF EXISTS cicada_puzzle_times;
CREATE TABLE cicada_puzzle_times (
  id        INT UNSIGNED AUTO_INCREMENT,
  puzz_id   INT UNSIGNED NOT NULL COMMENT 'key into cicada_puzzle',
  time      DATETIME     NOT NULL COMMENT 'completion time',
  level     INT UNSIGNED NOT NULL COMMENT 'level (clue) completed',
  PRIMARY KEY (id),
  KEY (puzz_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

# cicada_test_answers - Answers from Page 6 test
DROP TABLE IF EXISTS cicada_test_answers;
CREATE TABLE cicada_test_answers (
  id        INT UNSIGNED AUTO_INCREMENT,
  puzz_id   INT UNSIGNED NOT NULL COMMENT 'key into cicada_puzzle',
  time      DATETIME     NOT NULL COMMENT 'answered time',
  question  INT UNSIGNED NOT NULL COMMENT 'question 0-18',
  answer    ENUM('true','false','indeterminate','meaningless','self-referential','rule','loop','none',
                 'reflection','fish','neither','both'),
  essay     VARCHAR(1000),
  PRIMARY KEY (id),
  KEY (puzz_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
*/


//------------------------------------------------------------------------
//$_GET['hint'] - called from ShowHint();  retrieve current hint
if(isset($_GET['hint'])) {
	if(!$g_cpID)  exit('Hex dump');  //puzzle not started
	$hint = DB::Run('SELECT lvlhint FROM cicada_puzzle WHERE puzz_id=?', [$g_cpID])->fetchColumn();
	$txt = "g_cpLvl=$g_cpLvl, hint=$hint, num=". count($g_aHints[1]);
	exit($g_aHints[$g_cpLvl][$hint % count($g_aHints[$g_cpLvl])]);
} //if $_GET['hint']


//------------------------------------------------------------------------
//$_GET['hintack'] - called from ShowHint();  acknowledge = advance to next hint
if(isset($_GET['hintack'])) {
	if(!$g_cpID)  exit;  //puzzle not started
	DB::Run('UPDATE cicada_puzzle SET hints=hints+1, lvlhint=lvlhint+1 WHERE puzz_id=?', [$g_cpID]);
	exit;
} //if $_GET['hintack']


//------------------------------------------------------------------------
//$_GET['cp'] - called from javascript CP() when a puzzle clue is completed
if(isset($_GET['cp'])) {
	if(!$g_cpID)  exit;  //puzzle not started

	//advance to next level
	$aLvls = array(0=>'Email', 1=>'Believe', 2=>'MOROS', 3=>'Caesar', 4=>'Tarth', 5=>'Beer', 6=>'Shift', 7=>'Alice', 8=>'NSA', 9=>'Horse', 10=>'Book', 11=>'Vig', 12=>'Gadget', 13=>'Egypt', 14=>'Purge', 15=>'Remail', 16=>'Scores');
	$lvl = array_search($_GET['cp'], $aLvls);
	if($lvl === false) { //if not found
		//cheater!
		DB::Run('UPDATE cicada_puzzle SET cheats=cheats+1 WHERE puzz_id=?', [$g_cpID]);  //CHEATER!
		ErrMsg(0, "Cicada puzzle cheater! $g_cpEmail, id=$g_cpID, lvl=$g_cpLvl called CP({$_GET['cp']})");
		exit;
	}
	$_SESSION['cpLvl'] = $lvl+1;  //advance to next level

	//check if skipped a level
	$maxLvl = DB::Run('SELECT maxlvl FROM cicada_puzzle WHERE puzz_id=?', [$g_cpID])->fetchColumn();
	if($lvl > $maxLvl+1  &&  ($maxLvl < 4  ||  $maxLevel > 9))  //if skipped a level and it wasn't a decoy level
		DB::Run('UPDATE cicada_puzzle SET cheats=cheats+1 WHERE puzz_id=?', [$g_cpID]);  //CHEATER!

	//check if went too fast
	if($lvl == count($aLvls)-1) {  //if final level
		$elapsed = DB::Run('SELECT TIME_TO_SEC(TIMEDIFF(updated, started)) AS elapsed FROM cicada_puzzle WHERE puzz_id=?',
						 [$g_cpID])->fetchColumn();
		if($elapsed  &&  $elapsed < 60*10)  //10 minutes is fastest reasonable time
			DB::Run('UPDATE cicada_puzzle SET cheats=cheats+1 WHERE puzz_id=?', [$g_cpID]);  //CHEATER!
	}

	//update database
	DB::Run('UPDATE cicada_puzzle SET level=?, maxlvl=GREATEST(?,maxlvl), lvlhint=0, updated=NOW() WHERE puzz_id=?',
			[$lvl, $lvl, $g_cpID]);
	//if first time completing this level, add level completion time; i.e. only the first completion time counts
	if(DB::Run('SELECT NOT EXISTS(SELECT 1 FROM cicada_puzzle_times WHERE puzz_id=? AND level=?)', 
				[$g_cpID, $lvl])->fetchColumn())
		DB::Run('INSERT INTO cicada_puzzle_times (puzz_id, level, time) VALUES (?, ?, NOW())', [$g_cpID, $lvl]);
	exit;
} //CP


//------------------------------------------------------------------------
//$_POST['email'] - called from StartPuzzle();
if(isset($_POST['email'])) {
	//validate email
	if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
		exit('Invalid email. Are you sure you\'re ready for this?');
	$g_cpEmail = $_SESSION['cpEmail'] = $_POST['email'];
	$dbEmail = NormalizeGmail($g_cpEmail);

	//remail - check for users that have completed puzzle
	if($g_cpLvl == 15
	 && DB::Run('SELECT EXISTS(SELECT 1 FROM cicada_puzzle WHERE puzz_id=? AND email=?)', 
				[$g_cpID, $dbEmail])->fetchColumn()) {
		//send puzzle completion email
		$cpRemailID = DB::Run('SELECT remail_id FROM cicada_puzzle WHERE puzz_id=?', [$g_cpID])->fetchColumn();
		$msg = str_replace('*cpRemailID*', $cpRemailID, $g_aHints[16][1]);
		if(!EmailUser($g_cpEmail, $g_cpEmail, "$g_siteName puzzle completion", $msg))
			exit('Unable to send completion email. Please try again later.');
		exit('Another email has been sent.');
	} //if lvl 15

	//add email to database
	$id = (int)DB::Run('SELECT puzz_id FROM cicada_puzzle WHERE email=? AND maxlvl=0', [$dbEmail])->fetchColumn();
	if($id) { //if exists
		DB::Run("UPDATE cicada_puzzle SET started=NOW(), updated=NOW() WHERE puzz_id=?", [$id]);
	} else { //create a new entry
		//Note: if(g_cpID) then user is restarting puzzle, it counts as a cheat;  CHEATER!
		if(!DB::Run("INSERT INTO cicada_puzzle (prev_id, email, started, updated, cheats, remail_id) 
					 VALUES (?, ?, NOW(), NOW(), ?, ?)", 
					[$g_cpID, $dbEmail, $g_cpID ? 1 : 0, bin2hex(random_bytes(8))]))
			exit('Unable to start puzzle. Please try again later.');
		$id = DB::lastInsertId();
	}
	$_SESSION['cpID'] = $id;

	//set cookie
	do { $cookie_id = bin2hex(random_bytes(20));  //make sure it's unique to entire database
	} while(DB::Run('SELECT EXISTS(SELECT 1 FROM cicada_puzzle WHERE cookie_id=? LIMIT 1)', [$cookie_id])->fetchColumn());
	setcookie('cp', $cookie_id, time() + 60*60*24*90, '/');  //remember user for 90 days
	$cookie_hash = hash('sha256', $cookie_id);
	DB::query("UPDATE cicada_puzzle SET cookie_id='$cookie_hash' WHERE puzz_id=$id");

	//send puzzle start email
	if(!EmailUser($g_cpEmail, $g_cpEmail, "$g_siteName puzzle", $g_aHints[0][1])) {
		exit('Unable to send puzzle start email. Please try again later.');
	}
	$_SESSION['cpLvl'] = 1;  //current (not yet completed) level
	exit('An email has been sent.');
}//$_POST['email']

//after puzzle, leave cookie intact so a second attempt will count as a cheat
