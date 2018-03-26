<?php 
	$url = explode("/",'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']); $url = array_pop($url);
 	if($url == ''){ echo '<style>.top{position: absolute; z-index: 2;}</style>'; } 
	require( 'C:\wamp64\www\blackholeframe\app\view\elements\site\banners.php');     
?>

	<div class="container-fluid">
		<div class="row justify-content-center">

			<div class="col-sm-12 col-md-12 col-lg-5 col-centered col-lg-offset-3">
				<loop>
					<loop_sql><?= 'table=professores;where= ;extras= ;orderby=id;order= ;limit= ;'; ?></loop_sql>
					<div class="col">{titulo}</div>
					<div class="col">{email}</div>
					<div class="col">{telefone}</div>

					<div class="col">
						<p>
							Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
							tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
							quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
							consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
							cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
							proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
						</p>
					</div>
				</loop>
			</div>

			<div class="col-sm-3 col-md-6 col-lg-4 col-centered">
				<loop>
					<loop_sql><?= 'table=cursos;where= ;extras= ;orderby=id;order= ;limit= ;'; ?></loop_sql>
					<div class="col">{titulo}</div>
				</loop>
			</div>

		</div>
	</div>

	<?php

		//DIRECT SQL FETCH USAGE EXAMPLE

		/*
		include($_SERVER['DOCUMENT_ROOT'].'/blackholeframe/app/config/database.php');
		
		$conn = db();
		$res = $conn->query("SELECT titulo FROM noticias"); 
		while ($obj = $res->fetch(PDO::FETCH_OBJ)) {
			echo $obj->titulo.'<br>';
		}

		$conn = NULL;
		*/

	?>