<?php

global $ini_file, $thread_safe, $os, $compiler;

function get_zip_links()
{
  global $thread_safe, $os, $compiler;

  $os = strtolower(substr(php_uname('s'),0,3));
  if ( !in_array( $os, array( 'dar', 'lin', 'win', 'sun', 'ope', 'fre' )) )
    return '';

  $name = 'http://downloads2.ioncube.com/loader_downloads/ioncube_loaders_' . $os . '_';
  if ($os=='win') {
    if (!$thread_safe )
      $name .= 'nonts_';
    $name .= strtolower($compiler).'_';
  }
  $arch = php_uname('m');
  if ( !preg_match( '/86/', $arch ))
  {
    return '';
  }
  $name .= 'x86';

  if ($os!='win') {
    $wordsize = ((-1^0xffffffff) ? 64 : 32);
    if ($wordsize == 64) {
      $name .= '-64';
    }
  }

  $link = 'Download the latest loader installation package for ';
  switch ($os)
  {
    case 'sun':
      $link .= '<a href="' . $name . '.zip">Solaris</a>';
      break;

    case 'ope':
      $link .= '<a href="' . $name . '.zip">OpenBSD</a>';
      break;

    case 'fre':
      $link .= '<a href="' . $name . '.zip">FreeBSD</a>';
      break;

    case 'win':
      $link .= '<a href="' . $name . '.zip">Windows</a>';
      break;

    case 'dar':
      $link .= '<a href="' . $name . '.zip">Mac OS X</a>';
      break;

    case 'lin':
      $link .= '<a href="' . $name . '.zip">Linux</a>';
      break;

    default:
      $link = '';
  }
  if ( $link )
    $link = '<p>' . $link . '</p>';
  return $link;
}

function get_system_info()
{
  global $ini_file, $thread_safe, $compiler;

  $thread_safe = false;
  $compiler = (substr(PHP_OS, 0, 3) == 'WIN') ? 'VC6': '';
  ob_start();
  phpinfo(INFO_GENERAL);
  $php_info = ob_get_contents();
  ob_end_clean();

  $breaker = (php_sapi_name() == 'cli')?'\n':'</tr>';
  $lines = explode($breaker,$php_info);

  foreach ($lines as $line) {
    if (preg_match('/thread safety/i', $line)) {
      $thread_safe = (preg_match('/(enabled|yes)/i', $line) != 0);
    }

    if (preg_match('~configuration file.*(</B></td><TD ALIGN="left">| => |v">)([^ <]*)~i',$line,$match)) {
      $ini_file = $match[2];

      if (!@file_exists($ini_file)) {
        $ini_file = '';
      }
    }
    if ($compiler && preg_match('/compiler/i',$line)) {
      $supported_match = 'VC6|VC9|VC11';
      $is_supported_compiler = preg_match("/($supported_match)/i",$line);
      if (preg_match("/(VC[0-9]+)/i",$line,$match)) {
        $compiler = strtoupper($match[1]);
      } else {
        $compiler = '';
      }
    }

  }
}

function ioncube_loader_version_information()
{
  if (extension_loaded('ionCube Loader')) {
    $old_version = true;
    $liv = "";
    $lv = "Unknown version";
    if (function_exists('ioncube_loader_iversion')) {
      $liv = ioncube_loader_iversion();
      $lv = sprintf("%d.%d.%d", $liv / 10000, ($liv / 100) % 100, $liv % 100);

    }
    return '<p class="topic" style="padding: 10px;color:green;">You have ionCube loader version '.$lv.' installed.<br>You may still want to check that you have the <a href="http://www.ioncube.com/loaders.php" target="_blank">lastest version</a> installed.</p>';
  }
  return '';
}

get_system_info();
$download_link = get_zip_links();

$loader_name = 'ioncube_loader_' . $os . '_' . substr(phpversion(),0,3) .($thread_safe  ?'_ts' : '') . ( ($os=='win') ? '.dll' : '.so' );

$extension_dir = realpath( ini_get( 'extension_dir' ) );
if ( $extension_dir == '' )
  $extension_dir = '&lt;php extension dir&gt;';
else
  $extension_dir .= DIRECTORY_SEPARATOR;

if ( $ini_file == '' )
  $ini_file = '&lt;php.ini&gt;';

?><!DOCTYPE html>
<html>
<head>
  <title>myDBR Install</title>
  <link rel="stylesheet" href="install.css" type="text/css" media="screen" title="no title">
  <link rel="stylesheet" href="install/install.css" type="text/css" media="screen" title="no title">
  <!--[if IE]>
  <link href="ie.css" rel="stylesheet" type="text/css">
  <link href="install/ie.css" rel="stylesheet" type="text/css">
  <![endif]-->
</head>
<body>
<div id="header_noc">
  <div id="header_content" class="fixed">myDBR Installation - PHP Loader
  </div>
</div>

<div id="content" style="margin-top: 40px;">
  <p>
    Before you can start using myDBR the ionCube loader needs to be installed on your server. myDBR is a compiled
    application that can only function with the ionCube loader installed.
  </p>
  <p>
    ionCube does provide an excellent <a href="http://www.ioncube.com/loaders.php" target="_blank" >Loader Wizard PHP script</a> which you can try if the instructions below do not work for you.
  </p>
  <p>
    The file that redirected to this page: <b><?php echo $_SERVER['SCRIPT_NAME'];  ?></b>
  </p>

  <?php echo ioncube_loader_version_information(); ?>
  <table class="install_tests" style="margin-top: 40px;">
    <tbody>
    <tr>
      <td class="topic">Download ionCube loader</td>
      <td class="todo">ToDo</td>
    </tr>
    <tr>
      <td class="details" colspan="2">
        <?php if ( $download_link == '' ) : ?>
          <p>Please go to <a href="http://www.ioncube.com/loaders.php" target="_blank" >ioncube.com</a> and download the
            loader appropriate for your server configuration.
          </p>
        <?php else :?>
          <?php echo get_zip_links() ?>
          <p>If the above link is incorrect, please visit <a href="http://www.ioncube.com/loaders.php" target="_blank">ioncube.com</a> for
            a complete list of loaders and download the package appropriate for your server configuration
          </p>
        <?php endif;?>
      </td>
    </tr>

    <tr>
      <td class="topic">Copy loader to extension directory</td>
      <td class="todo">ToDo</td>
    </tr>

    <tr>
      <td class="details" colspan="2">
        <p>From the ionCube installation package copy the file
          <code><?php echo $loader_name ?></code> to directory <code><?php echo $extension_dir?></code></p>
      </td>
    </tr>

    <tr>
      <td class="topic">Add loader to php.ini</td>
      <td class="todo">ToDo</td>
    </tr>

    <tr>
      <td class="details" colspan="2">
        <p>Add the following line to <code><?php echo $ini_file?></code></p>
        <pre class="term">zend_extension<?php
          echo ($thread_safe && !version_compare(phpversion(), '5.3.0', 'ge') ? '_ts' : ''); ?> = <?php echo (($os=='win') ? '"' : '') . $extension_dir .  $loader_name .(($os=='win') ? '"' : '')?></pre>
      </td>
    </tr>

    <tr>
      <td class="topic">Restart your web-server</td>
      <td class="todo">ToDo</td>
    </tr>

    <tr>
      <td class="details" colspan="2">
        <p>Finally, restart the web-server and reload this page.</p>
        <p>Please consult the ionCube installation guide for further information</p>
      </td>
    </tr>

    </tbody>
  </table>
  <div class="buttons">
    <a href="" class="button">Retry</a>
  </div>
</div>
</body>
</html>
<?php die(); ?>