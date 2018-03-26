<?php

	foreach($conn->query("SELECT titulo FROM ".$cms." WHERE id ='".$id_item."' ") as $row) {
		$titulo_table = $row['titulo'];
	}

	$actual_link = "http://$_SERVER[HTTP_HOST]";


	if($titulo_table == 'pedidos_pagseguro'){

		foreach($conn->query("SELECT * FROM status_pagseguro") as $row) {

			$id_status	= $row['id'];
			$titulo_status	= $row['titulo'];

			echo '<div class="border-top margin-top-30px"><h2>'.$titulo_status.'</h2></div>';

			foreach($conn->query("SELECT * FROM pedidos_pagseguro WHERE status_pagseguro = '".$id_status."' ORDER BY id DESC") as $row) {


				$id		 	= $row['id'];

				if(isset($row['titulo'])){ 
					$titulo = $row['titulo'];
				} 
				elseif(isset($row['nome'])){ 
					$titulo = $row['nome']; 
				}
				elseif(isset($row['id_pedidos'])){ 
					$titulo = $row['id']." | ". $row['cliente']; 
				}

				if(isset($row['img'])){ $img = $row['img'];  $img_size= ""; } else{ $img = ''; $img_size="width:0;"; }
											
				echo '
				<div class="content-item">
					<div class="content-item-thumb inline" style="background-image:url('.IMG_DIR.$titulo_table.DS.$img.'); '.$img_size.'">
					</div>
					<div class="content-item-title col7 inline">
						'.$titulo.'
					</div>
					<div class="col2 inline" align="right">
						<a href="'.ROOT.ADMIN.'edit'.DS.$id_item.DS.$id.'"><div class="bt_edit inline transition"><i class="fa fa-pencil" aria-hidden="true"></i></div></a>
						<a href="'.DS.'brazilboots'.DS.ADMIN.'controller'.DS.'delete'.DS.$id_item.DS.$id.'"><div class="bt_delete inline transition"><i class="fa fa-times" aria-hidden="true"></i></div></a>
					</div>
				</div>
				';


			}

		}

		exit;

	}



	foreach($conn->query("SELECT * FROM ".$titulo_table. " ORDER BY id DESC") as $row) {
									
		$id		 	= $row['id'];

		if(isset($row['titulo'])){ 
			$titulo = $row['titulo'];
		} 
		elseif(isset($row['nome'])){ 
			$titulo = $row['nome']; 
		}
		elseif(isset($row['id_pedidos'])){ 
			$titulo = $row['id']." | ". $row['cliente']; 
		}

		if(isset($row['img'])){ $img = $row['img'];  $img_size= ""; } else{ $img = ''; $img_size="width:0;"; }
									
		echo '
		<div class="content-item">
			<div class="content-item-thumb inline" style="background-image:url('.IMG_DIR.$titulo_table.DS.$img.'); '.$img_size.'">
			</div>
			<div class="content-item-title col7 inline">
				'.$titulo.'
			</div>
			<div class="col2 inline" align="right">
				<a href="'.ROOT.ADMIN.'edit'.DS.$id_item.DS.$id.'"><div class="bt_edit inline transition"><i class="fa fa-pencil" aria-hidden="true"></i></div></a>
				<a href="'.DS.'brazilboots'.DS.ADMIN.'controller'.DS.'delete'.DS.$id_item.DS.$id.'"><div class="bt_delete inline transition"><i class="fa fa-times" aria-hidden="true"></i></div></a>
			</div>
		</div>
		';
	}

?>