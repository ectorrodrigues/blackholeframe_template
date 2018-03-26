<?php

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


	if(isset($_GET['initial'])){

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
			    echo "DATABASE sucessfully created.<br />";

		    $sql = "CREATE TABLE cms ( id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, titulo VARCHAR(50) )";
		    $pdo->exec($sql);
		    echo "CMS Table sucessfully created.<br />";

		    $sql = "CREATE TABLE usuarios ( id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, titulo VARCHAR(50), email VARCHAR(80), password VARCHAR(150), keypass VARCHAR(150) )";
		    $pdo->exec($sql);
		    echo "Usuarios Table sucessfully created.<br />";

		    $user 		= $_POST['user'];
		    $email 		= $_POST['email'];
		    $password 	= $_POST['password'];
		    $password 	= crypt($password, '$1$H2Oc3po$');

		    $query 	= $pdo->prepare("INSERT INTO usuarios (id, titulo, email, password, keypass) VALUES('1', :titulo, :email, :password, :keypass)"); 
			$query->bindParam(':titulo', $user);
			$query->bindParam(':email',$email);
			$query->bindParam(':password', $password);
			$query->bindParam(':keypass', $password);
			$query->execute();
			echo "Usuarios Table Updated.<br />";

			echo '<p><a href="/hco/creator" style="background-color:#000; color:#fff; padding:15px 10px; border-radius:5px; text-decoration:none; margin:15px 0;" >Voltar</a></p>';




			} catch(PDOException $e){
			    die("ERROR: Could not able to execute $sql. " . $e->getMessage());
			}
			 
			// Close connection
			unset($pdo);

		    

		}

		catch(PDOException $e) {
		    echo $sql . "<br>" . $e->getMessage();
	    }

	    die();

	}

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

		    echo "Database sucessfully created.<br />";

		}

		catch(PDOException $e) {
		    echo $sql . "<br>" . $e->getMessage();
	    }
	}



    // ADD TO CMS --------------------------------------------------------------------------------------------

    if($cms == 'yes'){
    	$query 	= $conn->prepare("SELECT id from cms ORDER BY id DESC LIMIT 1"); 
		$query->execute();
		$last_id = $query->fetchColumn();
		$last_id = ($last_id+1);

	    $query 	= $conn->prepare("INSERT INTO cms (id, titulo) VALUES(:last_id, :title)"); 
		$query->bindParam(':title', $title);
		$query->bindParam(':last_id', $last_id);
		$query->execute();
		echo "CMS Updated.<br />";
	}



	// CREATE DIRECTORY AND INDEX.PHP ------------------------------------------------------------------------

	if($directory = 'yes'){
		if (!file_exists('../../app/view/pages/'.$title)) {
		    mkdir('../../app/view/pages/'.$title, 0777, true);
		    $my_file = '../../app/view/pages/'.$title.'/index.php';
		    fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);
		    addtext($title, 'index.php');
		    echo "Index.php Created.<br />";
		} else {
			echo "Error: Index.php NOT Created.<br />";
		}
	}


	// CREATE VER.PHP --------------------------------------------------------------------------------------------

    if($ver == 'yes'){
    	$my_file = '../../app/view/pages/'.$title.'/ver.php';
	    fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);
	    addtext($title, 'ver.php');
		echo "Ver.php Created.<br />";
	} else {
		echo "Error: Ver.php NOT Created.<br />";
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

<link rel="stylesheet" type="text/css" href="<?=CSS_DIR?>galeria.css" /> 

<?php  $path = "'.ROOT.'/app/webroot/img/'.$title.'/"; ?>

	<div id="main_photo" onclick="lightbox()" style="background-image:url(<?php echo $path.$img; ?>);">
    </div>
    
    <div id="thumbstrip">
    <?php
		
		echo "
			<input type="button" id="thumb" 
			value=\"".$img."\"
			alt=\"<strong><font size=+2>".$titulo."</font></strong>\" name=\"firstthumb\" 
			onclick=\"selectImg(this.value)\" 
			onfocus=\"selectImg2(this.alt)\" 
			style=\"	background-image:url(".$path.$img."); 
					background-size:cover; 
					background-repeat:no-repeat; 
					margin-left:0\"
		/>";
	
		foreach($conn->query("SELECT * FROM '.$title.'_galeria WHERE id_'.$title.' =\'".$id."\'") as $row_gal) {
									
			$titulo_gal	= $row_gal["titulo"];
			$img_gal	= $row_gal["img"];			
		
			echo "
			<input type=\"button\" id=\"thumb\" 
			value=\"".$img_gal."\"
			alt=\"<strong><font size=+2>".$titulo_gal."</font></strong>\" name=\"firstthumb\" 
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

	    $sql = "CREATE TABLE ".$title."_galeria ( id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,  id_".$title." INT(6), titulo VARCHAR(100), img VARCHAR(300) )";
	    $conn->exec($sql);


		echo "Gallery Created.<br />";
	} else {
		echo "Error: Gallery NOT Created.<br />";
	}


	echo '<p><a href="/ctesop/_creator" style="background-color:#000; color:#fff; padding:15px 10px; border-radius:5px; text-decoration:none; margin:15px 0;" >Voltar</a></p>';


	$conn = null;

?>