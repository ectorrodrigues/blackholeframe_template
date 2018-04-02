<div class="container padding-top-bottom" align="center">

		<div class="col-lg-8 text-left" align="center">

			
			<div class="pb-4">
				<h3>3. Pages & Tables</h3>
			</div>
			

			<form action="model/AppModel.php?page=pages" method="post" enctype="multipart/form-data">

				<div class="form-group">
				    <label>Create Table?</label><br>
					<select name="table" class="form-control">
						<option value="yes">YES</option>
						<option value="no">NO</option>
					</select>
				</div>


				<div class="form-group">
				    <label>Table Name</label>
				    <input type="text" name="title" class="form-control">
				</div>

				<div class="row  db">

					<div class="form-group col-lg-12">
				    	<label>Database</label><br>
				    	<small class="form-text text-muted">Id automaticly generated. Primary Key Auto Increment.</small>
				    </div>

				    <div class="form-group col-lg-4">
				    	<input type="text" name="db_name[]" class="form-control" placeholder="Name">
				    </div>

				    	<div class="form-group col-lg-4">
						    <select name="db_type[]" class="form-control">
						    	<option value="VARCHAR">VARCHAR</option>
						    	<option value="INT">INT</option>
						    	<option value="LONGTEXT">LONGTEXT</option>
						    	<option value="MEDIUMTEXT">MEDIUMTEXT</option>
						    	<option value="DATE">DATE</option>
						    	<option value="TIMESTAMP">TIMESTAMP</option>
						    </select>
						</div>

				    <div class="form-group col-lg-4">
				    	<input type="text" name="db_lenght[]" class="form-control" placeholder="Lenght">
				    </div>

				    <input type="hidden" name="db_num_columns" class="db_num_columns" value="1">

				</div>

				<div class="row">
					<div class="col-12 text-green pointer" align="center" onclick="AddDbColumn()">
						<div><i class="fas fa-plus-circle"></i> New Column</div>
					</div>
				</div>

				<div class="row" style="height: 50px;"></div>

				<div class="row">
					<div class="form-group col-lg-3 inline">
					    <label>Create Directory?</label><br>
						<select name="directory" class="form-control">
							<option value="yes">YES</option>
							<option value="no">NO</option>
						</select>
					</div>

					    <div class="form-group col-lg-3">
					    	<label>CMS?</label>
							<select name="cms" class="form-control">
							    <option value="yes">YES</option>
							    <option value="no">NO</option>
							</select>
						</div>

						<div class="form-group col-lg-3">
					    	<label>Create Ver.php?</label>
							<select name="ver" class="form-control">
							    <option value="no">NO</option>
							    <option value="yes">yes</option>
							</select>
						</div>

						<div class="form-group col-lg-3">
					    	<label>Create Gallery?</label>
							<select name="gallery" class="form-control">
								<option value="no">NO</option>
								<option value="yes">yes</option>
							</select>
						</div>


					<div class="form-group col-md-12 padding-top-bottom text-right">
						<button type="submit" class="btn btn-primary transition">Create</button>
					</div>
					
				</div>

			</form>

			<div class="form-group col-lg-12 text-right">
				<a href="../_creator" class="btn-back" >
					<i class="fas fa-undo-alt"></i> voltar
				</a>
			</div>


		</div>


</div>


<script>
function AddDbColumn(){
	$(".db").append('<div class="form-group col-md-4"><input type="text" name="db_name[]" class="form-control" placeholder="Column Name"></div><div class="form-group"><div class="form-group col-md-4"><select name="db_type[]" class="form-control"><option value="VARCHAR">VARCHAR</option><option value="INT">INT</option><option value="LONGTEXT">LONGTEXT</option><option value="MEDIUMTEXT">MEDIUMTEXT</option><option value="DATE">DATE</option><option value="TIMESTAMP">TIMESTAMP</option></select></div></div><div class="form-group col-md-4"><input type="text" name="db_lenght[]" class="form-control" placeholder="Column Lenght"></div>');

	var num = parseInt($('.db_num_columns').val());
  	$('.db_num_columns').val(num+1);

}
</script>