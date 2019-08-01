<?
$module_config['table_name'] = "sys_users";
$module_config['id_column'] = "u_id";
$module_config['object'] = 'Admin';
$module_config['default_action'] = 'list_page';

$module_fields["u_name"]      = array(0, 50, 0, "ALPHA", "Nome", "", "textfield");
$module_fields["login"]     = array(0, 20, 0, "ALPHA", "Login", "", "textfield");

if($this->action == 'create') 
	$module_fields["passwd"]    = array(0, 20, 6, "ALPHA", "Senha", "", "textfield");

$module_fields["email"]     = array(1, 100, 0, "EMAIL", "Email", "1", "textfield");

if(isset($_SESSION["admin_luid"]) && $_SESSION["admin_luid"] < 2)
	$module_fields["luid"]      = array(1, 1, 0, "ALPHA", "Perfil de acesso", "1", "combo");
$module_fields["enable"]    = array(1, 1, 0, "BOOL", "Ativo", "1", "checkbox");
