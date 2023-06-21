<?php # Boss menu helpers
if(count(get_included_files())==1)  exit; //direct access not permitted

const USER_QRY = [
	'recent'    =>'/^$|^recent$/i',           //<empty> | recent
	'id'        =>'/^\d+$/i',                 //<user#>
	'range'     =>'/^(\d*)-(\d*)$/',          //a-b, a-, -b
	'new'       =>'/^new$/i',                 //new
	'wholesale' =>'/^w(?:hole(?:sale)?)?$/i', //w | whole | wholesale
	'boss'      =>'/^boss$/i',                //boss
	'created'   =>'/^(?:created|registered)\b\s*(?:since|[=:])?\s*/i', //created [since] <date>
	'visited'   =>'/^(?:visited|active)\b\s*(?:since|[=:])?\s*/i',     //visited [since] <date>
	'name'      =>'/^name\b\s*[=:]?\s*/i',                             //first or last name =
	'flags'     =>'/^(?:flags?|access|status)\b\s*[=:]?\s*/i',         //flags =
	//keyval must be last so doesn't override other <cmd>=
	'keyval'    =>'/^(\w+\b)\s*[=:]?\s*(\w+\b)/i', //<key>=<val>: email, company, city, state...
]; //USER_QRY


//---------------------------------------------------------------------------
//ShowUsers - display a list of users
// qry - query string from boss input box
function ShowUsers($qry) {
	if(!IsBoss('Cashier'))  return;

	//parse $qry => $qryCmd $qryVal
	$qryCmd = $qryVal = ''; //see USER_QRY; Ex: new, wholesale, name, state...
	foreach(USER_QRY as $key=>$regex) {
		if(preg_match($regex, $qry)) {
			$qryCmd = $key; //replace with formal command from BOSS_CMDS
			$qryVal = preg_replace($regex, '', $qry); //remove command; Ex: 'name=smith' => 'smith'
			break;
		} //if preg_match
	} //foreach

	//build SQL
	$title = $where = $orderBy = $filter = '';
	$aData = [];
	switch($qryCmd) {
	case 'recent':
		$title = 'Recently active users';
		$where = '';
		$orderBy = 'ORDER BY visited DESC';
	break;

	case 'id': //user# - redirect to account page
		ob_end_clean();
		header("Location: /account.php?$qry");
		exit;
	
	case 'new':
		$title = 'New users';
		$where = '';
		$orderBy = "ORDER BY created DESC";
	break;

	case 'wholesale':
		$title = 'Users with wholesale access';
		$where = 'WHERE (flags & '. USER_FLAGS['Wholesale'] .')';
		$orderBy = "ORDER BY visited DESC";
	break;

	case 'boss':
		$title = 'Users with boss access';
		$where = 'WHERE (flags >= '. USER_FLAGS['Intern'] .')';
		$orderBy = 'ORDER BY flags DESC, user_id ASC';
	break;

	case 'range': //user# range: a-b, a-, -b
		preg_match(USER_QRY['range'], $qry, $aGroups);
		$title = "Users #{$aGroups[0]}"; //range
		$where = 'WHERE ';
		if($aGroups[1]) {
			$where .= "user_id >= {$aGroups[1]}";
			if($aGroups[2])  $where .= ' AND ';
		}
		if($aGroups[2])
			$where .= "user_id <= {$aGroups[2]}";
		$orderBy = 'ORDER BY user_id';
	break;

	case 'created':  case 'visited': // users created/visited since <date>
		$since = strtotime($qryVal ? $qryVal : '1 month ago');
		if($since > time())  $since = strtotime("$qryVal ago"); //change "2 months" to "2 months ago"
		$days = round((time() - $since) / (60*60*24));
		$filter = '<input type="date" min="2005-01-01" max="'. date('Y-m-d') 
		.'" value="'. date('Y-m-d', $since) .'"'
		." onchange=\"if(this.checkValidity()) window.location='?order:date='+ this.value\">";
		$title = "Users $qryCmd since $filter ($days days)";
		$where = "WHERE $qryCmd >= FROM_UNIXTIME(?)";
		$orderBy = "ORDER BY $qryCmd ASC"; //oldest first
		$aData = [$since];

		//show <input date>
		$filter = "<br>\n". '<input type="date" min="2005-01-01" max="'. date('Y-m-d') 
			.'" value="'. date('Y-m-d', $since)
			."\" onchange=\"if(this.checkValidity()) window.location='?users=$qryCmd:'+ this.value\">";
	break;

	case 'name':
		$title = "Users with name like '$qryVal'";
		$where = 'WHERE first_name LIKE ? OR last_name LIKE ?';
		$orderBy = 'ORDER BY last_name, first_name';
		if(strrpos($qryVal,' ') === false) { //single name (no spaces)
			$fn = $ln = $qryVal;
		} else {
			//split name:  Johann Sebastian Bach => ['Johann Sebatian', 'Bach']
			$aName = explode(' ', $qryVal);
			$ln = array_pop($aName); //end of array
			$fn = implode(' ', $aName);
		}
		$aData = ['%'.$fn.'%', '%'.$ln.'%'];
	break;

	case 'flags':
		$qryVal = empty($qryVal) ? 'Default' : ucfirst(strtolower($qryVal));
		$aFlags = array_combine(array_keys(USER_FLAGS), array_keys(USER_FLAGS));
		$filter = SelectBox('user:flags', $qryVal, $aFlags);
		if(USER_FLAGS[$qryVal] < USER_FLAGS['Intern']) { //normal user flags (not boss)
			$title = "Users with status = $filter";
			$where = ($qryVal === 'Default') ? 'WHERE flags=0' : 'WHERE (flags & '. USER_FLAGS[$qryVal] .')';
			$orderBy = 'ORDER BY visited DESC';
		} else { //boss flags
			$title = "Boss users with status >= $filter";
			$where = 'WHERE (flags >= '. USER_FLAGS[$qryVal] .')';
			$orderBy = 'ORDER BY flags, user_id';
		}
	break;

	case 'keyval': //<field> = <value>
		preg_match(USER_QRY['keyval'], $qry, $aGroups);
		$aFields = DB::Run('SHOW COLUMNS FROM users')->fetchAll(PDO::FETCH_COLUMN);
		if(count($aGroups) == 3  &&  in_array($aGroups[1], $aFields, true)) {
			$title = "Users where {$aGroups[1]} like {$aGroups[2]}";
			$where = "WHERE {$aGroups[1]} LIKE ?";
			$orderBy = 'ORDER BY visited DESC';
			$aData = ['%'.$aGroups[2].'%'];
			break;
		}
		//else fall-through to default

	default: //unrecognized command = date | name
		if(strtotime($qry) !== false  &&  strlen($qry) > 1) //if date && !single-character-timezone
			return ShowUsers("visited $qry");
		return ShowUsers("name $qry");
	} //switch

	//SELECT all matching users
	$limit = isset($_GET['moreUsers'])  ? 500 : 50;
	$sql = "SELECT * FROM users $where $orderBy LIMIT $limit";
//x echo Dump($sql, 'sql') ."<br>\n". Dump($aData, 'aData');
	$aUsers = DB::Run($sql, $aData)->fetchAll();
	if(!$aUsers) {
		echo "<div class=\"ctxt\">Unable to find any $title</div>\n";
		return;
	}

	echo "<article id=\"users\" class=\"floatBox\">\n";
	echo "  <table class=\"list\"><caption>$title</caption>\n";
	echo "  <thead><tr><th>#</th><th>Name</th><th>Email</th><th>Registered</th><th>Visited</th>"
		."<th>Referral / <span style=\"color:navy\">Internal Notes</span></th></thead><tbody>\n";
	foreach($aUsers as $user) {
		global $g_siteName;
		$name = htmlspecialchars($user['first_name'] .' '. $user['last_name']);
		$desc = htmlspecialchars($user['remarks']);
		if($user['notes'])  $desc = "<span style=\"color:navy\">{$user['notes']}</span>; $desc";
		if($user['flags'])  $desc = "<span style=\"color:brown\">".FlagList($user['flags'])."</span>; $desc";
		$descTitle = strip_tags($desc);
		$desc = substr($desc, 0, 250);

		echo "<tr>\n";
		echo "  <td><a href=\"/account.php?{$user['user_id']}\">{$user['user_id']}</a></td>\n";
		echo "  <td class=\"name\" title=\"$name\">$name</td>\n";
		echo "  <td class=\"name\" title=\"{$user['email']}\"><a href=\"mailto:$name%3c{$user['email']}%3e?subject=$g_siteName\">{$user['email']}</a></td>\n";
		echo "  <td class=\"date\">". DateHTML($user['created']) ."</td>\n";
		echo "  <td class=\"date\">". DateHTML($user['visited']) ."</td>\n";
		echo "  <td class=\"desc\" title=\"$descTitle\">$desc</td>\n";
		echo "</tr>\n";
	} //foreach
	echo "</tbody></table>\n";

	//show "More" or "Less" link at bottom of table
	if(isset($_GET['moreUsers']))
		echo '  <a class="more" href="'. RemoveQuery('moreUsers') ."\">Less</a>\n"; //link = show less
	else if(count($aUsers) === $limit) //if hit LIMIT
		echo '  <a class="more" href="'. AddQuery('moreUsers') ."\">More</a>\n"; //link = show more

	echo "</article>\n";
} //ShowUsers
