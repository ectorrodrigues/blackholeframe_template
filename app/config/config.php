<?php

/** ______________________________________________________________________________________________________________
*
* MAIN 
* Here comes the MAIN configuration stuff
*/

if(!defined('DS')){ define('DS', '/');}

//SITE NAME
$sitename = explode(DS, $_SERVER['PHP_SELF']);
if(!defined('SITE_NAME')){ 
	define('SITE_NAME', $sitename[1]);
}

if(!isset($_COOKIE['site'])){
	setcookie('site', SITE_NAME, time() + (14400 * 30), "/");
} else {
	$site = $_COOKIE['site'];
}

define('SITE_TITLE', 'BlackHole');

//DIRECTORIES
$directories = 'app/config/directories.php';

if(file_exists($directories)){ 
	include ($directories); 
} 
else { 
	include ('../../..'. DS . 'config' . DS . 'directories.php'); 
}

//Automatic Update files to the newest version from CDN
$auto_update_appmodel 		= 'yes'; // yes or no *yes is default
$auto_update_adminmodel		= 'yes'; // yes or no *yes is default
$auto_update_form_helper	= 'yes'; // yes or no *yes is default
$auto_update_list_helper	= 'yes'; // yes or no *yes is default

//CMS
$cms	= 'cms'; //Table name where are stored the names of the Pages with CMS


/** ______________________________________________________________________________________________________________
*
* ADMIN 
* Here comes the ADMIN configuration stuff
*/

//FORM
# Form input types assumed by the form inputs (*If not defined below, the input will assume Text Type)
$array_fields_hidden	= array('id', 'id_cms', 'id_pedidos', 'sessao');
$array_fields_text		= array('titulo', 'preco');
$array_fields_number	= array('sku', 'preco');
$array_fields_select	= array('status', 'id_categorias', 'id_noticias', 'area', 'professor', 'coordenador', 'curso', 'cursos', 'status');
$array_fields_img		= array('img', 'foto', 'icone');
$array_fields_textarea	= array('descricao', 'texto', 'endereco', 'matriz_curricular', 'ementas', 'regulamento');
$array_fields_date		= array('data');
$array_fields_time		= array('horario');
$array_galeries 		= array('produtos', 'noticias');

//GALLERY
$array_galeries = array('produtos', 'noticias', 'idades', 'intercambios'); //Pages that have gallery

?>