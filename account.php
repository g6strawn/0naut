<?php # View user account
require_once "{$_SERVER['DOCUMENT_ROOT']}/inc/header.php";
require_once "{$_SERVER['DOCUMENT_ROOT']}/inc/form.php";

$errMsg = ''; //error message (if any) displayed at top of screen

//user ID
$uid = $_SESSION['uid'] ?? 0; //0 = create a new user
//only Cashier can view other users;  ex: account.php?9999
if(IsBoss('Cashier')  &&  !empty($_GET)  &&  ctype_digit((string)array_keys($_GET)[0]))
	$uid = (int)(array_keys($_GET)[0]); //GET = user# (might also have GET[1]=more)

//fetch default values
if($uid) { //existing user
	$user = DB::Run('SELECT * FROM users WHERE user_id=?', [$uid])->fetch();
	if(!$user) { ?>
		<!-- redirect to home page in 5 seconds -->
		<div class="errBox">
			<h2>Unable to retrieve account information.</h2>
			<img src="/img/wait.gif" class="center">
			<progress id="redirectProgress" value=0 max=100></progress>
		</div>
		<script>
			setInterval(()=>{document.getElementById('redirectProgress').value++}, 5000/100);
			setTimeout(()=>{window.location='/'}, 5000);
		</script><?php
		ErrMsg("Unable to retrieve account information for user #$uid");
		include_once "{$_SERVER['DOCUMENT_ROOT']}/inc/footer.php";
		exit;
	}
	if(!$user['country'])  $user['country'] = 'United States';
	$user = filter_var_array($user, FILTER_SANITIZE_SPECIAL_CHARS); //strip HTML tags
} else { //new user;  create an empty user record
	$aKeys = DB::Run('SHOW COLUMNS FROM users')->fetchAll(PDO::FETCH_COLUMN);
	$user = array_fill_keys($aKeys, NULL);
	$user['autologin'] = $user['notify'] = '1';
	$user['email2'] = $user['pswd'] = $user['pswd2'] = '';
}

