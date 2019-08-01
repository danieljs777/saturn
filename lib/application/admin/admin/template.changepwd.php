    <? Render::custom_javascript("lib/application/admin/common/js/global.js"); ?>

    <div>
        <form action="?admin/save/<? echo $admin_id; ?>" class="mws-form" method="post" id="form_changepwd" name="form_changepwd">
        <fieldset><legend>Alteração de senha</legend>
            <input type="hidden" name="admin_id" value="<? echo $admin_id; ?>" />
            <p>
                <label>Nova senha</label>
                <input type="password" class="mws-textinput" name="tx_password">
                <span>&nbsp;&nbsp;&nbsp;Min 6 caracteres</span>
            </p>
                    
            <p>
                <label>Confirme a nova senha</label>
                <input type="password" class="mws-textinput" name="passwd">
                <span>&nbsp;&nbsp;&nbsp;Min 6 caracteres</span>
            </p>

                <input type='hidden' value='?home' id='success_action' name='success_action' />
                <? Render::div_ajax_message("div_chpwd_message"); ?>
                
            <p>
                <input type="submit" class="mws-button green" value="Salvar">
            </p>
            </fieldset>
        </form>
			
	</div>
