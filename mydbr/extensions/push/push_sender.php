<?php

abstract class PushSender {
  public $errors = 0;
  public $error_text;
  public $options = array();
  public $app_api_token;
  public $valid_options;

  /**
   * Creates a new sender based on hash passed as parameters
   *
   * @param hash $params
   * @return void
   */
  static function create( $params, $options ) {
    
    $result = null;
    switch (strtolower($params['sender'])) {
      case 'pushbullet':
        $result = new PushbulletSender( $params['pushbullet_access_token'], $options );
        break;
      case 'pushover':
        $result = new PushoverSender( $params['pushover_app_api_token'], $options );
        break;
      case 'prowl':
        $result = new ProwlSender( $options );
        break;
      default:
        # code...
        break;
    }
    return $result;
  }
  /**
   * Delivers a message to a recipient.
   *
   * @param string $recipient   Recipient key
   * @param string $title       Message title
   * @param string $message     Message to be delivered
   * @return boolean            true if message is send, false if errors occurred, Error message is stored in $errors
   */
  abstract function deliver( $id, $recipient, $title, $message );
  
  function get_options( $cmd, $options )
  {
    $r = array();
    if (isset($options[$cmd])) {
      $cmd_options = $options[$cmd];
      for ($i=0; $i < sizeof($cmd_options); $i++) { 
        if (in_array($cmd_options['option'][$i], $this->valid_options)) {
          $r[$cmd_options['option'][$i]] = $cmd_options['value'][$i];
        }
      }
    }
    $this->options = $r;
  }
}

class PushbulletSender extends PushSender {
  
  function __construct( $access_token, $options )	{
    $this->errors = 0;
    $this->error_text = array();
    $this->app_api_token = $access_token;
    $this->valid_options = array('access_token', 'target');
    
    $this->get_options('dbr.push.option', $options);
  }
  
  /**
   * Delivers a message
   *
   * @param string $recipient 	In international format without + in the beginning
   * @param string $message 		Message
   * @return boolean				    whether delivery is successful
   */
  function deliver( $id, $recipient, $title, $message ) {

    if (isset($this->options['access_token'])) {
      $this->app_api_token = $this->options['access_token'];
    }
    $target = isset($this->options['target']) ? $this->options['target'] : 'email';

    // device_iden - Send the push to a specific device. Appears as target_device_iden on the push. You can find this using the /v2/devices call.
    // email - Send the push to this email address. If that email address is associated with a Pushbullet user, we will send it directly to that user, otherwise we will fallback to sending an email to the email address (this will also happen if a user exists but has no devices registered).
    // channel_tag - Send the push to all subscribers to your channel that has this tag.
    // client_iden - Send the push to all users who have granted access to your OAuth client that has this iden.
    
    if (!in_array($target, array('device_iden', 'email', 'channel_tag', 'client_iden'))) {
      // We need to report this just once
      if ($id==0) {
        $this->errors += 1;
        $this->error_text[$id][0] = 'Invalid target for Pushbullet';
      }
      return;
    }
    
    $postfields = array(
      $target => $recipient, 
      "type" => 'note', 
      "title" => $title,
      "body" => $message
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,            "https://api.pushbullet.com/v2/pushes" );
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
    curl_setopt($ch, CURLOPT_POST,           1 );
    curl_setopt($ch, CURLOPT_USERPWD,        $this->app_api_token.":");
    curl_setopt($ch, CURLOPT_POSTFIELDS,     json_encode($postfields) ); 
    curl_setopt($ch, CURLOPT_HTTPHEADER,     array('Content-Type: application/json')); 

    $result = curl_exec($ch);
    if ($result===false) {
      $this->errors += 1;
      $this->error_text[$id][0] = 'Connection to Pushbullet server failed';
    } else {
      $result = json_decode($result);
      if (isset($e->error->message)) {
        $this->errors += 1;
        $this->error_text[$id] = $e->error->message;
      }
    }
    curl_close($ch);
  }
}


class ProwlSender extends PushSender {
  
  function __construct( $options )	{
    $this->errors = 0;
    $this->error_text = array();
    $this->valid_options = array('providerkey', 'priority', 'application');
    
    $this->get_options('dbr.push.option', $options);
  }
  
  /**
   * Delivers a message
   *
   * @param string $recipient 	In international format without + in the beginning
   * @param string $message 		Message
   * @return boolean				    whether delivery is successful
   */
  function deliver( $id, $recipient, $title, $message ) {
    
    $param_max_len = array(
      'apikey'       =>    40,
      'providerkey'  =>    40,
      'priority'     =>     2,
      'application'  =>   254,
      'event'        =>  1024,
      'description'  => 10000,
    );
    
    $postfields = array(
      'apikey'      =>     $recipient,
      'priority'    =>     0,
      'application' =>     'myDBR',
      'event'       =>     $title,
      'description' =>     $message
    );
    
    foreach ($this->options as $key => $value) {
      $postfields[$key] = $value;
    }
    
    $ch = curl_init('https://api.prowlapp.com/publicapi/add' );
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_USERAGENT, "myDBR");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,10);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
    
    $result = curl_exec($ch);
    $result = new SimpleXMLElement($result);

    if (!isset($result->success)) {
      $this->errors += 1;
      if (isset($result->error['code'])) {
        switch($result->error['code']) {
          case 400: $this->error_text[$id][0] = 'Bad request, the parameters you provided did not validate.'; break;
          case 401: $this->error_text[$id][0] = 'Not authorized, the API key given is not valid, and does not correspond to a user.'; break;
          case 406: $this->error_text[$id][0] = 'Not acceptable, your IP address has exceeded the API limit.'; break;
          case 409: $this->error_text[$id][0] = 'Not approved, the user has yet to approve your retrieve request'; break;
          case 500: $this->error_text[$id][0] = 'Internal server error, something failed to execute properly on the Prowl side.'; break;
          default:  $this->error_text[$id][0] = 'Unkown error ('.$response->error['code'].')'; break;
        }
      } else {
        $this->error_text[$id][0] = 'Unkown error. No error code';
      }
    }
    
    curl_close($ch);
  }
}


