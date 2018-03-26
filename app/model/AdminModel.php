<?php

//------------- INIT ---------------------------------------------------------------------------------------

	include ('../config/directories.php');
	include ('../config/database.php');
	$array_galeries 		= array('produtos', 'noticias');

	$action	= $_GET['action'];
	$table	= $_GET['id'];
	$item 	= $_GET['item'];

	$conn = db();

	foreach($conn->query("SELECT titulo FROM cms WHERE id = '".$table."' ") as $row) {
		$table		= $row['titulo'];
	}


//------------- ADD -------------------------------------------------------------------------------------------------

	if($action == 'add'){

		$query = $conn->prepare("DESCRIBE ".$table);
		$query->execute();
		$table_fields = $query->fetchAll(PDO::FETCH_COLUMN);


		$columns		= '';
		$columns_val	= '';

		foreach($table_fields as $field) {

			if($field == "id"){
				$columns .= '';
			}
			else{
				$columns .= $field.', ';
			}

			if($field !== "img" AND $field !== "icone") {
				$field = $_POST[$field];
			}
			else{
				$file		= $_FILES[$field]['name'];
				$img		= uniqid().$file;

				$_UP['pasta']	= ABSOLUTE_PATH . $table . DS;
				move_uploaded_file($_FILES[$field]['tmp_name'], $_UP['pasta'] . $img);

			} 
			
			if($columns == ""){		
				$columns_val	.= '';
			}
			else{

				if(!empty($img)){
					$columns_val	.= " '".$img."', ";
					$img = '';
				} else {
					$columns_val	.= " '".$field."', ";
				}

			}
		}


		$query 	= $conn->prepare("SELECT id FROM cms WHERE titulo = '".$table."'"); 
		$query->execute();
		$id_category = $query->fetchColumn();

		$sql = 'INSERT INTO '.$table.' ('.substr($columns, 0, -2).') VALUES ('.substr($columns_val, 0, -2).')' ;
		$query = $conn->prepare($sql);
		$query->execute();

		$query 	= $conn->prepare("SELECT id FROM ".$table." ORDER BY ID DESC LIMIT 1"); 
		$query->execute();
		$id_last = $query->fetchColumn();

		$id_last_new = ($id_last+1);

		if(!empty($_FILES['filesToUpload']['tmp_name'][0])){

			if(in_array($table, $array_galeries, TRUE)){
				
				$i = 0;
			
				foreach ($_FILES['filesToUpload']['name'] as $fileimg) {
			
					$fileimgname = uniqid().$fileimg;
			
					move_uploaded_file($_FILES['filesToUpload']['tmp_name'][$i], ABSOLUTE_PATH . $table . DS . $fileimgname);

					$query 	= $conn->prepare("INSERT INTO ".$table."_galeria (id_".$table.", titulo, img) VALUES ('".$id_last."', '".$fileimgname."', '".$fileimgname."')"); 
					$query->execute();    
			
					$i++;	
				}
				

				
			
			}

		}


	}


//------------- EDIT -------------------------------------------------------------------------------------------------

	elseif($action == 'edit'){

		$columns		= '';
		$columns_val	= '';

		$query = $conn->prepare("DESCRIBE ".$table);
		$query->execute();
		$table_fields = $query->fetchAll(PDO::FETCH_COLUMN);

		foreach($table_fields as $field) {

			if($field == "id"){
				$id		 = $_POST[$field];
				$field   = '';
				$columns = '';
			} 

			elseif($field == "img" OR $field == "icone"){

				if(empty($_FILES[$field]['tmp_name'][0])){
					
					$columns = '';
					$field   = '';
					$columns_val	.= '';

				}else{

					$file		= $_FILES[$field]['name'];
					$img		= uniqid().$file;

					$_UP['pasta']	= IMG_REL_DIR . $table . DS;
					move_uploaded_file($_FILES[$field]['tmp_name'], $_UP['pasta'] . $img);

					$columns_val	.= $field." = '".$img."', ";
				}

			} else {
				
				$columns = $field;
				$field = $_POST[$field];
				
				if($columns == ""){		
					$columns_val	.= '';
				} else{
					$columns_val		.= $columns." = '".$field."', ";
				}
			}

		}

		$query 	= $conn->prepare("SELECT id FROM cms WHERE titulo = '".$table."'"); 
		$query->execute();
		$id_category = $query->fetchColumn();

		$sql = 'UPDATE '.$table.' SET '.substr($columns_val, 0, -2)." WHERE id = '".$id."'";
		$query = $conn->prepare($sql);
		$query->execute();

		$conn=null;

		if(!empty($_FILES['filesToUpload']['tmp_name'][0])){
			
			require (CONFIG_REL_DIR . 'database.php');

			$query 	= $conn->prepare("SELECT id FROM ".$table." ORDER BY ID DESC LIMIT 1"); 
			$query->execute();
			$id_last = $query->fetchColumn();

			if(in_array($table, $array_galeries, TRUE)){

				$i = 0;

				foreach ($_FILES['filesToUpload']['name'] as $fileimg) {

					$fileimgname = uniqid().$fileimg;

					move_uploaded_file($_FILES['filesToUpload']['tmp_name'][$i], ABSOLUTE_PATH . $table . DS . $fileimgname);
					
					$query 	= $conn->prepare("INSERT INTO ".$table."_galeria (id_".$table.", titulo, img) VALUES ('".$id."', '".$fileimgname."', '".$fileimgname."')"); 
					$query->execute();    

					$i++;	
				}

			}
			
		}

	}


//------------- DELETE -------------------------------------------------------------------------------------------------


	elseif($action == 'delete'){

		$query 	= $conn->prepare("DELETE FROM ".$table." WHERE id = ".$item.""); 
		$query->execute();

	}


//------------- GALLERY -------------------------------------------------------------------------------------------------


	elseif($action == 'gallery'){

		$query 	= $conn->prepare("DELETE FROM ".$table."_galeria WHERE id = ".$item.""); 
		$query->execute();

	}	



//------------- CLOSE CONN AND REDIRECT ---------------------------------------------------------------------------------------

	$conn=null;

	header('Location:'. ROOT . ADMIN . $_GET['id']);

?>