<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <link rel="shortcut icon" href="images/mydbr_fav.png?v=596" type="image/png" alt="myDBR">
  <title>myDBR Tools</title>
</head>
<body>

<div>
  <img src="../images/apppic_small.jpg">
</div>
<h2>Translation tool</h2>

<p>
  <a href="localization.php">Localization tool</a> tool will help you translate myDBR into your own language. Go a head, give it a try.
</p>

<h2>Extensions loaded</h2>

<?php
require_once('troubleshoot/extensions.php');
?>

<h2>Problems connecting to the database?</h2>

<p>
  See test connection scripts in mydbr/tools/troubleshoot-directory
</p>

<h2>ChartDirector</h2>

<p>
  <a href="troubleshoot/ChartDirector.php">ChartDirector test</a>
</p>

</body>
</html>