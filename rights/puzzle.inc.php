<?php  //must be included in every file that uses Cicada Puzzle
//TODO: Prerequisites: Mouse, Javascript, cookies, updated browser.

if(count(get_included_files())==1)  exit;  //direct access not permitted

//-----------------------------------------------------------------------------
//initialize global variables
//$g_cpID    = $_SESSION['cpID']    = user id = TABLE cicada_puzzle.puzz_id; 0 = not yet started
//$g_cpLvl   = $_SESSION['cpLvl']   = current (not yet completed) level; 0 = not yet started
//$g_cpEmail = $_SESSION['cpEmail'] = users not-normalized email (as they entered it)
if(session_id() == '')  { session_name('sid');  session_start(); }  //needed by puzzle.php
if(isset($_SESSION['cpID'])  &&  isset($_SESSION['cpLvl'])) {
	//puzzle already started
	$g_cpID  = (int)$_SESSION['cpID'];
	$g_cpLvl = (int)$_SESSION['cpLvl'];
} else if(isset($_COOKIE['cp'])) {
	//browser was closed; resume puzzle from previous session
	$cpReset = DB::Run('SELECT puzz_id, level FROM cicada_puzzle WHERE cookie_id=?', 
					[hash('sha256', $_COOKIE['cp'])])->fetch();
	$g_cpID  = $_SESSION['cpID']  = (int)$cpReset['puzz_id'];
	$g_cpLvl = $_SESSION['cpLvl'] = $g_cpID ? (int)$cpReset['level']+1 : 0;  //DB = completed level; current level = DB+1
} else {
	$g_cpID = $g_cpLvl = 0;  //puzzle not yet started
} //$g_cpID, $g_cpLvl

//Note: Don't use database email because it contains normalize gmail addresses
$g_cpEmail = isset($_SESSION['cpEmail'])  ?  $_SESSION['cpEmail']  :  '';


//------------------------------------------------------------------------
//NormalizeGmail - remove alias (everything after +) and remove all .
//username@gmail.com = user.name@ = username+junk@ = us.er.na.me+morejunk@
function NormalizeGmail($email) {
	$aEmail = explode('@', $email);
	if(strtolower($aEmail[1]) != 'gmail.com')  return $email;
	$email = strstr($aEmail[0], '+', true);  //get portion before +, exclusive
	if(!$email)  $email = $aEmail[0];
	$email = str_replace('.', '', $email);  //remove all .
	return $email .'@'. $aEmail[1];
} //NormalizeGmail


//-----------------------------------------------------------------------------
//javascript CP() - Cicada Puzzle;  user completed a level, send txt to puzzle.php
if(IsLocalHost()) echo "<!-- id=$g_cpID, lvl=$g_cpLvl, $g_cpEmail -->\n"; ?>
<!--
ATTENTION PUZZLE SOLVERS!!!
There are no clues here. The entire puzzle is solvable without reading 
the source code. If you feel stuck, you can find hints here:
<?=$g_siteURL?>/content/rights/cicada/01-hello/#hint

Reading the source code is considered cheating.
-->
<script>
var g_noCheat = "Warning: Don't cheat.";
var g_cp = <?= $g_cpID ? '!0' : '!1'; ?>;
function CP(cp, url) {
  if(g_cp && cp) {
<?php if(IsLocalHost()) echo '    console.log("CP="+ cp);'."\n"; ?>
    var ajax = new XMLHttpRequest();
    ajax.open("GET", "/content/rights/puzzle.php?cp="+cp);
    if(url)  ajax.onload = function() { window.location = url; }
    ajax.send();
  }
  return g_cp;
}
<?php

	//level 14 - purge
	if($g_cpLvl == 14  &&  isset($cpReset)) {
		//purge complete (i.e. session variables were reset)
		echo "CP('Purge');\n";
		$g_cpLvl++;  //advance to next level
	} //if $g_cpLvl
?></script>
