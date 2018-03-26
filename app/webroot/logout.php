<?php
define('DS', DIRECTORY_SEPARATOR);
include ('..' . DS . 'config' . DS . 'directories.php');

setcookie("login", "", time() - 3600);
unset($_COOKIE['login']);
unset($_COOKIE['login']);
setcookie('login', null, -1, '/');
setcookie('login', null, -1, '/');

header('Location:'.ROOT);

?>