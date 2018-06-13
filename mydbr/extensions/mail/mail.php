<?php
require_once('lib/external/PHPMailer/PHPMailerAutoload.php');


/*
    Copyright mydbr.com http://www.mydbr.com
    You are free to modify this file
*/

/*
    This is the function described in extensions.php
    $id: object id in the report
    $options: command options described in extensions.php cmds, contains the parsed values from the report
    $dataIn: Array of: recipient_email,recipient_name,subject,body
    $colInfo: column info - not needed in mail
*/

function Ext_Mail_AddrType($type)
{
  switch (strtolower($type)) {
    case 'cc':
      return 'AddCC';
      break;
    case 'bcc':
      return 'AddBCC';
    default:
      return 'AddAddress';
      break;
  }
}

function Ext_Mail( $id, $options,  $dataIn, $colInfo )
{
  global $mydbr_mail;

  $mail_extension = new Extension;

  $attach_error = '';
  $errorcnt = 0;
  ob_start();

  $mail = new PHPMailer();
  $mail->CharSet = 'UTF-8';

  $mail->IsSMTP(); // telling the class to use SMTP

  $mailer = Extension::get_mail_setting('mailer');
  switch ($mailer) {
    case 'mail':
      $mail->isMail();
      break;
    case 'sendmail':
      $mail->isSendmail();
      break;
    case 'qmail':
      $mail->isQmail();
      break;
    default:
      if (isset($options['dbr.mail.smtp.server']['host'])) {
        $mail->Host = $options['dbr.mail.smtp.server']['host'];
        $mail->Port = $options['dbr.mail.smtp.server']['port'];
        $mail->SMTPSecure = isset($options['dbr.mail.smtp.server']['secure']) ? $options['dbr.mail.smtp.server']['secure'] : '';
      } else {
        $mail->Host = Extension::get_mail_setting('host');
        $mail->Port = Extension::get_mail_setting('port');
        $mail->SMTPSecure = Extension::get_mail_setting('secure');
      }
      if (Extension::get_mail_smtp_ipv4()) {
        // If IPV6 SMTP is blocked, use: $mydbr_defaults['mail']['force_ipv4'] = true;
        if ($mail->SMTPSecure) {
          // Now that we will be using IP instead of name, we'll use the given host as SSL peer_name option
          $mail->SMTPOptions = array( 'ssl' => array( 'peer_name' => $mail->Host ) );
        }
        // Convert the hostname to IPv4 
        $mail->Host = gethostbyname($mail->Host);
      }
      $mail->SMTPKeepAlive = true; // SMTP connection will not close after each email sent
      $mail->SMTPDebug = isset($options['dbr.mail.debug']['debug']) ? $options['dbr.mail.debug']['debug'] : Extension::get_mail_setting('debug');

      $username = null;
      $password = null;

      if (isset($options['dbr.mail.smtp.ssl'])) {
        if (is_array($options['dbr.mail.smtp.ssl']['option'])) {
          for ($i=0; $i < sizeof($options['dbr.mail.smtp.ssl']['option']); $i++) { 
            $value = $options['dbr.mail.smtp.ssl']['value'][$i];
            if ($value == 'false' || $value == 'true') {
              $value = ($value == 'true');
            }
            $mail->SMTPOptions['ssl'][$options['dbr.mail.smtp.ssl']['option'][$i]] = $value;
          }
        } else {
          $value = $options['dbr.mail.smtp.ssl']['value'];
          if ($value == 'false' || $value == 'true') {
            $value = ($value == 'true');
          }
          $mail->SMTPOptions['ssl'][$options['dbr.mail.smtp.ssl']['option']] = $value;
        }
      }
      if (isset($options['dbr.mail.smtp.user']['user']) && isset($options['dbr.mail.smtp.user']['password'])) {
        $username = $options['dbr.mail.smtp.user']['user'];
        $password = $options['dbr.mail.smtp.user']['password'];
      } else {
        $username = Extension::get_mail_setting('username');
        $password = Extension::get_mail_setting('password');
      }
      // Enable SMTP authentication?
      if ($username && $password) {
        $mail->Username = $username;
        $mail->Password = $password;
        $mail->SMTPAuth = true;
      }
      // Additional options
      if (isset($options['dbr.mail.smtp.authentication.type']['type']) &&
        in_array($options['dbr.mail.smtp.authentication.type']['type'], array('LOGIN', 'PLAIN', 'NTLM', 'CRAM-MD5')) ) {

        $mail->AuthType = $options['dbr.mail.smtp.authentication.type']['type'];
      }
      if (isset($options['dbr.mail.smtp.nlmt.realm']['realm'])) {
        $mail->Realm = $options['dbr.mail.smtp.nlmt.realm']['realm'];
      }
      if (isset($options['dbr.mail.smtp.nlmt.workstation']['workstation'])) {
        $mail->Workstation = $options['dbr.mail.smtp.nlmt.workstation']['workstation'];
      }
      break;
  }
  $notify_successful_mail = Extension::get_mail_setting('notify_successful_mail');
  if (isset($options['dbr.mail.notify_successful_mail']['noyes'])) {
    $notify_successful_mail = $options['dbr.mail.notify_successful_mail']['noyes'];
  }
  // Is the email address defined in the report?
  if (isset($options['dbr.mail.sender']['email'])) {
    $email = $options['dbr.mail.sender']['email'];
    $name = isset($options['dbr.mail.sender']['name']) ? $options['dbr.mail.sender']['name'] : '';
    $replyto_email = isset($options['dbr.mail.sender']['replyto_email']) ? $options['dbr.mail.sender']['replyto_email'] : $email;
    $replyto_name = isset($options['dbr.mail.sender']['replyto_name']) ? $options['dbr.mail.sender']['replyto_name'] : $name;
  } else {
    $email = Extension::get_mail_setting('from_email');
    $name = Extension::get_mail_setting('from_name');
    $replyto_email = Extension::get_mail_setting('replyto_email');
    $replyto_name = Extension::get_mail_setting('replyto_name');
  }
  if (isset($options['dbr.mail.encoding']['encoding'])) {
    $mail->Encoding = $options['dbr.mail.encoding']['encoding'];
  }

  $mail->AllowEmpty = true;
  $mail->SetFrom($email, $name);
  $mail->AddReplyTo($replyto_email, $replyto_name);

  if (isset($options['dbr.mail.attach']) && isset($options['dbr.mail.attach']['report'])) {
    $ignore_ssl = false;
    if (is_array($options['dbr.mail.attach']['report'])) {
      for ($i=0; $i < sizeof($options['dbr.mail.attach']['report']); $i++) {
        if (isset($options['dbr.mail.attach']['ignore_ssl'])) {
          $ignore_ssl = $options['dbr.mail.attach']['ignore_ssl'];
        }
        $mail_extension->mail_add_report_attachments($mail, $options['dbr.mail.attach']['name'][$i], $options['dbr.mail.attach']['report'][$i], $ignore_ssl, $attach_error );
      }
    } else {
      $ignore_ssl = isset($options['dbr.mail.attach']['ignore_ssl']) ? $options['dbr.mail.attach']['ignore_ssl'] : false;

      $mail_extension->mail_add_report_attachments($mail, $options['dbr.mail.attach']['name'], $options['dbr.mail.attach']['report'], $ignore_ssl, $attach_error );
    }
  }

  for ($i=0; $i<sizeof($dataIn) ; $i++) {    // send the mail one by one
    // Avoiding to overload the server
    set_time_limit(30); // sets (or resets) maximum  execution time to 30 seconds

    // Do not send mails too fast (mail server might refuse to send the mails)
    if ( ($i % Extension::get_mail_setting('sleep_after_rows')) == 0) {
      usleep(Extension::get_mail_setting('sleep_microsec'));
    }
    $mail->Subject = $dataIn[$i][2];

    if (isset($options['dbr.mail.recipient'])) {
      if (is_array($options['dbr.mail.recipient']['email'])) {
        for ($recip=0; $recip < sizeof($options['dbr.mail.recipient']['email']); $recip++) {
          $value = $options['dbr.mail.recipient'];
          $addr_type = Ext_Mail_AddrType($options['dbr.mail.recipient']['cc_bcc'][$recip]);
          $mail->$addr_type($value['email'][$recip], $value['name'][$recip]);
        }
      } else {
        $value = $options['dbr.mail.recipient'];
        $addr_type = Ext_Mail_AddrType($value['cc_bcc']);
        $mail->$addr_type($value['email'], $value['name']);
      }
    }

    if (strpos($dataIn[$i][0], ',')!==false) {
      $addresses = explode(',', $dataIn[$i][0]);
      foreach ($addresses as $address) {
        $mail->AddAddress($address, '');
      }
    }
    if ($dataIn[$i][0]) {
      $mail->AddAddress($dataIn[$i][0], $dataIn[$i][1]);
    }

    $body = (isset($dataIn[$i][3]) ? $dataIn[$i][3] : '');

    if (isset($options['dbr.mail']['HTML']) && $options['dbr.mail']['HTML']==1) {
      /* Get HTML content */
      $record = $mail_extension->get_recording();
      $mail->AltBody = '';
      if ($record) {
        /* Attach images as attachments */
        $record = $mail_extension->mail_add_attachments( $record, $mail );

        /* Outlook uses Word HTML parser which does not support multiple css classes -> fixing it */
        $replace_from = array(
          'class="cell align_l',
          'class="cell align_r',
          'class="cell_header align_l',
          'class="cell_header align_r'
        );
        $replace_to = array(
          'class="cell_left align_l',
          'class="cell_right align_r',
          'class="cell_header_left align_l',
          'class="cell_header_right align_r'
        );
        $record = str_replace($replace_from, $replace_to, $record);

        /* Apply CSS styles to mail elements */
        $hdr = file_get_contents('extensions/mail/mail_header.html');
        $user_mail_css = 'user/mail.css';
        if (file_exists($user_mail_css)) {
          $hdr .= file_get_contents($user_mail_css);
        }
        $hdr .= chr(10).'</style>'.chr(10).'</head>'.chr(10).'<body>';
        $mail->MsgHTML($hdr.$record.'</body></html>');
      } else {
        $mail->MsgHTML(nl2br($body));
      }
    } else {
      $mail->Body = $body;
      $mail->IsHTML(false);
    }
    if (!$mail->Send()) {
      $errorcnt++;
    }
    $mail->ClearAddresses();
  }
  $mail->SmtpClose();
  $mail_extension->mail_clear_attachments();


  $captured = ob_get_contents();
  ob_end_clean();

  if ($attach_error) {
    $errorcnt++;
  }

  if ($mail->SMTPDebug>0) {
    echo '<div class="box debug_code"><pre>'.
      '<b>Debug</b><br><br>'.
      ($captured ? htmlentities($captured) : $mail->ErrorInfo).
      '</pre></div>';
  }
  if ($attach_error) {
    $mail->ErrorInfo .= ' Attachment error: '.$attach_error;
  }
  
  $msg = 'Sent '.sizeof($dataIn).' mail'.(sizeof($dataIn)>1 ? 's' : '').' with ' . ($errorcnt ? $errorcnt.' errors' : 'no errors');
  if ($notify_successful_mail) {
    echo '<div class="comment">'. $msg . ($errorcnt ? ('<div style="margin-top: 20px">'.$mail->ErrorInfo.'</div>') : '') .'</div>';
  }
  if (isset($options['dbr.mail.log.proc']['procedure'])) {
    $mail_extension->mail_log( $options['dbr.mail.log.proc']['procedure'], $msg, $errorcnt ? $mail->ErrorInfo : '' );
  }
}