//validate & process form
if($_SERVER['REQUEST_METHOD'] === 'POST') {
	//sanitize all POST data
	$post = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS); //strip HTML tags

	//add values not contained in $_POST
	$post['flags'] = $user['flags'];
	$post['autologin'] = isset($_POST['remember']) ? ($user['autologin'] ?: '1') : NULL;
	$post['notify'] = isset($_POST['notify']) ? 1 : 0;
	$user = $post + $user; //add any missing POST variables (only bad bots?)

	//sanitize & validate email
	$user['email'] = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
	if(!filter_var($user['email'], FILTER_VALIDATE_EMAIL)) {
		$errMsg .= "The email you entered was not valid.<br>\n";
		$user['email'] = false;
	}
	if(!$uid) { //new user
		//email confirmation must match
		//  Note: No need to sanitize because they must match exactly
		if(!isset($_POST['email2']) || $user['email'] !== $_POST['email2']) { //compare against unsanitized version
			$errMsg .= "Your email confirmation did not match.<br>\n";
			$user['email'] = $user['email2'] = false;
		} else if(DB::Run('SELECT EXISTS(SELECT 1 FROM users WHERE email=?)', 
					[$user['email']])->fetchColumn()) {
			//duplicate email (i.e. email already taken)
			$errMsg .= 'That email is already registered. Please enter a different email or '
				. '<a href="" onclick="return OpenLogin()">Sign&nbsp;In</a> to your existing account.'."<br>\n";
			$user['email'] = $user['email2'] = false;
		}

		//password confirmation must match
		// Note: Password stored in database can be anything, sanitized version is only for display
		if(!isset($_POST['pswd'])  ||  !isset($_POST['pswd2'])
		 || $_POST['pswd'] !== $_POST['pswd2']) { //do NOT use sanitized password, any password works
			$errMsg .= "Your password confirmation did not match.<br>\n";
			$user['pswd'] = $user['pswd2'] = false;
		}
	} //new user

	//check for invalid fields
	$user = FormValidate($user); //trim long values, set missing/bad values = false
	if(array_search(false, $user, true)) //if any field === false
		$errMsg .= "Please complete required fields marked in red.<br>\n";

	//is user human?
	if(!$uid  &&  (!isset($_POST['captcha'])  ||  $_POST['captcha'] !== 'human'))
		$errMsg .= "This site is for humans only, not spambots.<br>\n";
	
	//send data to database
	if(!$errMsg) {
		if(!isset($user['country'])  ||  $user['country'] === 'United States')
			$user['country'] = NULL; //default = U.S.

		if(!$uid) {
			//create new account
			if(!DB::Run('INSERT INTO users SET 
					email=?, pswdhash=?, autologin=?, flags=?, created=NOW(), visited=NOW(), notify=?, first_name=?, last_name=?, company=?, street=?, street2=?, city=?, state=?, zip=?, country=?, phone=?, phone2=?, remarks=?', 
					[$user['email'], password_hash($_POST['pswd'], PASSWORD_DEFAULT), $user['autologin'], USER_FLAGS['Default'], $user['notify'], $user['first_name'], $user['last_name'], $user['company'], $user['street'], $user['street2'], $user['city'], $user['state'], $user['zip'], $user['country'], $user['phone'], $user['phone2'], $user['remarks']])) {
				ErrMsg("Unable to register email={$user['email']}");
				$errMsg .= "Unable to register, please try again.<br>\n";
			} else {
				$user['user_id'] = DB::lastInsertId();
				EmailBoss('Manager', 'New user registration', UserInfo($user['user_id']));
				if(!IsBoss())  DBLogin($user); //login new user, leave boss logged in as themselves
			}
		} else {
			//update existing account
			if(!DB::Run('UPDATE users SET 
					email=?, autologin=?, notify=?, first_name=?, last_name=?, company=?, street=?, street2=?, city=?, state=?, zip=?, country=?, phone=?, phone2=?, remarks=? WHERE user_id=?', 
					[$user['email'], $user['autologin'], $user['notify'], $user['first_name'], $user['last_name'], $user['company'], $user['street'], $user['street2'], $user['city'], $user['state'], $user['zip'], $user['country'], $user['phone'], $user['phone2'], $user['remarks'], $uid])) {
				ErrMsg("Unable to update account for {$user['email']}");
				$errMsg .= "Unable to update account, please try again<br>\n";
			} else if($uid === $_SESSION['uid'])
				$_SESSION['name'] = $user['first_name']; //in case name changed

			//update user[notes]
			if(IsBoss('Cashier')) { //only Cashiers can modify private user notes
				if(!DB::Run('UPDATE users SET notes=? WHERE user_id=?', [$user['notes'], $uid]))
					ErrMsg("Unable to update notes for {$user['email']}");
			}

			//update user[flags]
			if(IsBoss('Manager')) { //only Managers can modify user flags
				//set user flags
				$oldFlags = $user['flags'];
				if(isset($_POST['Banned']))
					$user['flags'] = USER_FLAGS['Banned']; //turn off all other flags
				else {
					$user['flags'] &= 0xF000; //reset lower bits
					foreach(USER_FLAGS as $key=>$bit) {
						if($bit >= USER_FLAGS['Intern'])  break; //don't iterate boss levels
						if(isset($_POST[$key]))  $user['flags'] |= $bit; //turn on flag bit
					} //foreach
				}

				//set boss level
				$isBoss = isset($_POST['boss']) //was Boss status set?  (use phpMyAdmin for Dev)
					 &&  $_POST['boss'] !== 'Dev' //nobody can be promoted to Dev
					 &&  !HasFlag('Dev', $user['flags']); //nobody can be demoted from Dev
				if($isBoss) {
					if($_POST['boss'] === 'Customer') {
						$user['flags'] &= 0x0FFF; //remove employee status
					} else if(isset(USER_FLAGS[$_POST['boss']])
							&&  USER_FLAGS[$_POST['boss']] >= USER_FLAGS['Intern']) {
						//enable one and only one boss level
						$user['flags'] = (($user['flags'] & 0x0FFF) | USER_FLAGS[$_POST['boss']]);
						if($user['flags'] !== $oldFlags)
							EmailAdmin('Boss access modified', "Boss access was changed for user <a href=\"/user.php?$uid\">$uid - {$user['first_name']} {$user['last_name']}</a><br>\nOld access: ". FlagList($oldFlags) ."<br>\nNew access: ". FlagList($user['flags']) ."<br>\nAdmin who made changes: <a href=\"/user.php?{$_SESSION['uid']}\">{$_SESSION['uid']} - {$_SESSION['name']}</a>");
					}
				} //if isBoss

				//if changed, update flags
				if($user['flags'] !== $oldFlags) {
					if(!DB::Run('UPDATE users SET flags=? WHERE user_id=?', [$user['flags'], $uid]))
						ErrMsg("Unable to update flags for {$user['email']}");
					else if($uid === $_SESSION['uid']) //if modified signed-in user
						$_SESSION['flags'] = (int)$user['flags']; //in case flags changed
				} //if changed
			} //IsBoss('Manager')
		} //update

		//all done, redirect to home page
		ob_end_clean(); //make sure no HTTP has been sent yet
		header('Location: /'); //redirect to home page
		exit;
	} //database
} //validate/process

if($errMsg) echo "<div class=\"errBox\">$errMsg</div>\n";

//new users see "Benefits of Registering"
if($uid) //if signed in
	echo "<h2 class=\"center\">Account Information</h2>\n";
else { ?>
<section id="whyReg" class="floatBox">
  <h2 class="center">New User Registration</h2>
  <p>You must register to continue. Why? Because I said so.</p>
  <img src="\img\spam.png" title="No spam from us.">
  <p>If you had an old Kona coffee account, you can sign in with that. If you forgot your password, you will need access to the email account you registered with.</p>
  <p>If you don't know what this Kona coffee stuff is about or you want to create a new account for whatever reason, that's fine too. Use the form below to register.</p>
  </p>
</section>
<?php } ?>

