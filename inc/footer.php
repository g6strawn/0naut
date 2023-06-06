<?php //Copyright (c) 2022 Gary Strawn, All rights reserved
if(count(get_included_files())==1)  exit; //direct access not permitted

if(!isset($g_noHdrFtr)) { ?>
  <footer id="siteFtr">
  <hr>
  <a href="/"><img src="/img/ftrlogo.png" id="ftrLogo" class="spin"></a>
  <button id="toTop" title="Back to top" class="blueBtn round"
  	onclick="window.scrollTo({top:0, left:0, behavior:'smooth'})">^</button>
  <div id="ftrCopyright">Copyright &copy; 2015-<?=date('Y') .' <a href="/">'. $g_siteName?></a><br>
    All rights reserved
  </div>
</footer>
<br><?php } ?>

<?php if(!IsSignedIn()  &&  !isset($g_noHdrFtr)) { ?>
<!-- Login dialog -->
<template id="loginTemplate">
<div id="loginBox" class="modal"><div>
  <button class="blueBtn closeBtn" onclick="SkipLogin()">&times;</button>
  <h3 id="loginHdr">Sign In</h3>
  <img id="loginWait" src="/img/wait.gif" class="center" style="display:none">
  <form onsubmit="return SubmitLogin(this)">
    <input id="loginEmail" name="email" type="email" placeholder="email" 
      maxlength=100 required autocomplete="email">
    <input id="loginPswd" name="pswd" type="password" placeholder="password" 
      minlength=4 maxlength=100 required autocomplete="current-password">
    <label id="loginRemember"><input name="remember" type="checkbox" checked>Remember&nbsp;Me</label>
    <button id="loginSubmit" type="submit" class="blueBtn">Sign&nbsp;In</button>
    <a href="/account.php" id="loginCreate" class="left" title="Create a new account">Register</a>
    &nbsp;
    <a href="" id="loginForgot" class="right" title="Reset your password"
      onclick="return SubmitLogin()">Forgot password</a>
  </form>
  <p id="loginOptional" title="Press ESC to skip">* Sign In is optional.</p>
</div></div>
</template>
<?php } ?>

</body>
</html>
<?php
ob_end_flush();  //close output buffer
if(IsSignedIn()  ||  isset($_SESSION['anon']))  LogHit();
?>
