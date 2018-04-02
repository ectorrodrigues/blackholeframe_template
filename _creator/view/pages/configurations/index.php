<div class="container padding-top-bottom" align="center">

	<div class="col-lg-8 text-left" align="center">

		
			<div class="pb-4">
				<h3>2. Configurations</h3>
			</div>
		

			<form action="model/AppModel.php?page=configurations" method="post" enctype="multipart/form-data">

				<?php require('config/database.php'); ?>

				<div class="form-group col-lg-12">
					<?php
						//$conn = db();
						$query 	= $conn->prepare("SELECT content FROM config WHERE title = 'Site Title'"); 
						$query->execute();
						$value = $query->fetchColumn();
					?>
					<label>Site Title</label>
				    <input type="text" name="site_title" class="form-control" value="<?= $value ?>" >
				</div>

				<div class="form-group col-lg-12">
					<?php
						$array = array("Auto_Update_AppModel", "Auto_Update_AdminModel", "Auto_Update_Helper_List", "Auto_Update_Helper_Form");

						//$conn = db();

						foreach($array as $update_title) {
							$query 	= $conn->prepare("SELECT content FROM config WHERE title = :title"); 
							$query->bindParam(':title', $update_title);
							$query->execute();
							$value = $query->fetchColumn();	

							echo '
							<label>'.$update_title.'</label>
				    		<select name="'.$update_title.'" class="form-control">
							';		

							if($value == 'yes'){ 
								echo '<option value="yes" selected="selected">yes</option>
								<option value="no">no</option>';
							} else { 
								echo '<option value="yes">yes</option>
								<option value="no" selected="selected">no</option>'; 
							}

							echo '
				    		</select>
				    		<br/>
							';
						
						}
					?>
				</div>

				<div class="form-group col-lg-12">
					<?php
						foreach($conn->query("SELECT * FROM input_types") as $row) {

							$title 		= $row['title'];
							$content 	= $row['content'];

							echo '
							<label>'.$title.'</label>
				    		<input type="text" name="'.$title.'"  class="form-control" value="'.$content.'"><br />
							';	

						}
					?>
				</div>

				<div class="form-group col-md-12 padding-top-bottom text-right">
					<button type="submit" class="btn btn-primary transition">Create</button>
				</div>

			</form>

			<div class="form-group col-lg-12 text-right">
				<a href="../_creator" class="btn-back" >
					<i class="fas fa-undo-alt"></i> voltar
				</a>
			</div>

		</div>	
</div>