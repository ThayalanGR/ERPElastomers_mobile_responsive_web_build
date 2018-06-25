<?php
define('IN_PHPBB', true);
define( 'MYDBR_SECRECT', 'secret');
define('SSO_LOGO', '../../images/apppic_small.jpg');
define('HASHING_ALGORITHM', 'sha1');

$phpbb_root_path = '../../../phpBB3/';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);

function get_phpBB_groups($userid)
{
  global $db;
  
  $groups = array();
  $sql =  "select g.group_name from ".GROUPS_TABLE." g, ".USER_GROUP_TABLE." u ".
      "where g.group_id=u.group_id and u.user_id=".$userid." and g.group_name like 'myDBR%'";
  $result = $db->sql_query($sql);
  while ($row = $db->sql_fetchrow($result)) {
    $groups[] = $row['group_name'];
  }
  $db->sql_freeresult($result);
  return $groups;
}


function sso_finish($bbuser)
{
  $groups = '';
  $admin='0';
  $sep = '';
  $phpBB_groups = get_phpBB_groups($bbuser['user_id']);
  
  /* Is authenticated user a myDBR user? */
  if (sizeof($phpBB_groups)==0) {
    return;
  }
  
  /* Get the groups and separate admin */
  foreach ($phpBB_groups as $group) {
    if ($group=='myDBR Admins') {
      $admin='1';
    } else {
      $groups .= $sep.$group;
      $sep = '|';
    }
  }

  $user = $bbuser['username'];
  $name = $bbuser['username'];
  $email = $bbuser['user_email'];
  $token = $_REQUEST['token'];
  $url = $_REQUEST['url'];
  $hash = $_REQUEST['hash'];
  if ($hash!=hash(HASHING_ALGORITHM, $url.$token.MYDBR_SECRET)) {
    die();
  }

  $hash = sha1( $user . $name . $groups . $email . $admin .$token . MYDBR_SECRECT );
    
  $url = $url . '?user=' . urlencode($user) . '&name=' . urlencode($name) . '&hash=' . $hash .'&groups=' . urlencode($groups) . 
    '&email=' . urlencode($email) . '&admin=' . $admin;

  header('Location:' . $url);
  die();
  
}

if (!isset($_REQUEST['url'])) {
  die();
}

// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup();

$bad_login = '';
$do_login = true;

if ($user->data['is_registered'])
{
  sso_finish($user->data);
  $bad_login  = 'Invalid myDBR username \'' . $user->data['username'] . '\'<br>'; //User's login failed
}
else if(isset($_POST['login']))
{
  $do_login = false;
  $username = request_var('username', '', true);
  $password = request_var('password', '', true);
  $autologin = (!empty($_POST['autologin'])) ? true : false;

  $result = $auth->login($username, $password, $autologin);

  if ($result['status'] == LOGIN_SUCCESS) {
    sso_finish($result['user_row']);
  }

  $bad_login  = 'Invalid myDBR username1 \'' . $username . '\'<br>'; //User's login failed
  $do_login= true;
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title>
    phpBB3 SSO
  </title>
  <style type="text/css">
  #login {
    -moz-border-radius: 10px;
    -webkit-border-radius: 10px;
    border-radius: 10px;
    background: #82B4D5;
    border-spacing: 0px 0px;
    border-style: 1px solid gray;
    margin-left: auto;
    margin-right: auto;
    color: white;
  }
  td {
    padding-left: 10px;
    padding-right: 10px;
  }
  td.top {
    padding-top: 20px;
    padding-bottom: 20px;
  }
  td.bottom {
    padding-top: 10px;
    padding-bottom: 15px;
    text-align:center;
  }
  #login_button {
    width: 80px;
  }
  img {
    display: block;
    margin-left: auto;
    margin-right: auto;
    padding-bottom: 10px;
  }
  .center {
    text-align: center;
    margin-left: auto;
    margin-right: auto;
    padding-bottom: 10px;
  }
  .login_error {
    padding-top: 10px;
    padding-bottom: 10px;
    color: red;
    text-align: center;
  }
  </style>
</head>
<body>
  <form action="<?php echo $_SERVER['REQUEST_URI'] ? $_SERVER['REQUEST_URI']: $_SERVER['SCRIPT_NAME'] .=  '?' . $_SERVER['QUERY_STRING']; ?>" method="post">
    <img src="<?php echo SSO_LOGO; ?>">
    <?php
      if ($bad_login) {
        echo '<div class="login_error">'.$bad_login.'</div>';
      }
    ?>
    <div class="center">Welcome to myDBR Reporting</div>
    <table id="login" cellspacing="2">
      <tr><td class="top" colspan="2">phpBB3 Login</td></tr>
      <tr><td>Username</td><td><input size="20" class="LoginUser" id="username" name="username" type="text"></td></tr>
      <tr><td>Password</td><td><input size="20" class="LoginPass" id="password" name="password" type="password"></td></tr>
      <tr><td class="bottom" colspan="2"><input id="login_button" name="login" value="Login" type="submit"></td></tr>
    </table>
  </form>
</body>
</html>