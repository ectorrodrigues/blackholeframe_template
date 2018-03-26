
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="cache-control" content="no-store, no-cache, must-revalidate, Post-Check=0, Pre-Check=0">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="-1">
    
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,400i,700,900" rel="stylesheet">
    <link rel="shortcut icon" href="/brazilboots/app/webroot/files/favicon.ico" type="image/x-icon">
    <link rel="icon"  href="/brazilboots/app/webroot/files/favicon.ico" type="image/x-icon">

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
   
    <script src="https://use.fontawesome.com/a0bf7d3b26.js"></script>
           
    <title>Creator</title>

    <style type="text/css">

    	body{
    		font-family: Montserrat;
    		background-color: #efefef;
    	}

    	.w-60{
    		width: 60%;
    	}

    	.text-left{
    		text-align: left !important;
    	}

    	h1{
    		font-weight: 700;
    		letter-spacing: -3px !important;
    		color: #ddd;
    		margin: 50px 0;
    	}

    	.btn{
    		width: 100%;
    		padding: 12px 0;
    		margin-top: 25px;
    		margin-bottom: 60px;
    		background-color: #000 !important;
    	}

    	.btn:hover{
    		background-color: #fff !important;
    		color: #000;
    	}

    	.text-green{
    		color: #19CB00;
    	}

    </style>

</head>

<body> 

<div class="container w-60" align="center">

	<div class="row w-60" align="center">
		<h1>creator</h1>
	</div>

	<div class="row w-60" align="center">

		<div class="col-6 text-left" align="center">

			<form action="controller/AppController.php" method="post" enctype="multipart/form-data">

				<div class="form-group col-md-12">
				    <label>Create Table?</label><br>
					<select name="table" class="form-control">
						<option value="yes">YES</option>
						<option value="no">NO</option>
					</select>
				</div>


				<div class="form-group col-md-12">
				    <label>Table Name</label>
				    <input type="text" name="title" class="form-control">
				</div>

				<div class="form-row db">

					<div class="form-group col-md-12">
				    	<label>Database</label><br>
				    	<small class="form-text text-muted">Id automaticly generated. Primary Key Auto Increment.</small>
				    </div>

				    <div class="form-group col-md-4">
				    	<input type="text" name="db_name[]" class="form-control" placeholder="Name">
				    </div>

					<div class="form-group">
				    	<div class="form-group col-md-4">
						    <select name="db_type[]" class="form-control">
						    	<option value="VARCHAR">VARCHAR</option>
						    	<option value="INT">INT</option>
						    	<option value="LONGTEXT">LONGTEXT</option>
						    	<option value="MEDIUMTEXT">MEDIUMTEXT</option>
						    	<option value="DATE">DATE</option>
						    	<option value="TIMESTAMP">TIMESTAMP</option>
						    </select>
						</div>
					</div>

				    <div class="form-group col-md-4">
				    	<input type="text" name="db_lenght[]" class="form-control" placeholder="Lenght">
				    </div>

				    <input type="hidden" name="db_num_columns" class="db_num_columns" value="1">

				</div>

				<div class="col-12 text-green" align="center" onclick="AddDbColumn()">
					<div><span class="glyphicon glyphicon-plus-sign"></span> New Column</div>
				</div>

				<div class="row"></div>

				<div class="form-group col-md-12">
				    <label>Create Directory?</label><br>
					<select name="directory" class="form-control">
						<option value="yes">YES</option>
						<option value="no">NO</option>
					</select>
				</div>

				<div class="form-row">
					<div class="form-group">

				    	<div class="form-group col-md-4">
				    		<label>CMS?</label>
						    <select name="cms" class="form-control">
						    	<option value="yes">YES</option>
						    	<option value="no">NO</option>
						    </select>
						</div>

						<div class="form-group col-md-4">
				    		<label>Create Ver.php?</label>
						    <select name="ver" class="form-control">
						    	<option value="no">NO</option>
						    	<option value="yes">yes</option>
						    </select>
						</div>

						<div class="form-group col-md-4">
				    		<label>Create Gallery?</label>
						    <select name="gallery" class="form-control">
						    	<option value="no">NO</option>
						    	<option value="yes">yes</option>
						    </select>
						</div>

						<div class="form-group col-md-12">
							<button type="submit" class="btn btn-primary">Create</button>
						</div>

					</div>
				</div>

			</form>

		</div>

		<div class="col-6 text-left" align="center">

			<form action="controller/AppController.php?initial=yes" method="post" enctype="multipart/form-data">
					<div class="form-group col-md-12">
					    <label>Initial Installation</label>
					</div>

					<div class="form-group col-md-12">
						<label>Database Name</label>
					    <input type="text" name="db_name" class="form-control" >
					</div>

					<div class="form-group col-md-12">
					    <label>Username</label>
					    <input type="text" name="user" class="form-control">
					</div>

					<div class="form-group col-md-12">
					    <label>E-mail</label>
					    <input type="email" name="email" class="form-control">
					</div>

					<div class="form-group col-md-12">
					    <label>Password</label>
					    <input type="text" name="password" class="form-control">
					</div>

					<div class="form-group col-md-12">
					    <button type="submit" class="btn btn-primary">Create</button>
					</div>

			</form>

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
	
</body>

</html>