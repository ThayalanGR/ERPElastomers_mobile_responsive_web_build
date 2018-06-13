<?php

abstract class SMSSender {

  public $errors;
  public $simulate;


  /**
   * Creates a new sender based on hash passed as parameters
   *
   * @param hash $params
   * @return void
   */
  static function create( $params ) {
    $result = null;
    if ($params['sender'] == 'clickatell' ) {
      $result = new ClickatellSender( $params['user'], $params['pass'], $params['api_id'] );
    }
    return $result;
  }


  /**
   * Delivers a message to a recipient.
   *
   * @param string $recipient 	Telephone number for the recipient, format according to SMS gateway
   * @param string $message 		Message to be delivered
   * @param string $from 			Optional "from" for message, might not be supported by all gateways
   * @return boolean				true if message is send, false if errors occurred, Error message is stored in $errors
   */
  abstract function deliver( $recipient, $message, $from = null );


}

class ClickatellSender extends SMSSender {

  protected $base_url = 'http://api.clickatell.com/http';
  protected $authentication_url;
  protected $session_id;

  /**
   * Creates url for authentication
   *
   * @param string $user
   * @param string $password
   * @param string $api_id
   */
  function __construct( $user, $password, $api_id )	{
    $this->authentication_url = $this->base_url . '/auth?user=' . urlencode( $user) . '&password=' .
      urlencode( $password ) . '&api_id=' .urlencode( $api_id );
  }

  /**
   * Authenticates with clickatell gateway and stores session_id
   *
   * @return void
   */
  function authenticate() {
    $data = file( $this->authentication_url );
    $data = explode(":",$data[0]);
    if ($data[0] == "OK" ) {
      $this->session_id = trim( $data[1] );
      return true;
    } else {
      $this->errors = "Authentication failure: ". $data[0];
      return false;
    }
  }

  /**
   * Delivers a message
   *
   * @param string $recipient 	In international format without + in the beginning
   * @param string $message 		Message
   * @param string $from 			Sender ID, must be requested from Clickatell
   * @return boolean				weather delivery is successful
   */
  function deliver( $recipient, $message, $from = null ) {
    if ( $this->session_id == null && $this->errors == null && !$this->simulate )
      $this->authenticate();
    if ( $this->errors == null ) {
      // convert to latin-1
      $message = mb_convert_encoding( $message, 'ISO-8859-1', mb_detect_encoding( $message) );

      $url = $this->base_url . "/sendmsg?session_id=" . $this->session_id . "&to=$recipient&text=" . urlencode( $message );
      if ( $from )
        $url .= '&from=' . urlencode( $from );

      if ( $this->simulate ) {
        echo "<p>" . $url .  "</p>";
        $send = array( "ID: none" );
      } else {
        $send = file( $url );
      }
      $send = explode( ":", $send[0] );
      if ( $send[0] == "ID")
        return true;
      else {
        $this->errors = "Send message failed" . $send[1];
        return false;
      }
    } else {
      return false;
    }
  }


}


/**
 * Sends SMS Messages.
 *
 * @param string $id
 * @param string $options
 * @param string $dataIn
 * @param string $colInfo
 * @return void
 */
function Ext_SMS( $id, $options,  $dataIn, $colInfo )
{
  global $mydbr_sms;

  if (!function_exists('mb_convert_encoding')) {
    echo '<div class="error">SMS extension requires that the <a href="http://www.php.net/manual/en/book.mbstring.php">Multibyte String</a> PHP support is installed.</div>';
    return;
  }

  $sender = SMSSender::create( $mydbr_sms );
  if ( $sender == null ) {
    echo '<div class="error">SMS sender ' . $mydbr_sms['sender'] . ' not found.</div>';
    return;
  }

  if ( isset($options['dbr.sms']['simulate'] ) )
    $sender->simulate = true;

  if ( isset($options['dbr.sms.sender']['from']))
    $from = $options['dbr.sms.sender']['from'];
  else
    $from = null;

  for ($i=0; $i<sizeof($dataIn) ; $i++) {
    if ( $sender->errors == null )
      $sender->deliver( $dataIn[$i][0], $dataIn[$i][1], $from );
  }

  if ($sender->errors != null) {
    $txt = $sender->errors;
  } else {
    $txt = $sender->simulate ? "Simulated " : "Sent ";
    $txt .= sizeof( $dataIn) . " messages";
  }
  echo '<div class="comment">'. $txt . '</div>';
}
