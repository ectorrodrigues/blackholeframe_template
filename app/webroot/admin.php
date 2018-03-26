<?php 
	//include ('app'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'config.php');
	require (ELEMENTS_DIR .'head.php'); 
?>


<body>
<div class="container">

	<div class="logout">
		<a href="<?=ROOT.WEBROOT_DIR.'logout.php'?>">logout</a>
	</div>

	<div class="col2 inline menu-gallery">
		<?php
			$conn = db();
									
			$content = '
			<div class="menu-item">
				<a href="'.ROOT.ADMIN.'{id}">
					{function->remove_underlines->titulo}
				</a>
			</div>
			';

			foreach_fetch(	
			/*table*/$cms,
			/*content*/$content, 
			/*where*/"",
			/*extras*/"", 
			/*order*/"id", 
			/*asc_desc*/"ASC",
			/*limit*/"" );
		?>
	</div>

	<div class="col8 inline content margin-left">
		<?php
			$url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

			if (preg_match('#[0-9]#',$url)){ 
					
				$id_item = $_GET['id'];

				echo '
				<a href="'.ROOT.ADMIN.'add'.DS.$id_item.'">
					<div class="bt_add">+ Adicionar Item</div>
				</a>';

				if(strpos($url,"add") == true || strpos($url,"edit") == true || strpos($url,"delete") == true){
					include (HELPER_DIR.'form.php');
				} else {
					include (HELPER_DIR.'list.php');
				}

			} else {

				echo '<div id="welcome">Bem-Vindo =)</div>';

			}
				
			$conn = null;
		?>
	</div>
    
</div>
</body>