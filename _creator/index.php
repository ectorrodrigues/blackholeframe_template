
<?php include ('view/elements/site/top.php'); ?>


<?php 
	
	if(empty($_GET['page'])){
		include('view/pages/home/index.php'); 
	}
	else {
		include('view/pages/'.$_GET['page'].'/index.php');
	}
?>
	
</body>

</html>