class PushoverSender extends PushSender {
  
  function __construct( $app_api_token, $options )	{
    $this->errors = 0;
    $this->error_text = array();
    $this->app_api_token = $app_api_token;
    $this->valid_options = array('token', 'device', 'url', 'url_title', 'priority', 'timestamp', 'sound', 'html');
    
    $this->get_options('dbr.push.option', $options);
  }
  
  /**
   * Delivers a message
   *
   * @param string $recipient 	In international format without + in the beginning
   * @param string $message 		Message
   * @return boolean				    whether delivery is successful
   */
  function deliver( $id, $recipient, $title, $message ) {
    $postfields = array(
      "token" => $this->app_api_token,
      "user" => $recipient, 
      "title" => $title,
      "message" => $message,
    );
    foreach ($this->options as $key => $value) {
      $postfields[$key] = $value;
    }
    
    $ch = curl_init();
    curl_setopt_array(
      $ch, array(
        CURLOPT_URL => "https://api.pushover.net/1/messages.json",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POSTFIELDS => $postfields
      )
    );
    
    $result = curl_exec($ch);
    if ($result===false) {
      $this->errors += 1;
      $this->error_text[$id][0] = 'Connection to Pushover server failed';
    } else {
      $result = json_decode($result);
      if ($result->status!=1) {
        $this->errors += 1;
        $this->error_text[$id] = $result->errors;
      }
    }
    curl_close($ch);
  }
}


/**
 * Sends Push Messages.
 *
 * @param string $id
 * @param string $options
 * @param string $dataIn
 * @param string $colInfo
 * @return void
 */
function Ext_Push( $id, $options,  $dataIn, $colInfo )
{
  global $mydbr_push;
  
  $push_extension = new Extension;
  
  if (isset($options['dbr.push.sender']['sender'])) {
    $mydbr_push['sender'] = $options['dbr.push.sender']['sender'];
  }
  
  if (!isset($mydbr_push['sender'])) {
    echo '<div class="error">Define the $mydbr_push in extension_init.php.</div>';
    return;
  }

  $sender = PushSender::create( $mydbr_push, $options );
  
  if ( $sender == null ) {
    echo '<div class="error">Push sender ' . $mydbr_push['sender'] . ' not found.</div>';
    return;
  }
  if (sizeof($dataIn)>0 && sizeof($dataIn[0])>2) {
    for ($i=0; $i<sizeof($dataIn) ; $i++) {
      $sender->deliver( $i, $dataIn[$i][0], $dataIn[$i][1], $dataIn[$i][2] );
    }
  } else {
    $sender->errors++;
    $sender->error_text[0][0] = 'Push requires recipient, title and message as parameters';
  }

  $notify_successful_push = true;
  if (isset($options['dbr.push.notify_successful_push']['noyes'])) {
    $notify_successful_push = $options['dbr.push.notify_successful_push']['noyes'];
  }

  if ($sender->errors>0) {
    $txt = "Sent ".sizeof( $dataIn) . " messages with ".$sender->errors." errors";
    if ($sender->errors==1 && sizeof($sender->error_text) == 1) {
      $av = array_values($sender->error_text);
      $e = array_shift($av);
      $txt .= '. Error:'. $e[0]; 
    }
  } else {
    $txt = "Sent ".sizeof( $dataIn) . (sizeof($dataIn)>1 ? " messages" : " message");
  }
  
  if ($notify_successful_push) {
    echo '<div class="comment">'. $txt . '</div>';
  }
  
  if (isset($options['dbr.push.log.proc']['procedure'])) {
    $msg = '';
    for ($i=0; $i<sizeof($dataIn) ; $i++) {
      $msg .= 'Recipient: '.$dataIn[$i][0].', Status: '.(isset($sender->error_text[$i][0]) ? $sender->error_text[$i][0] : 'OK')."\n";
    }
    $push_extension->push_log( $options['dbr.push.log.proc']['procedure'], $txt, $msg );
  }
  
  if (isset($options['dbr.push.debug']['debug']) && $options['dbr.push.debug']['debug']) {
    echo '<div class="box debug_code"><pre><b>Debug</b><br><br>';
    
    for ($i=0; $i<sizeof($dataIn) ; $i++) {
      echo 'ID: '. ($i+1) . ', Recipient: '.$dataIn[$i][0].', Status: '.(isset($sender->error_text[$i][0]) ? $sender->error_text[$i][0] : 'OK')."\n";
    }
    echo '</pre></div>';
  }
}
