<?php 
    //include ('app'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'config.php');
    require (ELEMENTS_DIR .'head.php'); 
?>


<body style="background-color: #eee !important;">

	<div class="container top" align="center">
	    	<a href="<?= ROOT ?>">
	         	<img src="../resources/img/logo_horizontal.svg" width="80" height="auto" />
	    	</a>
	</div>    

    <div class="container middle-padding-top" align="center">
        <div class="content col5 login-screen" align="center">

        <?php
        	if(!isset($_COOKIE['login'])){
        		echo 'Login<p></p>';
        	} else {
        		echo 'cookie setado';
        	}
        ?>

			<form action="<?= ROOT.'admin'.DS?>" method="post" enctype="multipart/form-data">
		    	<input type="text" name="user" placeholder="usuario" />
		        <input type="password" name="password" placeholder="senha" />
		    	<input type="submit" name="submit" class="submit transition" value="enviar" style="width: 84%;" />
		    </form>
		</div>
	</div>

</body>
</html>