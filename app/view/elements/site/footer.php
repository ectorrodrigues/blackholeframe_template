<div class="footer container padding-top" align="center">

	<div class="col8" align="center">

		<div class="col8 inline text-left">
			<?php include (ELEMENTS_DIR.'menu.php'); ?>
		</div>

		<div class="col3 inline text-left">

			<?php

				$content = '
					<strong>{telefone_geral}</strong><br/>
					{email_geral}<br/>
					{endereco}<br/>
				';

				loop(	/*table*/"dados_gerais",
						/*content*/$content, 
						/*where*/"",
						/*extras*/"", 
						/*order*/"id", 
						/*asc_desc*/"ASC",
						/*limit*/"");
			?>

		</div>

		<p class="padding-top">
			<img src="<?=IMG_DIR.'icone.svg'?>" style="width: 50px;" class="padding-top rotate" /><br/>
		</p>

	</div>

</div>

