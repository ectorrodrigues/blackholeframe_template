<?php 
	require (ELEMENTS_DIR .'head.php'); 
?>

<body> 

	<?php 

		include (ELEMENTS_DIR.'top.php'); 

		$url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

		if(empty($page)){

			$page = 'home';
			$archive = 'index.php';
			contruct_page($page, $archive);

		} else {

			$page 	= $_GET['page'];

			if(strpos($url, "ver") == false){
				$archive = 'index.php';
				contruct_page($page, $archive);
			}else{
				$id 	= $_GET['id'];
				$archive = 'ver.php';
				contruct_page($page, $archive);
			}
		}

		include (ELEMENTS_DIR .'footer.php'); 
	?>
	
</body>