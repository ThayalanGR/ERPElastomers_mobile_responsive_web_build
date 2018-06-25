<?php
$secret = 'secret';
$hashing_algorithm = 'sha1';

/*
  myDBR calls this with url + token + hash, where hash == hash('shaX' url + $token + secret)
*/
if (isset($_REQUEST['url'])) {
  $token = $_REQUEST['token'];
  $url = $_REQUEST['url'];
  $hash = isset($_REQUEST['hash']) ? $_REQUEST['hash'] : '';
  if (hash($hashing_algorithm, $url.$token.$secret)!=$hash) {
    die();
  }
}

$user = isset($_REQUEST['user']) ? $_REQUEST['user'] : 'ssouser' ;
$name = isset($_REQUEST['name']) ? $_REQUEST['name'] : 'Single Sign-On user' ;
$groups = isset($_REQUEST['groups']) ? $_REQUEST['groups'] : 'ssogroup1|ssogroup2' ;
$email = isset($_REQUEST['email']) ? $_REQUEST['email'] : 'ssouser@mycompany.com' ;
$telephone = isset($_REQUEST['telephone']) ? $_REQUEST['telephone'] : '+358 123 2345' ;
$admin = isset($_REQUEST['admin']) ? $_REQUEST['admin'] : '0';

/*
  If extra parameters are used (extra1, extra2...), the corresponding automatic parameter needs
  to be defined in mydbr/user/defaults as:
   $mydbr_defaults['automatic_parameters']['sso_extra1'] = 'in_SSO_OrganizationID';
   $mydbr_defaults['automatic_parameters']['sso_extra2'] = 'logout_url';
*/
$extra1 = isset($_REQUEST['extra1']) ? $_REQUEST['extra1'] : '' ;
$extra2 = isset($_REQUEST['extra2']) ? $_REQUEST['extra2'] : '' ;

// In example extra fields are optional based on user choise
if (isset($_REQUEST['noextra1'])) {
  $extra1 = '';
}
if (isset($_REQUEST['noextra2'])) {
  $extra2 = '';
}


/*
  Handle the login form submit and redirect back to myDBR
*/
if ( @$_REQUEST['continue'] )
{
  $token = $_REQUEST['token'];
  $url = $_REQUEST['mydbr_url'];
  
  // User's email is optional
  if (isset($_REQUEST['noemailchange'])) {
    $email = '';
  }
  else {
    // User's email can be erased by setting blank email address
    $email = isset($_REQUEST['email']) ? $_REQUEST['email'] : '' ;
  }
  // User's telephone is optional
  if (isset($_REQUEST['notelephonechange'])) {
    $telephone = '';
  }
  else {
    // User's telephone can be erased by setting blank telephone address
    $telephone = isset($_REQUEST['telephone']) ? $_REQUEST['telephone'] : '' ;
  }
  // Group is optional
  if (isset($_REQUEST['nogroupchange'])) {
    $groups = '';
  }
  $hash = hash($hashing_algorithm, $user . $name . $groups . $email . $telephone . $admin . $extra1 . $extra2 . $token . $secret );

  $url .= '?user=' . urlencode($user) . '&name=' . urlencode($name) . '&hash=' . $hash;

  if (!isset($_REQUEST['nogroupchange'])) {
    $url .= '&groups=' . urlencode($groups);
  }

  if (!isset($_REQUEST['noemailchange'])) {
    $url .= '&email=' . urlencode($email);
  }
  
  if (!isset($_REQUEST['notelephonechange'])) {
    $url .= '&telephone=' . urlencode($telephone);
  }
  if (!isset($_REQUEST['noextra1'])) {
    $url .= '&extra1=' . urlencode($extra1);
  }
  if (!isset($_REQUEST['noextra2'])) {
    $url .= '&extra2=' . urlencode($extra2);
  }
  
  $url .= '&admin=' . $admin;
    
  header('Location:' . $url);
  die;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
  <title>SSO example</title>
  <style type="text/css">
    body { background-color: #EEEEEE; font: 12px Helvetica;}
    .login {border:1px solid gray;background:white; width: 600px;margin: 30px auto 0px auto;box-shadow: 0 0 10px #CCC;border-radius: 5px;}
    fieldset{border:none;}
    fieldset ul{margin 0;padding:0;}
    fieldset li{list-style:none;}
    fieldset li label{display:inline-block;vertical-align:top;width:100px;padding:5px 0px;}
    fieldset li label.right {width:auto;}
    fieldset li label.normal{display:static;width:inherit;}
    fieldset li div{display: inline-table;padding:5px 0px;}
    input.wide { width: 350px;}
    input { margin: 2px;}
  </style>
</head>
<body>
  <form action="<?php echo $_SERVER['PHP_SELF'];?>">
    <div class="login">
      <fieldset>
      <ul>
        <li><label class="right" style="font-weight:bold;">myDBR SSO login example</label></li>
        <li>
          <label for="u">User: </label><input id="u" type="text" name="user" value="<?php echo htmlspecialchars($user) ?>">
          <input id="ad" type="checkbox" name="admin" value="1" ><label class="right" for="ad">Admin</label></li>
        <li>
          <label for="n">Name: </label><input id="n" type="text" name="name" value="<?php echo htmlspecialchars($name) ?>"></li>
        <li>
          <label for="e">Email: </label><input id="e" type="text" name="email" value="<?php echo htmlspecialchars($email) ?>">
          <input id="ne" type="checkbox" name="noemailchange" value="No" ><label class="right" for="ne">No changes to user's email</label></li>
        <li>
          <label for="e">Telephone: </label><input id="e" type="text" name="telephone" value="<?php echo htmlspecialchars($telephone) ?>">
          <input id="ne" type="checkbox" name="notelephonechange" value="No" ><label class="right" for="ne">No changes to user's telephone number</label></li>
        <li>
          <label for="g">Group(s): </label><input id="g" type="text" name="groups" value="<?php echo htmlspecialchars($groups) ?>">
          <input id="ng" type="checkbox" name="nogroupchange" value="No" ><label class="right" for="ng">No changes to user's groups</label>
        </li>
        <li>
          <label for="extra1">Extra1: </label><input id="extra1" type="text" name="extra1" value="<?php echo htmlspecialchars($extra1) ?>">
          <input id="noextra1" type="checkbox" name="noextra1" value="No" ><label class="right" for="noextra1">No extra information</label>
        </li>
        <li>
          <label for="extra2">Extra2: </label><input id="extra2" type="text" name="extra2" value="">
          <input id="noextra2" type="checkbox" name="noextra2" value="No" ><label class="right" for="noextra2">No extra information</label>
        </li>
        <li><label for="t">Token: </label><input id="t" class="wide" type="text" name="token" value="<?php echo htmlspecialchars($token) ?>"></li>
        <li><label for="url">Url: </label><input id="url" class="wide" type="text" name="mydbr_url" value="<?php echo htmlspecialchars($url) ?>"></li>
        <li><input type="submit" name="continue" value="Login"></li>
      </ul>
      </fieldset>
    </div>
  </form>
</body>
</html>