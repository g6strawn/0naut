<?php //Copyright (c) 2022-2023 Gary Strawn, All rights reserved

//process fetch
require_once "{$_SERVER['DOCUMENT_ROOT']}/inc/sql.php";
if(isset($_GET['list'])  &&  isset($_GET['type'])) {
	$data = DB::Run("SELECT x, y, z FROM spacex WHERE list=? AND type=?",
			[$_GET['list'], $_GET['type']])->fetchAll();
	foreach($data as )
	exit(json_encode($data));
}

function IsLocalHost() { return in_array($_SERVER['SERVER_ADDR'], array('127.0.0.1', '::1')); }
$root = IsLocalHost() ? '/three' : 'https://threejs.org';
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
  <title>Gary's 3D Satellite Demo</title>
  <meta charset="UTF-8" />
  <meta name="copyright" content="Gary Strawn" />
  <meta name="description" content="Satellites and users" />
  <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
  <link rel="stylesheet" href="index.css" />
  <script async src="https://unpkg.com/es-module-shims@1.3.6/dist/es-module-shims.js"></script>
  <script type="importmap">{ "imports": {
    "three":  "<?=$root?>/build/three.module.js",
	"three/": "<?=$root?>/"
  }}</script> 
  <script type="module" src="index.js"></script>
</head>
<body>
  <noscript>
    NOTE: Your browser has Javascript disabled. This page will not function properly unless you <a href="http://www.enable-javascript.com/" rel="external" target="_blank">enable Javascript</a>.
  </noscript>

  <header><div>
    <h1>Satellites</h1>
    <div id="controls">
      <div id="optRadio">
        <label title="1000 users, 64 satellite, 95% coverage">
          <input type="radio" name="showList" value="3" checked="checked">100/64, 95%</label><br>
        <label title="5000 users, 700 satellites, 80% coverage">
          <input type="radio" name="showList" value="4">5k/700, 80%</label><br>
        <label title="50,000 users, 36 satellites, 1% coverage">
          <input type="radio" name="showList" value="5">50k/36, 1%</label><br>
        <label title="10,000 users, 720 satellites, 80% coverage">
          <input type="radio" name="showList" value="6">10k/720, 80%</label>
      </div>
<!--
      <button class="bigRed" id="insertCoin">25&cent;</button>
      <img src="\img\info.png" id="infoBtn">
-->
    </div>
  </div></header>

  <div id="info" style="display:none"><div>
    <button class="blueBtn round" id="infoClose">&times;</button>
    <p>Satellites and users.</p>
  </div></div>

</body>
</html>

<?php //build spacex database
/*
require_once "{$_SERVER['DOCUMENT_ROOT']}/inc/sql.php";
if(isset($_GET['db'])) {
	DB::Run("DROP TABLE IF EXISTS spacex");
	DB::Run("CREATE TABLE spacex (
		item_key INT UNSIGNED NOT NULL AUTO_INCREMENT,
		item_id INT UNSIGNED NOT NULL COMMENT 'sat/user id',
		list INT UNSIGNED NOT NULL COMMENT 'Exercise number 1-6',
		type ENUM('sat', 'user') NOT NULL COMMENT 'sat or user',
		x FLOAT NOT NULL,
		y FLOAT NOT NULL,
		z FLOAT NOT NULL,
		PRIMARY KEY (item_key),
		KEY(item_id),
		KEY(list)
	) ENGINE=InnoDB;");

	$aFiles = glob("./*.txt"); //sorted list of .txt files
	foreach($aFiles as $fileId => $filename) {
		echo "Importing $filename<br>\n";
		$file = new SplFileObject($filename);
		while(!$file->eof()) {
			$aTok = explode(' ', $file->fgets());
			if(count($aTok) != 5  ||  !($aTok[0] == "sat"  ||  $aTok[0] == 'user'))
				continue; //ignore invalid lines
//			echo $aTok[0].' '.$aTok[1].' '.$aTok[2].' '.$aTok[3].' '.$aTok[4]."<br>\n";
			if(!DB::Run("INSERT INTO spacex (item_id, list, type, x, y, z) VALUES (?,?,?, ?,?,?)",
						[$aTok[1], $fileId+1, $aTok[0],  $aTok[2], $aTok[3], $aTok[4]]))
				echo "Error inserting {$tok[0]} {$tok[1]}";
		} //while
	} //foreach
} //if db
*/
/*
require_once "{$_SERVER['DOCUMENT_ROOT']}/inc/sql.php";
if(isset($_GET['db'])) {
	$filename = '06_ten_thousand.txt';
	$file = new SplFileObject($filename);
	$begin = $_GET['db'] * 1000;
	$end = $begin + 1000;
	echo "Importing $filename, $begin - $end<br>\n";
	for($i = 0;  !$file->eof()  &&  $i < $begin;  $i++)
		$file->fgets();

	for( ; !$file->eof()  &&  $begin < $end; $begin++) {
		$aTok = explode(' ', $file->fgets());
		if(count($aTok) != 5  ||  !($aTok[0] == "sat"  ||  $aTok[0] == 'user'))
			continue; //ignore invalid lines
		echo $aTok[0].' '.$aTok[1].' '.$aTok[2].' '.$aTok[3].' '.$aTok[4]."<br>\n";
		if(!DB::Run("INSERT INTO spacex (item_id, list, type, x, y, z) VALUES (?,?,?, ?,?,?)",
					[$aTok[1], $filename[1], $aTok[0],  $aTok[2], $aTok[3], $aTok[4]]))
			echo "Error inserting {$tok[0]} {$tok[1]}";
	} //for
} //if db
*/
?>