<form method="post">
<section id="account" class="boxes">
<article class="floatBox">
<?php
	echo FormInput('email', $user['email']);
	if(!$uid) { //if new account
		echo FormInput('email2', $user['email2']);
		echo FormInput('pswd',   $user['pswd']);
		echo FormInput('pswd2',  $user['pswd2']);
	}
	echo FormInput('first_name', $user['first_name']);
	echo FormInput('last_name',  $user['last_name']);
	echo FormInput('company',    $user['company']);
	echo FormInput('street',     $user['street']);
	echo FormInput('street2',    $user['street2']);
	echo FormInput('city',       $user['city']);
	echo FormInput('state',      $user['state']);
	echo FormInput('zip',        $user['zip']);
//Note: for other countries, need to change <select state> to <input region>
//	echo FormInput('country',    $user['country']);
	echo FormInput('phone',      $user['phone']);
	echo FormInput('phone2',     $user['phone2']);
	if(IsBoss()) {
		echo "<dl>\n";
		echo "  <dt>Account:</dt><dd>$uid</dd>\n";
		echo "  <dt>Created:</dt><dd>". DateHTML($user['created']) ."</dd>\n";
		echo "  <dt>Visited:</dt><dd>". DateHTML($user['visited']) ."</dd>\n";
		echo "</dl>\n";
	}
	echo "</article><article class=\"floatBox\">\n";
	echo FormInput('remarks', $user['remarks']);
?>
  <div class="centered">
    <label id="remember" title="Uncheck this if you are using a public computer."><input type="checkbox" name="remember"
      <?= ($user['autologin'] ? ' checked' : '')?>>Remember me on this computer.</label><br>
    <label id="notify" title="Uncheck this box if you are a curmudgeon."><input type="checkbox" name="notify"
      <?= ($user['notify'] ? ' checked' : '')?>>Notify me of special offers.</label>
  </div>

<?php if(!$uid) { //captcha  ?>
  <div class="captcha">
    <p>This website is for humans only.<br>
    To ensure you are not a robot,<br>
    select the <span class="human">CORRECT</span> answer.</p>
<?php
$iHuman = rand(0,8);
for($i = 0;  $i < 9;  $i++) {
	if($i === $iHuman) {
		$sel = (isset($_POST['captcha'])  &&  $_POST['captcha'] === 'human')  ?  'checked' : 'required';
		echo '<label class="human"><input type="radio" name="captcha" value="human" '.$sel.'>CORRECT</label>';
	} else
		echo '<label class="bot"><input type="radio" name="captcha" value="bot">wrong</label>';
	echo "\n";
}
echo "  </div>\n";
}

//show user flags
echo '<div class="centered">';
foreach(USER_FLAG_DESC as $key=>$desc) {
	if($key === 'Default')  continue;
	if(HasFlag($key, $user['flags']))
		echo "<div title=\"{$desc[1]}\">{$desc[0]}</div>\n";
}
echo "</div>\n";

//boss flags
function FlagCheckbox($flag) {
	global $user;
	$isCheck = HasFlag($flag, $user['flags']) ? ' checked' : '';
	echo "  <label title=\"". USER_FLAG_DESC[$flag][1] ."\"><input type=\"checkbox\" name=\"$flag\"$isCheck>$flag</label>\n";
}

function BossOption($flag) {
	global $user;
	$isSelected = HasFlag($flag, $user['flags']) ? ' selected' : '';
	echo "  <option value=\"$flag\"$isSelected>$flag - ". USER_FLAG_DESC[$flag][1] ."</option>\n";
}

if(IsBoss('Manager')) { //only Admin can modify user flags
	echo "<div id=\"flag_boxes\">\n";
	FlagCheckbox('Friend');  FlagCheckbox('Family');   FlagCheckbox('Wholesale');
	FlagCheckbox('Banned');  FlagCheckbox('Watched');  FlagCheckbox('Inactive');
	FlagCheckbox('Thunker'); FlagCheckbox('Coder');    FlagCheckbox('Book');
	FlagCheckbox('Member');  FlagCheckbox('Premium');  FlagCheckbox('Monkey');
	echo "</div>\n";

	if(IsBoss('Admin')  &&  !IsBoss('Dev', $user['flags'])) { //nobody can modify Dev status
		echo "<select name=\"boss\">\n";
		echo "  <option value=\"Customer\">Customer - No employee access</option>\n";
		BossOption('Intern');
		BossOption('Shipper');
		BossOption('Cashier');
		BossOption('Manager');
		BossOption('Admin');
		echo "</select>\n";
	}
}
if(IsBoss('Cashier'))  echo FormInput('notes', $user['notes']);
?>
  <button type="submit" class="blueBtn"><?php echo ($uid ? 'Update Account' : 'Create Account')?></button>
</article>
</section>
</form>

<?php include "{$_SERVER['DOCUMENT_ROOT']}/inc/footer.php"; ?>
