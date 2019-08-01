<!DOCTYPE html>
<html>
	<head>
		<!-- Meta -->
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<!-- End of Meta -->
		
		<!-- Page title -->
		<title><? echo($page_title) ?></title>
		<!-- End of Page title -->

		
		<!-- Libraries -->
		<link type="text/css" href="css/login.css" rel="stylesheet" />	
		<style>
		/*NOTIFICATION MESSAGES*/
		.message {
			display:block;
			padding:10px 20px;
			margin-bottom:15px;
		}
		
		.message p {
			width:auto;
			margin-bottom:0;
			margin-left:60px;
		}
		
		.message h2 {
			margin-left:60px;
			margin-bottom:5px;
		}
		
		.warning {
			background:#feffc8 url('/admin/assets/icons/warning_48.png') 20px 50% no-repeat;
			border:1px solid #f1aa2d;
		} 
		
		.message p {
			color:#555;
		}
		
		.message h2 {
			color:#333;
		}
		
		.error {
			background:#fdcea4 url('/admin/assets/icons/stop_48.png') 20px 50% no-repeat;
			border:1px solid #c44509;
		}
		
		.success {
			background:#d4f684 url('/admin/assets/icons/tick_48.png') 20px 50% no-repeat;
			border:1px solid #739f1d;
		}
		
		.information {
			background:#c3e4fd url('/admin/assets/icons/info_48.png') 20px 50% no-repeat;
			border:1px solid #688fdc;
		}
		
		.message:hover {
			cursor:pointer;
		}
		/* end of notification messages */
        </style>
		<link type="text/css" href="css/smoothness/jquery-ui-1.7.2.custom.css" rel="stylesheet" />	
		
		<script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
		<script type="text/javascript" src="js/easyTooltip.js"></script>
		<script type="text/javascript" src="js/jquery-ui-1.7.2.custom.min.js"></script>
        
		<!-- End of Libraries -->	
	</head>
	<body>

	<div id="container">
		<div class="logo">
			<a href=""><img src="assets/logo-big.png" alt="" /></a>
		</div>
        <? if ($errormsg != '') { ?>
            <div class="message error close">
                <h2>Erro!</h2>
                <p><? echo $errormsg; ?> </p>
            </div>
        <? } ?>
        
		<div id="box">
			<form action="?admin/auth" method="POST">
			<p class="main">
				<label>Usuário: </label>
				<input type="textfield" name="tx_login" value="" /> 
				<label>Senha: </label>
				<input type="password" name="tx_password" value="" />	
			</p>

			<p class="space">
				<input type="submit" value="Login" class="login" />
			</p>
			</form>
		</div>
	</div>

	</body>
</html>