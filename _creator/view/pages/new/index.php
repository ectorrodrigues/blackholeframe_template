<div class="container padding-top-bottom" align="center">

	<div class="col-lg-8 text-left" align="center">

		
			<div class="pb-4">
				<h3>1. New</h3>
			</div>
		

			<form action="model/AppModel.php?page=new" method="post" enctype="multipart/form-data">

					<div class="form-group col-lg-12">
						<label>Database Name</label>
					    <input type="text" name="db_name" class="form-control" >
					</div>

					<div class="form-group col-lg-12">
					    <label>Username</label>
					    <input type="text" name="user" class="form-control">
					</div>

					<div class="form-group col-lg-12">
					    <label>E-mail</label>
					    <input type="email" name="email" class="form-control">
					</div>

					<div class="form-group col-lg-12">
					    <label>Password</label>
					    <input type="text" name="password" class="form-control">
					</div>

					<div class="form-group col-lg-12">
					    <button type="submit" class="btn btn-primary">Create</button>
					</div>

			</form>

		</div>	
</div>