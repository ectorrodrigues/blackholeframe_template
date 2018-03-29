<?php

	foreach($conn->query("SELECT title FROM ".$cms." WHERE id ='".$id_item."' ") as $row) {
		$title_table = $row['title'];
	}

	$actual_link = "http://$_SERVER[HTTP_HOST]";


	if($title_table == 'pedidos_pagseguro'){

		foreach($conn->query("SELECT * FROM status_pagseguro") as $row) {

			$id_status	= $row['id'];
			$title_status	= $row['title'];

			echo '<div class="border-top margin-top-30px"><h2>'.$title_status.'</h2></div>';

			foreach($conn->query("SELECT * FROM pedidos_pagseguro WHERE status_pagseguro = '".$id_status."' ORDER BY id DESC") as $row) {


				$id		 	= $row['id'];

				if(isset($row['title'])){ 
					$title = $row['title'];
				} 
				elseif(isset($row['nome'])){ 
					$title = $row['nome']; 
				}
				elseif(isset($row['id_pedidos'])){ 
					$title = $row['id']." | ". $row['cliente']; 
				}

				if(isset($row['img'])){ $img = $row['img'];  $img_size= ""; } else{ $img = ''; $img_size="width:0;"; }
											
				echo '
				<div class="content-item">
					<div class="content-item-thumb inline" style="background-image:url('.IMG_DIR.$title_table.DS.$img.'); '.$img_size.'">
					</div>
					<div class="content-item-title col7 inline">
						'.$title.'
					</div>
					<div class="col2 inline" align="right">
						<a href="'.ROOT.ADMIN.'edit'.DS.$id_item.DS.$id.'"><div class="bt_edit inline transition"><i class="fa fa-pencil" aria-hidden="true"></i></div></a>
						<a href="'.DS.SITE_NAME.DS.ADMIN.'model'.DS.'delete'.DS.$id_item.DS.$id.'"><div class="bt_delete inline transition"><i class="fa fa-times" aria-hidden="true"></i></div></a>
					</div>
				</div>
				';


			}

		}

		exit;

	}



	foreach($conn->query("SELECT * FROM ".$title_table. " ORDER BY id DESC") as $row) {
									
		$id		 	= $row['id'];

		if(isset($row['title'])){ 
			$title = $row['title'];
		} 
		elseif(isset($row['nome'])){ 
			$title = $row['nome']; 
		}
		elseif(isset($row['id_pedidos'])){ 
			$title = $row['id']." | ". $row['cliente']; 
		}

		if(isset($row['img'])){ $img = $row['img'];  $img_size= ""; } else{ $img = ''; $img_size="width:0;"; }
									
		echo '
		<div class="content-item">
			<div class="content-item-thumb inline" style="background-image:url('.IMG_DIR.$title_table.DS.$img.'); '.$img_size.'">
			</div>
			<div class="content-item-title col7 inline">
				'.$title.'
			</div>
			<div class="col2 inline" align="right">
				<a href="'.ROOT.ADMIN.'edit'.DS.$id_item.DS.$id.'"><div class="bt_edit inline transition"><i class="fa fa-pencil" aria-hidden="true"></i></div></a>
				<a href="'.DS.SITE_NAME.DS.ADMIN.'model'.DS.'delete'.DS.$id_item.DS.$id.'"><div class="bt_delete inline transition"><i class="fa fa-times" aria-hidden="true"></i></div></a>
			</div>
		</div>
		';
	}

?>