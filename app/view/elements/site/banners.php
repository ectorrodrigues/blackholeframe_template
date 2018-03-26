


		<div id="banners">
		<link rel="stylesheet" type="text/css" href="http://localhostCSS_DIRcarousel.css" />   
		<div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
			<div class="carousel-inner" role="listbox">

			<?php

				$i = '1';

				include($_SERVER['DOCUMENT_ROOT'].'/blackholeframe/app/config/database.php');
				
				$conn = db();
					
				foreach($conn->query("SELECT * FROM banners") as $row) {
					
					$id			= $row['id'];
					$titulo		= $row['titulo'];
					$img		= $row['img'];
					$link		= $row['link'];

					if($i == '1'){
						$alt = $titulo; 
						$active = 'active'; 
					}else{
						$alt = $titulo;  
						$active = ''; 
					}

					echo '
					<div class="carousel-item '.$active.'">
						<a href="'.$link.'">
							<div class="d-block img-fluid w-100" style="background-image:url(IMG_DIRbanners/'.$img.');"></div>
						</a>
					</div>
					';	

					$i++;
				}
			?>

			</div>

			<a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
		    	<span class="carousel-control-prev-icon" aria-hidden="true"></span>
		    	<span class="sr-only">Previous</span>
		  	</a>
		 	<a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
		    	<span class="carousel-control-next-icon" aria-hidden="true"></span>
		    	<span class="sr-only">Next</span>
		  	</a>

		</div>

	</div>
	