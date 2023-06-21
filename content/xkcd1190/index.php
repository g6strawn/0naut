<?php # xkcd 1190 - Time
require_once "{$_SERVER['DOCUMENT_ROOT']}/inc/header.php";

//---------------------------------------------------------------------------
//POST[iFrame]=delay - edit frame's delay
//Ex: POST[99] = 3000;  frame #99 has 3 second delay
if(IsBoss('Dev')  &&  !empty($_POST)) {
	$iFrame = (int)(array_keys($_POST)[0]);
	$delay  = (int)($_POST[$iFrame] ?? -1);
	$delay = (isset($_POST[$iFrame]) && ctype_digit($_POST[$iFrame])) ? (int)$_POST[$iFrame] : -1;
	if($iFrame > 0  &&  $iFrame <= 3099  &&  $delay >= 0  &&  $delay <= 60*60*1000) {
		//edit specified frame's delay
		if(DB::Run('UPDATE xkcd1190 SET `delay`=? WHERE `frame`=?', [$delay ?: null, $iFrame]))
			exit("Changed frame #$iFrame's delay to $delay");
	}
	exit("Invalid edit parameters");
} //POST

//---------------------------------------------------------------------------
//GET[update] - update database timestamps
if(IsBoss('Dev')  &&  isset($_GET['update'])) {
	echo "Updating database timestamps. This will take awhile...<br>\n";
	ob_flush();  flush();  //need both to override PHP's internal buffer

	$ticks = 0; //accumulated time (i.e. frame's timestamp)
	$aFrame = DB::Run('SELECT frame, delay FROM xkcd1190')->fetchAll();
	foreach($aFrame as $frame) {
		if(!DB::Run('UPDATE xkcd1190 SET ticks=? WHERE frame=?', [$ticks, $frame['frame']]))
			ErrMsg("Error update ticks for frame {$frame['iFrame']}: $ticks");
		$ticks += $frame['delay'] ?: 1000; //NULL = default = 1s
	} //foreach
	echo 'Total time: '. $ticks/1000 .' = '. 
		date('G:i:s', (int)($ticks/1000)) ."<br>\n";
	echo "Note: Last frame (3099) delay = 1 hour before looping animation.<br>\n";
} //GET[update]


//output javascript frame delays & ticks
//<script>
//  const g_aDelays = {"1":2000, "52":2000, ...};
//  const g_aTicks = [0,2000,3000,4000, ...];
//</script>
echo "<script>\n";
$aDelays = DB::Run("SELECT frame, delay FROM xkcd1190 WHERE delay IS NOT NULL")->fetchAll(PDO::FETCH_KEY_PAIR);
echo "const g_aDelays = ". json_encode(array_map('intval', $aDelays)) .";\n";
$aTicks = DB::Run("SELECT ticks FROM xkcd1190")->fetchAll(PDO::FETCH_COLUMN);
echo "const g_aTicks = [". implode(',', array_map('intval', $aTicks)) ."];\n";
echo "</script>\n";

//start animation at specified frame
$iFrame = 1;
if(!empty($_GET)  &&  ctype_digit((string)array_keys($_GET)[0]))  //ex: /index.php?99
	$iFrame = (int)(array_keys($_GET)[0]);
if($iFrame < 1  ||  $iFrame > 3099)  $iFrame = 1;

//fetch frame data
$aFrame = DB::Run('SELECT delay, ticks FROM xkcd1190 WHERE frame=?', [$iFrame])->fetch();
$url = '/content/xkcd1190/frames/'. sprintf('%04d.png', $iFrame);
if(!file_exists($_SERVER['DOCUMENT_ROOT'] . $url)) {
	//local file not found, fetch from xkcd
	$url = 'http://imgs.xkcd.com/comics/time/'. $aFrame['xlink'] .'.png';
}
$delay = (int)$aFrame['delay'] ?: 1000;
?>

<article>
<div id="imgBox">
  <img id="xkcd1190img" src="<?=sprintf('/content/xkcd1190/frames/%04d.png',$iFrame)?>">
  <img id="playBtn" src="play.png">
</div>
<div id="license">
  <a href="https://xkcd.com">xkcd</a> #1190 - Time &copy; <a href="https://xkcd.com/license.html" title="xkcd license">Creative Commons License</a>
</div>
<div id="currInfo">
  <span class="left"><span id="iFrame"><?=$iFrame?></span> of 3099</span>
  <input id="progBar" type="range" min=0 max=3099 step=1 value=<?=$iFrame?>>
  <span class="right"><span id="currTime"><?=
    date(($aFrame['ticks'] < 60*60*1000) ? 'i:s' : 'G:i:s', (int)($aFrame['ticks'] / 1000));
    ?></span> of 1:13:48</span>
</div>
<?php if(IsBoss('Dev')  &&  isset($_GET['edit'])) { ?>
  <label title="default = 1 second">Delay: 
    <input id="editDelay" name="delay" type="number" min=0 max=60 step="0.1" 
      value="<?= $aFrame['delay'] ? ($delay/1000) : '' ?>" disabled>
  </label>
</article>
<?php } ?>

<?php include_once "{$_SERVER['DOCUMENT_ROOT']}/inc/footer.php"; ?>
