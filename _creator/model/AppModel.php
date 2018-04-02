<?php

	$sitename = explode('/', $_SERVER['PHP_SELF']);
	$sitename = $sitename[1];

	if(isset($_GET['page'])){ 				$page 			= $_GET['page']; }
	if(isset($_POST['table'])){ 			$table 			= $_POST['table']; }
	if(isset($_POST['title'])){ 			$title 			= $_POST['title']; }
	if(isset($_POST['db_num_columns'])){ 	$db_num_columns	= $_POST['db_num_columns']; }
	if(isset($_POST['db_name'])){ 			$db_name 		= $_POST['db_name']; }
	if(isset($_POST['db_type'])){ 			$db_type 		= $_POST['db_type']; }
	if(isset($_POST['db_lenght'])){ 		$db_lenght 		= $_POST['db_lenght']; }
	if(isset($_POST['directory'])){ 		$directory 		= $_POST['directory']; }
	if(isset($_POST['cms'])){ 				$cms 			= $_POST['cms']; }
	if(isset($_POST['ver'])){ 				$ver 			= $_POST['ver']; }
	if(isset($_POST['gallery'])){ 			$gallery 		= $_POST['gallery']; }

	require('../config/database.php');

	define('ROOT', "http://".$_SERVER['HTTP_HOST']);

	$results_echo = '';

	function addtext($title, $page){
		$file    = '../../app/view/pages/'.$title.'/'.$page;
	    $text = 
	    '	<div class="container">
	    <div class="col8">
	    </div>
	</div>
	    ';

	    file_put_contents($file, $text);
	}

	function create_files($dir, $filename){

		global $results_echo;

		$appmodel = file_get_contents('https://raw.githubusercontent.com/ectorrodrigues/blackholeframe/master/app/'.$dir.'/'.$filename);

		if(strpos($appmodel, '<pre>') == true){
			$appmodel = str_replace(array("<pre>", "</pre>"), array("<?php", "?>" ), $appmodel);
		}

		file_put_contents('../../app/'.$dir.'/'.$filename, $appmodel);

		$results_echo .= '<strong>'.$filename.'</strong> sucessfuly created. <br>';
	}
			

