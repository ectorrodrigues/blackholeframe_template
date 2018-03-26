<?php
	
	$id		= $_GET['id'];
	$action	= $_GET['action'];

	if($action == 'edit'){
		$item = $_GET['item'];
	}
	else{
		$item = '0';
	}

	$conn = db();
	foreach($conn->query("SELECT title FROM ".$cms." WHERE id = '".$id."' ") as $row) {
		$title		= $row['title'];
	}

	echo '<form action="'.ROOT.ADMIN.'model'.DS.$action.DS.$id.DS.$item.'" method="post" enctype="multipart/form-data">';

	$query = $conn->prepare("DESCRIBE ".$title);
	$query->execute();
	$table_fields = $query->fetchAll(PDO::FETCH_COLUMN);

	foreach($table_fields as $field) {
		
		$inputs = '';

		if($action == 'edit'){
			foreach($conn->query("SELECT * FROM ".$title." WHERE id = '".$item."' ") as $row_item) {
				$value		= $row_item[$field];
				//if($field == 'data'){  $value = '2018-01-01';}
			}
		} else {
			$value	= '';
			if($field == 'data'){  $value = date("Y-m-d");}
		}
		
		if(in_array($field, $array_fields_hidden, TRUE)){
			$inputs	.= '<input type="hidden" name="'.$field.'" value="'.$value.'" />';
		}

		elseif(in_array($field, $array_fields_text, TRUE)){
			$inputs	.= '<label>'.$field.':</label><input type="text" name="'.$field.'" id="'.$field.'" placeholder="'.$field.'" value="'.$value.'" />';
		}

		elseif(in_array($field, $array_fields_select, TRUE)){
			
			$inputs	.= '<label>'.$field.':</label><select name="'.$field.'">';

			if($field == "categoria" || $field == "subcategoria" || $field == "area" || $field == "curso"  ){ $field = $field."s";}
			elseif($field == "id_categorias"){ $field = "categorias";}
			elseif($field == "professor"){ $field = "professores";}
			elseif($field == "coordenador"){ $field = "coordenadores";}
			elseif($field == "id_noticias"){ $field = "noticias";}

			foreach($conn->query("SELECT * FROM ".$field) as $row) {
				$id_select 		= $row['id'];
				$title_select	= $row['title'];

				if($id == $id_select){ $selected = 'selected="selected"'; } else { $selected = ''; }

				$inputs	.= '<option value="'.$id_select.'" '.$selected.'>'.$title_select.'</option>';
			}

			$inputs	.= '</select>';

		}

		elseif(in_array($field, $array_fields_number, TRUE)){
			$inputs	.= '<label>'.$field.':</label><input type="number" name="'.$field.'" id="'.$field.'" placeholder="'.$field.'" value="'.$value.'" />';
		}

		elseif(in_array($field, $array_fields_img, TRUE)){
			$inputs	.= '<label>'.$field.':</label><input type="file" name="'.$field.'" />';
		}

		elseif(in_array($field, $array_fields_time, TRUE)){
			$inputs	.= '<label>'.$field.':</label><input type="time" name="'.$field.'" />';
		}

		elseif(in_array($field, $array_fields_textarea, TRUE)){
			$inputs	.= '<label>'.$field.':</label><textarea name="'.$field.'">'.$value.'</textarea><script> CKEDITOR.replace( "descricao" );</script><script> CKEDITOR.replace( "endereco" );</script><script> CKEDITOR.replace( "texto" );</script><script> CKEDITOR.replace( "matriz_curricular" );</script><script> CKEDITOR.replace( "ementas" );</script><script> CKEDITOR.replace( "regulamento" );</script>';
		}
		
		elseif($field == "resumo"){
			//$inputs	.= '<input type="hidden" name="'.$field.'" value="'.$value.'" />';
			$inputs	.= '<label>'.$field.':</label><div>'.$value.'</div>';
		}
		
		elseif(in_array($field, $array_fields_date, TRUE)){
			
			$inputs	.= '<label>'.$field.':</label><input type="date" name="'.$field.'" value="'.date("Y-m-d", strtotime($value)).'" />';
			
		}
		
		elseif($field == "status_pagseguro"){
			$inputs	.= '<label>'.$field.':</label>
			<select name = "status_pagseguro">
				<option value="1">Aguardando Pagto</option>
				<option value="2">Aprovado</option>
				<option value="3">Cancelado</option>
				<option value="4">Concluído</option>
			</select>';
		}
		
		else {
			$inputs	.= '<label>'.$field.':</label><input type="text" name="'.$field.'"  id="'.$field.'" placeholder="'.$field.'" value="'.$value.'" />';
		}

		echo $inputs;
	}

	if(in_array($title, $array_galeries, TRUE)){

		if($action == 'add'){
			
			echo '<p><label>Fotos:</label><input type="file" name="filesToUpload[]" id="filesToUpload" multiple></p>';
		
		} else {

			echo '<p><label>Fotos:</label><input type="file" name="filesToUpload[]" id="filesToUpload" multiple></p>';

			foreach($conn->query("SELECT * FROM ".$title."_galeria WHERE id_".$title." = '".$item."' ") as $row) {
				$img		= $row['img'];
				$id_img		= $row['id'];

				echo '
				<div class="content-item">
					<div class="content-item-thumb inline" style="background-image:url('.IMG_DIR.$title.DS.$img.');">
					</div>
					<div class="col2 inline" align="right">
						<a href="'.DS.'brazilboots'.DS.ADMIN.'controller'.DS.'gallery'.DS.$id.DS.$id_img.'"><div class="bt_delete inline transition"><i class="fa fa-times" aria-hidden="true"></i></div></a>
					</div>
				</div>
				';
			}
			
		}

	}

	echo '<input type="submit" class="submit" value="enviar" />
	</form>';
?>

<script>$(".bt_add").css("display", "none")</script>
<script type="text/javascript">$("#preco").maskMoney();</script>

?>