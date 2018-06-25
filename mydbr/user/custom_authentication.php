<?php

class Custom_authentication
{
  /*
  Return an array with following elements when authentication is successful, otherwise return false
  
  $user['name']: username
  $user['admin']: 1=admin, 0=normal user
  $user['groups']: array of groupnames user belongs to. Example: array('Sales', 'HR', 'Production'), null if groups are not handled here but inside myDBR
  $user['email']: optional user's email
  $user['telephone']: optional user's telephone number
  */

  static function authenticate($username, $password)
  {
    /* Add your own code */
    $login_ok = true;

    if (!$login_ok) return null;

    return array(
      'name' => 'My name',
      'admin' => 0,
      'groups' => null,
      'email' => 'custom@custom.com',
      'telephone' => '+358123456789',
    );
  }

}