if($page == 'new'){

	try {

		try{
			$pdo = new PDO("mysql:host=localhost;", "root", "");
			// Set the PDO error mode to exception
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch(PDOException $e){
			die("ERROR: Could not connect. " . $e->getMessage());
		}
			 
		// Attempt create database query execution
		try{
			$sql = "CREATE DATABASE ".$_POST['db_name']." CHARACTER SET utf8 COLLATE utf8_unicode_ci;";
			$pdo->exec($sql);
			$sql = "USE ".$_POST['db_name'];
			$pdo->exec($sql);
			$results_echo .= "<strong>DATABASE</strong> sucessfully created.<br />";

		    $sql = "CREATE TABLE cms ( id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, title VARCHAR(50) )";
		    $pdo->exec($sql);
		    $results_echo .= "<strong>CMS</strong> Table sucessfully created.<br />";

		    $sql = "CREATE TABLE update_time_control ( id INT(255) UNSIGNED AUTO_INCREMENT PRIMARY KEY, time DATETIME)";
		    $pdo->exec($sql);
		    $results_echo .= "<strong>Update Time Control</strong> Table sucessfully created.<br />";

		    $sql = "CREATE TABLE users ( id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, title VARCHAR(50), email VARCHAR(80), password VARCHAR(150), keypass VARCHAR(150) )";
		    $pdo->exec($sql);
		    $results_echo .= "<strong>Users</strong> Table sucessfully created.<br />";

		    $user 		= $_POST['user'];
		    $email 		= $_POST['email'];
		    $password 	= $_POST['password'];
		    $password 	= crypt($password, '$1$H2Oc3po$');

		    $query 	= $pdo->prepare("INSERT INTO users (id, title, email, password, keypass) VALUES('1', :title, :email, :password, :keypass)"); 
			$query->bindParam(':title', $user);
			$query->bindParam(':email',$email);
			$query->bindParam(':password', $password);
			$query->bindParam(':keypass', $password);
			$query->execute();
			$results_echo .= "<strong>Users</strong> Table Updated.<br />";


			$sql = "CREATE TABLE config ( id INT(255) UNSIGNED AUTO_INCREMENT PRIMARY KEY, title VARCHAR(50), content VARCHAR(500) )";
		    $pdo->exec($sql);
		    $results_echo .= "<strong>Config</strong> Table sucessfully created.<br />";

		    $query 	= $pdo->prepare("INSERT INTO config (id, title, content) 
		    	VALUES ('', 'Database Name', '".$db_name."'), 
		    	('', 'Site_Title', 'Title of your Site'), 
		    	('', 'Auto_Update_AppModel', 'yes'),
		    	('', 'Auto_Update_AdminModel', 'yes'),
		    	('', 'Auto_Update_Helper_List', 'yes'),
		    	('', 'Auto_Update_Helper_Form', 'yes')
		    	"); 
			$query->execute();
			$results_echo .= "<strong>Config</strong> Table Updated.<br />";

			$sql = "CREATE TABLE input_types ( id INT(255) UNSIGNED AUTO_INCREMENT PRIMARY KEY, title VARCHAR(50), content VARCHAR(500) )";
		    $pdo->exec($sql);
		    $results_echo .= "<strong>Input_types</strong> Table sucessfully created.<br />";

		    $query 	= $pdo->prepare("INSERT INTO input_types (id, title, content) 
		    	VALUES ('', 'array_fields_hidden', 'id'), 
		    	('', 'array_fields_text', 'title'),
		    	('', 'array_fields_number', 'sku, price'),
		    	('', 'array_fields_select', 'status, id_category, id_subcategory, id_posts'),
		    	('', 'array_fields_img', 'img, photo, icon'),
		    	('', 'array_fields_textarea', 'text, description, addres'),
		    	('', 'array_fields_date', 'date'),
		    	('', 'array_fields_time', 'hour, time' ),
		    	('', 'array_galleries', 'products, blog, news' )
		    	"); 
			$query->execute();
			$results_echo .= "<strong>Input_types</strong> Table Updated.<br />";


			//MAKING FOLDERS AND POPULATE THEM WITH FILES
			if (!file_exists('../../app')) { mkdir('../../app', 0777, true); }
			if (!file_exists('../../app/config')) { mkdir('../../app/config', 0777, true); }
			create_files('config', 'config.php');
			create_files('config', 'database.php');
			create_files('config', 'directories.php');

			
			if (!file_exists('../../app/controller')) { mkdir('../../app/controller', 0777, true); }
			if (!file_exists('../../app/model')) { mkdir('../../app/model', 0777, true); }
			if (!file_exists('../../app/vendors')) { mkdir('../../app/vendors', 0777, true); }

			if (!file_exists('../../app/view')) { mkdir('../../app/view', 0777, true); }
				if (!file_exists('../../app/view/elements')) { mkdir('../../app/view/elements', 0777, true); }
					if (!file_exists('../../app/view/elements/site')) { mkdir('../../app/view/elements/site', 0777, true); }
					create_files('view/elements/site', 'banners.php');
					create_files('view/elements/site', 'footer.php');
					create_files('view/elements/site', 'head.php');
					create_files('view/elements/site', 'menu.php');
					create_files('view/elements/site', 'top.php');

				if (!file_exists('../../app/view/helper')) { mkdir('../../app/view/helper', 0777, true); }
				if (!file_exists('../../app/view/pages')) { mkdir('../../app/view/pages', 0777, true); }
					if (!file_exists('../../app/view/pages/home')) { mkdir('../../app/view/pages/home', 0777, true); }
					$my_file = '../../app/view/pages/home/index.php';
		    		if (!file_exists('../../app/view/pages/home/index.php')) { fopen($my_file, 'w') or die('Cannot open file:  '.$my_file); }

			if (!file_exists('../../app/webroot')) { mkdir('../../app/webroot', 0777, true); }
				if (!file_exists('../../app/webroot/css')) { mkdir('../../app/webroot/css', 0777, true); }
					create_files('webroot/css', 'main.css');
					create_files('webroot/css', 'admin.css');
					create_files('webroot/css', 'carousel.css');
					create_files('webroot/css', 'mobile.css');
					create_files('webroot/css', 'gallery.css');

				if (!file_exists('../../app/webroot/files')) { mkdir('../../app/webroot/files', 0777, true); }
				if (!file_exists('../../app/webroot/img')) { mkdir('../../app/webroot/img', 0777, true); }

			$results_echo .= "<strong>All Directories</strong> Created.<br />";

			} catch(PDOException $e){
			    die("ERROR: Could not able to execute $sql. " . $e->getMessage());
			}
			 
			// Close connection
			unset($pdo);

		    

		}

		catch(PDOException $e) {
		    echo $sql . "<br>" . $e->getMessage();
	    }

}



if($page == 'configurations'){

	$site_title 				= $_POST['site_title'];
	$Auto_Update_AppModel 		= $_POST['Auto_Update_AppModel'];
	$Auto_Update_AdminModel 	= $_POST['Auto_Update_AdminModel'];
	$Auto_Update_Helper_List 	= $_POST['Auto_Update_Helper_List'];
	$Auto_Update_Helper_Form 	= $_POST['Auto_Update_Helper_Form'];
	$array_fields_hidden 		= $_POST['array_fields_hidden'];
	$array_fields_text 			= $_POST['array_fields_text'];
	$array_fields_number 		= $_POST['array_fields_number'];
	$array_fields_select 		= $_POST['array_fields_select'];
	$array_fields_img 			= $_POST['array_fields_img'];
	$array_fields_textarea 		= $_POST['array_fields_textarea'];
	$array_fields_date 			= $_POST['array_fields_date'];
	$array_fields_time 			= $_POST['array_fields_time'];
	$array_galleries 			= $_POST['array_galleries'];

	$query 	= $pdo->prepare("UPDATE config SET content = :item WHERE title = 'Site_Title'"); 
	$query->bindParam(':item', $site_title);
	$query->execute();
	$results_echo .= "<strong>Site_Title</strong> Column Updated.<br />";

	$query 	= $pdo->prepare("UPDATE config SET content = :item WHERE title = 'Auto_Update_AppModel'"); 
	$query->bindParam(':item', $Auto_Update_AppModel);
	$query->execute();
	$results_echo .= "<strong>Auto_Update_AppModel</strong> Column Updated.<br />";

	$query 	= $pdo->prepare("UPDATE config SET content = :item WHERE title = 'Auto_Update_AdminModel'"); 
	$query->bindParam(':item', $Auto_Update_AdminModel);
	$query->execute();
	$results_echo .= "<strong>Auto_Update_AdminModel</strong> Column Updated.<br />";

	$query 	= $pdo->prepare("UPDATE config SET content = :item WHERE title = 'Auto_Update_Helper_List'"); 
	$query->bindParam(':item', $Auto_Update_Helper_List);
	$query->execute();
	$results_echo .= "<strong>Auto_Update_Helper_List</strong> Column Updated.<br />";

	$query 	= $pdo->prepare("UPDATE config SET content = :item WHERE title = 'Auto_Update_Helper_Form'"); 
	$query->bindParam(':item', $Auto_Update_Helper_Form);
	$query->execute();
	$results_echo .= "<strong>Auto_Update_Helper_Form</strong> Column Updated.<br />";

	$query 	= $pdo->prepare("UPDATE input_types SET content = :item WHERE title = 'array_fields_hidden'"); 
	$query->bindParam(':item', $array_fields_hidden);
	$query->execute();
	$results_echo .= "<strong>array_fields_hidden</strong> Column Updated.<br />";

	$query 	= $pdo->prepare("UPDATE input_types SET content = :item WHERE title = 'array_fields_text'"); 
	$query->bindParam(':item', $array_fields_text);
	$query->execute();
	$results_echo .= "<strong>array_fields_text</strong> Column Updated.<br />";

	$query 	= $pdo->prepare("UPDATE input_types SET content = :item WHERE title = 'array_fields_number'"); 
	$query->bindParam(':item', $array_fields_number);
	$query->execute();
	$results_echo .= "<strong>array_fields_number</strong> Column Updated.<br />";

	$query 	= $pdo->prepare("UPDATE input_types SET content = :item WHERE title = 'array_fields_select'"); 
	$query->bindParam(':item', $array_fields_select);
	$query->execute();
	$results_echo .= "<strong>array_fields_select</strong> Column Updated.<br />";

	$query 	= $pdo->prepare("UPDATE input_types SET content = :item WHERE title = 'array_fields_img'"); 
	$query->bindParam(':item', $array_fields_img);
	$query->execute();
	$results_echo .= "<strong>array_fields_img</strong> Column Updated.<br />";

	$query 	= $pdo->prepare("UPDATE input_types SET content = :item WHERE title = 'array_fields_textarea'"); 
	$query->bindParam(':item', $array_fields_textarea);
	$query->execute();
	$results_echo .= "<strong>array_fields_textarea</strong> Column Updated.<br />";

	$query 	= $pdo->prepare("UPDATE input_types SET content = :item WHERE title = 'array_fields_date'"); 
	$query->bindParam(':item', $array_fields_date);
	$query->execute();
	$results_echo .= "<strong>array_fields_date</strong> Column Updated.<br />";

	$query 	= $pdo->prepare("UPDATE input_types SET content = :item WHERE title = 'array_fields_time'"); 
	$query->bindParam(':item', $array_fields_time);
	$query->execute();
	$results_echo .= "<strong>array_fields_time</strong> Column Updated.<br />";

	$query 	= $pdo->prepare("UPDATE input_types SET content = :item WHERE title = 'array_galleries'"); 
	$query->bindParam(':item', $array_galleries);
	$query->execute();
	$results_echo .= "<strong>array_galleries</strong> Column Updated.<br />";

}



if($page == 'pages'){

	// CREATE DATABASE ---------------------------------------------------------------------------------------

	if($table == 'yes'){
		try {

		    require('../config/database.php');

		    $sql = "CREATE TABLE ".$title;
		    $sql .= ' ( id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, ';

			$i = 0;

			foreach($db_name as $value) {

				$db_name_value = $value;
				$db_type_value 		= $_POST['db_type'][$i];
				$db_lenght_value	= $_POST['db_lenght'][$i];

				if($db_type_value != "LONGTEXT"){ $db_lenght_value = '('.$db_lenght_value.'), '; } else { $db_lenght_value = ', '; }

				$sql .= $db_name_value.' '.$db_type_value.$db_lenght_value;

				$i++;

			}

			$sql = substr($sql, 0, -2);
			$sql .= ' )';
		    $conn->exec($sql);

		    $results_echo .= "<strong>Database</strong> sucessfully created.<br />";

		}

		catch(PDOException $e) {
		    $results_echo .= $sql . "<br>" . $e->getMessage();
	    }
	}



    // ADD TO CMS --------------------------------------------------------------------------------------------

    if($cms == 'yes'){
    	$query 	= $conn->prepare("SELECT id from cms ORDER BY id DESC LIMIT 1"); 
		$query->execute();
		$last_id = $query->fetchColumn();
		$last_id = ($last_id+1);

	    $query 	= $conn->prepare("INSERT INTO cms (id, title) VALUES(:last_id, :title)"); 
		$query->bindParam(':title', $title);
		$query->bindParam(':last_id', $last_id);
		$query->execute();
		$results_echo .= "<strong>CMS</strong> Updated.<br />";
	}



	// CREATE DIRECTORY AND INDEX.PHP ------------------------------------------------------------------------

	if($directory = 'yes'){
		if (!file_exists('../../app/view/pages/'.$title)) {
		    mkdir('../../app/view/pages/'.$title, 0777, true);
		    $my_file = '../../app/view/pages/'.$title.'/index.php';
		    fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);
		    addtext($title, 'index.php');
		    echo "<strong>Index.php</strong> Created.<br />";
		} else {
			$results_echo .= "<strong>Index.php</strong> NOT Created.<br />";
		}
	}


	// CREATE VER.PHP --------------------------------------------------------------------------------------------

    if($ver == 'yes'){
    	$my_file = '../../app/view/pages/'.$title.'/ver.php';
	    fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);
	    addtext($title, 'ver.php');
		$results_echo .= "<strong>Ver.php</strong> Created.<br />";
	} else {
		$results_echo .= "<strong>Ver.php</strong> NOT Created.<br />";
	}

	// CREATE GALLERY --------------------------------------------------------------------------------------------

    if($gallery == 'yes'){
    	$my_file = '../../app/view/pages/'.$title.'/gallery.php';
	    fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);

	    $file    = $my_file;

	    $text = '
<script type="text/javascript" charset="utf-8">
function selectImg(str) {
	var path = "'.ROOT.'/app/webroot/img/'.$title.'/";
	$("#main_photo").css("background-image", "url("+path+str+")");
}
</script>

<script type="text/javascript" charset="utf-8">
function lightbox() {
	var doc_height		= $( document ).height();
	var win_height		= $( window ).height();
	window.win_height2	= win_height-50;
	var margin_top		= doc_height/2;
	var scrolled 		= $(window).scrollTop();
	var scrolled2 		= scrolled+25;
	var main_photo		= $("#main_photo").css("background-image")
	var main_photo_res	= main_photo.replace("url(\"", "");
	var main_photo_res2	= main_photo_res.replace("\")", "");
	var tmpImg	= new Image();
	tmpImg.src	= main_photo_res2;
	$(tmpImg).on("load",function(){
		var orgWidth	= tmpImg.width;
		var orgHeight	= tmpImg.height;
		var finalWidth	= (orgWidth*win_height2)/orgHeight;
		$("body").prepend("<div class=\"black_overlay\" style=\"height:"+doc_height+"px;\"></div>");
		$("body").prepend("<div class=\"whitescreen\" align=\"center\" style=\"height:"+win_height+"px; margin-top:"+scrolled2+"px;\" onclick=\"fechar()\"><img src=\""+main_photo_res2+"\" height=\""+win_height2+"" width=\""+finalWidth+"\"></div></div>");
	});
	
}

function fechar() {
	$( ".black_overlay" ).remove();
	$( ".whitescreen" ).remove();
}
</script>

<link rel="stylesheet" type="text/css" href="<?=CSS_DIR?>gallery.css" /> 

<?php  $path = "'.ROOT.'/app/webroot/img/'.$title.'/"; ?>

	<div id="main_photo" onclick="lightbox()" style="background-image:url(<?php echo $path.$img; ?>);">
    </div>
    
    <div id="thumbstrip">
    <?php
		
		echo "
			<input type="button" id="thumb" 
			value=\"".$img."\"
			alt=\"<strong><font size=+2>".$title."</font></strong>\" name=\"firstthumb\" 
			onclick=\"selectImg(this.value)\" 
			onfocus=\"selectImg2(this.alt)\" 
			style=\"	background-image:url(".$path.$img."); 
					background-size:cover; 
					background-repeat:no-repeat; 
					margin-left:0\"
		/>";
	
		foreach($conn->query("SELECT * FROM '.$title.'_gallery WHERE id_'.$title.' =\'".$id."\'") as $row_gal) {
									
			$title_gal	= $row_gal["title"];
			$img_gal	= $row_gal["img"];			
		
			echo "
			<input type=\"button\" id=\"thumb\" 
			value=\"".$img_gal."\"
			alt=\"<strong><font size=+2>".$title_gal."</font></strong>\" name=\"firstthumb\" 
			onclick=\"selectImg(this.value)\" 
			onfocus=\"selectImg2(this.alt)\" 
			style=\"	background-image:url(".$path.$img_gal."); 
					background-size:cover; 
					background-repeat:no-repeat; 
					margin-left:0;\"
			/>";
		
		}
	?>
    </div>
';

		file_put_contents($file, $text);

		require('../config/database.php');

	    $sql = "CREATE TABLE ".$title."_gallery ( id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,  id_".$title." INT(6), title VARCHAR(100), img VARCHAR(300) )";
	    $conn->exec($sql);


		$results_echo .= "<strong>Gallery</strong> Created.<br />";
	} else {
		$results_echo .= "<strong>Gallery</strong> NOT Created.<br />";
	}

}

	$conn = null;


	echo '
	<link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,400i,500,600,700,700i,900" rel="stylesheet">

	<style>
	body{
		background-color: #000;
		color: #fff;
		text-align: left;
		font-size: 12px;
		line-height: 17px;
		font-family: "Montserrat", sans-serif;
		font-weight: 300;
	}

	.row{
		width:100%;
		padding:40px 0 5px 0;
	}

	.col{
		width:300px;
		text-align: left;
	}

	.btn{
		background-color: #000 !important;
	  	color: #50FFFC;
	  	border:solid;
	  	border-color: #50FFFC;
	  	border-width:1px;
	  	padding:10px;
	  	border-radius:5px;
	  	text-decoration:none;
	}
	.btn:hover{
	  	background-color: #50FFFC !important;
	  	color: #000;
	  	border:none;
	}
	</style>

	<div class="container-fluid" align="center">
		<div class="row" align="center">
			<div class="col">
				'.$results_echo.'
			</div>
		</div>
		<div class="row" align="center">
			<div class="col">
				<a href="/'.$sitename.'/_creator?page='.$page.'" class="btn" >
					voltar
				</a>
			</div>
		</div>
	</div>';

?>

