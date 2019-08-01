			<!-- Header -->
			<div id="header">
            <? if(System::islogged_admin()) { ?>
				<div id="top">
                
					<!-- Logo -->
					<div class="logo"> 
						<a href="?home" title="Principal" class="tooltip"><img height="60" style="margin-top:20px;margin-left:50px;" src="assets/logo-big.png" alt="<? echo SYSTEM_NAME; ?>" /></a> 
					</div>
					<!-- End of Logo -->
					
					<!-- Meta information -->
					<div class="meta">
						<p>Bem vindo, <? echo $_SESSION['admin_name']; ?>!</p>
						<ul>
							<li><a href="?admin/logoff" title="Finalizar Sessão" class="tooltip"><span class="ui-icon ui-icon-power"></span>Sair</a></li>
							<li><a href="?admin/changepwd" title="Alterar Senha" class="tooltip"><span class="ui-icon ui-icon-key"></span>Alterar Senha</a></li>
							<!--<li><a href="#" title="Alterar configurações atuais" class="tooltip"><span class="ui-icon ui-icon-wrench"></span>Configurações</a></li>-->
							<li><a href="?admin/myaccount" title="Sua conta" class="tooltip"><span class="ui-icon ui-icon-person"></span>Sua conta</a></li>
						</ul>	
					</div>
					<!-- End of Meta information -->
				</div>
                
				<!-- The navigation bar -->
				<div id="navbar">
	                <? System::get_menu(); ?>
				</div>
				<!-- End of navigation bar" -->
            <? } ?>
			</div>
			<!-- End of Header -->
