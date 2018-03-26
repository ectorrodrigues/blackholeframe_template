<?php
	unset($_COOKIE['sessao']);
	setcookie('sessao', null, -1, '/');
	setcookie("sessao", "", time()-3600);

	
	unset($_COOKIE['login']);
	setcookie('login', null, -1, '/');
	setcookie("login", "", time()-3600);
	
	echo 'removido';
?>