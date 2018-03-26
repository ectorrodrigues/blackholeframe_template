<?php

	//$x = explode("/",'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
	//$x = array_pop($x);
	//echo $x;

	//setcookie('login', '$1$H2Oc3po$Hf67niGYxzbBhQldDuou1.', time() + (14400 * 30), "/");

//echo $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];


if(!defined('DS')){ define('DS', '/');}

$sitename = explode(DS, $_SERVER['PHP_SELF']);
if(!defined('SITE')){ define('SITE', $sitename[1]);}

echo SITE;

?>

<!--
 <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>

<script>
$( document ).ready(function() {
    alert( "ready!" );
});
</script>
-->