<?php # API for Dungeon of Thunk
require_once "{$_SERVER['DOCUMENT_ROOT']}/inc/common.php";
require_once "{$_SERVER['DOCUMENT_ROOT']}/inc/sql.php";

//$reqMethod = GET | HEAD | PUT | POST | DELETE
//$reqData   = []
$reqMethod = $_SERVER['REQUEST_METHOD'] ?? '';
$reqData = [];
if($reqMethod)  parse_str(file_get_contents("php://input"), $aReqData);
$reqCmd = (count($reqData) === 1) ? $reqData[0] : '';

echo '$_GET[]='; var_dump($_GET);
echo '$_POST[]='; var_dump($_POST);
echo '$_FILES[]='; var_dump($_FILES);
echo '$_REQUEST[]='; var_dump($_REQUEST);

$sData = json_encode($reqData);
echo <<<DBG
method: {$reqMethod}
cmd: {$reqCmd}
data: {$sData}
DBG;


/*
if($reqMethod === 'GET')  return json_encode($reqData);

if($reqCmd === 'ents') {
	return file_get_contents('ents.json');
}
*/

/* //x
//update player's current score, return list of high scores
if(isset($_SERVER['REQUEST_METHOD'])  &&  $_SERVER['REQUEST_METHOD'] === 'PUT') {
	//get player's current score from PUT variables [score, level, id]
	parse_str(file_get_contents("php://input"), $aPut); //$aPut = $_PUT
	$score = filter_var($aPut['score'] ?? 0, FILTER_SANITIZE_NUMBER_INT);
	$level = filter_var($aPut['level'] ?? 0, FILTER_SANITIZE_NUMBER_INT);
	$uid   = ctype_xdigit($aPut['uid'] ?? 0)  ?  $aPut['uid']  :  0;
	if($aPut  &&  count($aPut) == 3  &&  $score > 0  &&  $level > 0  &&  $uid) {
		//update player's current score
		DB::Run('UPDATE pow2_scores SET score=?, level=?, updated=NOW() WHERE uid=?', 
				[$score, $level, $uid]);
	}

	//return top 10 high scores
	ob_end_clean();
	$aScores = DB::Run('SELECT name, score, level, TIMEDIFF(updated,started), started'.
			' FROM pow2_scores ORDER BY score DESC LIMIT 50')->fetchAll(PDO::FETCH_NUM);
	if(!$aScores)  exit('[]');
	foreach($aScores as $score)
		$score[4] = date('j M Y g:ia', strtotime($score[4])); //us a prettier date/time format
	exit(json_encode($aScores));
} //POST[scores]
*/

/*
//Unrecognized request
$subj = 'Dungeon of Thunk API: Suspicious access';
EmailAdmin($subj, <<<EMAIL
<p>Someone attempted to directly access {$_SERVER['PHP_SELF']}. This API is for public use but only via valid API requests. This invalid API request was:</p>

<p>
method: {$reqMethod}<br>
cmd: {$reqCmd}<br>
data: {json_encode($reqData)}
</p>

<p>If this happens once or twice, no big deal, it's probably an honest mistake or web crawler. However, if it continues, it may be a hacker probing the website. This is a throttled event so multiple access attemps will trigger the website throttle, slowing down each subsequent access attempt. The throttle should not affect normal website usage.</p>
EMAIL;);
Throttle($subj);
while(ob_get_level())  ob_end_clean(); //make sure no HTTP has been sent yet
header('Location: /'); //redirect to home page
exit;
*/