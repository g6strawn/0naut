<?php # fetch 30-years of market data from a random starting point
/* Called only from /investing/invest.js
Receives:  $_POST['Market']
Returns: JSON array of float = 30 years of daily closing prices

Data taken from: https://finance.yahoo.com/quote/DJIA/history?p=DJIA
*/
require_once "{$_SERVER['DOCUMENT_ROOT']}/inc/common.php";  //IsLocalHost, ErrMsg
require_once "{$_SERVER['DOCUMENT_ROOT']}/inc/sql.php";     //class DB


//build market data;  Convert "DJIA daily.csv" => market.dates and market.prices
if(isset($_GET['build'])  &&  IsLocalHost()) {
	$aLines = file("DJIA daily.csv", FILE_IGNORE_NEW_LINES);
	unset($aLines[0]); //remove first line (header)
	foreach($aLines as $line) {
		$aLine = explode(',', $line);
		$aPrice[] = $aLine[5]; //adjusted closing price (12.123456)
		$aDate[]  = $aLine[0]; //date (YYY-MM-DD)
	}
	file_put_contents("market.prices", implode("\n",$aPrice));
	file_put_contents("market.dates", implode("\n",$aDate));
	exit(count($aPrice) ." market days");
} //GET[build]


if($_SERVER['REQUEST_METHOD'] !== 'POST')  return; //POST access only


//return 30-year window (30*52*5 days) of DJIA market price
if(isset($_POST['market'])) {
	$aPrice = file('market.prices', FILE_IGNORE_NEW_LINES);
	if(!$aPrice)  return;
	$iStart = rand(0, count($aPrice) - (30*52*5)); //random start position
	$aPrice = array_slice($aPrice, $iStart, 30*52*5); //truncate to 30-year window

	$aDate = file('market.dates', FILE_IGNORE_NEW_LINES);
	if(!$aDate)  return;
	$_SESSION['marketStartDate'] = $aDate[$iStart];
	unset($aDate);

	//send data back to client
	exit(json_encode(['uid'=>uniqid(), 'aMkt'=>$aPrice]));
} //POST[market]


//receive game update (return silence)
if(isset($_POST['uid'])) {
	if(strlen($_POST['uid']) != 13  ||  !ctype_xdigit($_POST['uid']))
		return; //invalid ID, ignore this update

	if(isset($_POST['name'])) {
		//1st update, game just started
		$name = htmlspecialchars($_POST['name'])  ||  'Anonymous'; //sanitize name
		DB::Run('INSERT INTO market_scores (uid, name, start) VALUES (?, ?, ?)', 
				[$_POST['uid'], $name, $_SESSION['marketStartDate'] ?? 'Unknown']);
	} else {
		//"monthly" game update (every 4.3 seconds)
		DB::RUN('UPDATE market_scores SET days=?, youCash=?, avgCash=?, youGain=?, avgGain=? WHERE uid=?', 
				[$_POST['day'], $_POST['youCash'], $_POST['avgCash'], $_POST['youGain'], $_POST['avgGain'], $_POST['uid']]);
	}
	return;
} //POST[uid]


function GainStr($p) { return (($p > 0) ? '+' : '') . number_format($p, 1) .'%'; }

//return recent scores
if(isset($_POST['scores'])) {
	$aOut = [];
	$aScore = DB::Run('SELECT * FROM market_scores ORDER BY played DESC LIMIT 50')->fetchAll();
	foreach($aScore as $score) {
		$out = [];
		$out[] = $score['name'];
		$out[] = date('j M Y g:ia', strtotime($score['played']));
		$out[] = date('j M Y', strtotime($score['start']));
		if($score['days'] >= 30*52*5) //30 years * 52 weeks/year * 5 days/week
			$out[] = '30 years';
		else
			$out[] = floor($score['days'] / (52*5)) .' years, '
					.floor(($score['days'] % (52*5)) / (52*5/12)) .' months';
		$out[] = '$'. number_format($score['youCash']);
		$out[] = GainStr($score['youGain']);
		$out[] = '$'. number_format($score['avgCash']);
		$out[] = GainStr($score['avgGain']);
		$aOut[] = $out;
	}
	exit(json_encode($aOut));
} //POST[scores]


/*
-- --------------------------------------------------------------------------
-- market_scores - Beat the Market (see invest.js)
DROP TABLE IF EXISTS market_scores;
CREATE TABLE market_scores (
id      INT UNSIGNED  NOT NULL AUTO_INCREMENT,
uid     VARCHAR(13)   NOT NULL UNIQUE,
name    VARCHAR(10)   NOT NULL DEFAULT '',
played  DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
start   VARCHAR(10)   NOT NULL DEFAULT '',
days    INT UNSIGNED  NOT NULL DEFAULT 0,
youCash INT           NOT NULL DEFAULT 0,
avgCash INT           NOT NULL DEFAULT 0,
youGain DECIMAL(10,1) NOT NULL DEFAULT 0,
avgGain DECIMAL(10,1) NOT NULL DEFAULT 0,
PRIMARY KEY (id),
KEY (uid),
KEY (played)
);
*/